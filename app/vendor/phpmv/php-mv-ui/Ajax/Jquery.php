<?php

namespace Ajax;

use Ajax\common\traits\JqueryEventsTrait;
use Ajax\common\traits\JqueryAjaxTrait;
use Ajax\common\traits\JqueryActionsTrait;
use Ajax\service\Javascript;

/**
 * jQuery Class
 *
 * @author jcheron
 * @version 1.002
 * @license Apache 2 http://www.apache.org/licenses/
 **/
class Jquery {
	use JqueryEventsTrait,JqueryAjaxTrait,JqueryActionsTrait;
	protected $_ui;
	protected $_bootstrap;
	protected $_semantic;
	protected $jquery_code_for_compile=array ();
	protected $jsUtils;
	protected $params;

	protected $jquery_events=array (
			"bind","blur","change","click","dblclick","delegate","die","error","focus","focusin","focusout","hover","keydown","keypress","keyup","live","load","mousedown","mousseenter","mouseleave","mousemove","mouseout","mouseover","mouseup","off","on","one","ready","resize","scroll","select","submit","toggle","trigger","triggerHandler","undind","undelegate","unload"
	);

	public function ui($ui=NULL) {
		if ($ui!==NULL) {
			$this->_ui=$ui;
		}
		return $this->_ui;
	}

	public function bootstrap($bootstrap=NULL) {
		if ($bootstrap!==NULL) {
			$this->_bootstrap=$bootstrap;
		}
		return $this->_bootstrap;
	}

	public function semantic($semantic=NULL) {
		if ($semantic!==NULL) {
			$this->_semantic=$semantic;
		}
		return $this->_semantic;
	}

	public function __construct($params,$jsUtils) {
		$this->params=array();
		foreach ( $params as $key => $val ) {
				$this->params[$key]=$params[$key];
		}
		$this->jsUtils=$jsUtils;
		if(isset($params["ajaxTransition"]))
			$this->ajaxTransition=$this->setAjaxDataCall($params["ajaxTransition"]);
	}

	/**
	 * Inline
	 *
	 * Outputs a <script> tag
	 *
	 * @access public
	 * @param string $script
	 * @param boolean $cdata a CDATA section should be added
	 * @return string
	 */
	public function inline($script, $cdata=TRUE) {
		$str=$this->_open_script();
		$str.=($cdata) ? "\n// <![CDATA[\n{$script}\n// ]]>\n" : "\n{$script}\n";
		$str.=$this->_close_script();

		return $str;
	}

	/**
	 * Open Script
	 *
	 * Outputs an opening <script>
	 *
	 * @access private
	 * @param string $src
	 * @return string
	 */
	private function _open_script($src='') {
		$str='<script type="text/javascript" ';
		$str.=($src=='') ? '>' : ' src="'.$src.'">';
		return $str;
	}

	/**
	 * Close Script
	 *
	 * Outputs an closing </script>
	 *
	 * @param string
	 * @return string
	 */
	private function _close_script($extra="\n") {
		return "</script>{$extra}";
	}

	public function _setAjaxLoader($loader) {
		$this->ajaxLoader=$loader;
	}

	/**
	 * Outputs script directly
	 *
	 * @param string The element to attach the event to
	 * @param string The code to execute
	 * @return string
	 */
	public function _output($array_js='') {
		if (!is_array($array_js)) {
			$array_js=array (
					$array_js
			);
		}

		foreach ( $array_js as $js ) {
			$this->jquery_code_for_compile[]="\t$js\n";
		}
	}

	/**
	 * Execute a generic jQuery call with a value.
	 * @param string $jQueryCall
	 * @param string $element
	 * @param string $param
	 * @param boolean $immediatly delayed if false
	 */
	public function _genericCallValue($jQueryCall,$element='this', $param="", $immediatly=false) {
		$element=Javascript::prep_element($element);
		if (isset($param)) {
			$param=Javascript::prep_value($param);
			$str="$({$element}).{$jQueryCall}({$param});";
		} else
			$str="$({$element}).{$jQueryCall}();";
			if ($immediatly)
				$this->jquery_code_for_compile[]=$str;
			return $str;
	}
	/**
	 * Execute a generic jQuery call with 2 elements.
	 * @param string $jQueryCall
	 * @param string $to
	 * @param string $element
	 * @param boolean $immediatly delayed if false
	 * @return string
	 */
	public function _genericCallElement($jQueryCall,$to='this', $element, $immediatly=false) {
		$to=Javascript::prep_element($to);
		$element=Javascript::prep_element($element);
		$str="$({$to}).{$jQueryCall}({$element});";
		if ($immediatly)
			$this->jquery_code_for_compile[]=$str;
			return $str;
	}

	/**
	 * Creates a jQuery sortable
	 *
	 * @param string $element
	 * @param array $options
	 * @return void
	 */
	public function sortable($element, $options=array()) {
		if (count($options)>0) {
			$sort_options=array ();
			foreach ( $options as $k => $v ) {
				$sort_options[]="\n\t\t".$k.': '.$v."";
			}
			$sort_options=implode(",", $sort_options);
		} else {
			$sort_options='';
		}

		return "$(".Javascript::prep_element($element).").sortable({".$sort_options."\n\t});";
	}

	/**
	 * Table Sorter Plugin
	 *
	 * @param string $table table name
	 * @param string $options plugin location
	 * @return string
	 */
	public function tablesorter($table='', $options='') {
		$this->jquery_code_for_compile[]="\t$(".Javascript::prep_element($table).").tablesorter($options);\n";
	}

	/**
	 * Constructs the syntax for an event, and adds to into the array for compilation
	 *
	 * @param string $element The element to attach the event to
	 * @param string $js The code to execute
	 * @param string $event The event to pass
	 * @param boolean $preventDefault If set to true, the default action of the event will not be triggered.
	 * @param boolean $stopPropagation Prevents the event from bubbling up the DOM tree, preventing any parent handlers from being notified of the event.
	 * @return string
	 */
	public function _add_event($element, $js, $event, $preventDefault=false, $stopPropagation=false,$immediatly=true) {
		if (\is_array($js)) {
			$js=implode("\n\t\t", $js);
		}
		if ($preventDefault===true) {
			$js=Javascript::$preventDefault.$js;
		}
		if ($stopPropagation===true) {
			$js=Javascript::$stopPropagation.$js;
		}
		if (array_search($event, $this->jquery_events)===false)
			$event="\n\t$(".Javascript::prep_element($element).").bind('{$event}',function(event){\n\t\t{$js}\n\t});\n";
		else
			$event="\n\t$(".Javascript::prep_element($element).").{$event}(function(event){\n\t\t{$js}\n\t});\n";
		if($event==="click")
			$js.=Javascript::$clickDblclickConflict;
		if($immediatly)
			$this->jquery_code_for_compile[]=$event;
		return $event;
	}

	/**
	 * As events are specified, they are stored in an array
	 * This function compiles them all for output on a page
	 * @param view $view
	 * @param string $view_var
	 * @param boolean $script_tags
	 * @return string
	 */
	public function _compile(&$view=NULL, $view_var='script_foot', $script_tags=TRUE) {
		$this->_compileLibrary($this->ui());
		$this->_compileLibrary($this->bootstrap());
		$this->_compileLibrary($this->semantic());

		if (\sizeof($this->jquery_code_for_compile)==0) {
			return;
		}

		// Inline references
		$script='$(document).ready(function() {'."\n";
		$script.=implode('', $this->jquery_code_for_compile);
		$script.='})';
		if($this->params["defer"]){
			$script=$this->defer($script);
		}
		$script.=";";
		$this->jquery_code_for_compile=array();
		if($this->params["debug"]===false){
			$script=$this->minify($script);
		}
		$output=($script_tags===FALSE) ? $script : $this->inline($script);

		if ($view!==NULL){
			$this->jsUtils->createScriptVariable($view,$view_var, $output);
		}
		return $output;
	}

	public function getScript($offset=0){
		$code=$this->jquery_code_for_compile;
		if($offset>0)
			$code=\array_slice($code, $offset);
		return implode('', $code);
	}

	public function scriptCount(){
		return \sizeof($this->jquery_code_for_compile);
	}

	private function defer($script){
		$result="window.defer=function (method) {if (window.jQuery) method(); else setTimeout(function() { defer(method) }, 50);};";
		$result.="window.defer(function(){".$script."})";
		return $result;
	}

	private function _compileLibrary($library){
		if ($library!=NULL) {
			if ($library->isAutoCompile()) {
				$library->compile(true);
			}
		}
	}

	public function _addToCompile($jsScript) {
		$this->jquery_code_for_compile[]=$jsScript;
	}

	/**
	 * Clears the array of script events collected for output
	 *
	 * @return void
	 */
	public function _clear_compile() {
		$this->jquery_code_for_compile=array ();
	}

	/**
	 * A wrapper for writing document.ready()
	 * @return string
	 */
	public function _document_ready($js) {
		if (!is_array($js)) {
			$js=array (
					$js
			);
		}

		foreach ( $js as $script ) {
			$this->jquery_code_for_compile[]=$script;
		}
	}

	private function minify($input) {
	if(trim($input) === "") return $input;
	return preg_replace(
			array(
					// Remove comment(s)
					'#\s*("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')\s*|\s*\/\*(?!\!|@cc_on)(?>[\s\S]*?\*\/)\s*|\s*(?<![\:\=])\/\/.*(?=[\n\r]|$)|^\s*|\s*$#',
					// Remove white-space(s) outside the string and regex
					'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/)|\/(?!\/)[^\n\r]*?\/(?=[\s.,;]|[gimuy]|$))|\s*([!%&*\(\)\-=+\[\]\{\}|;:,.<>?\/])\s*#s',
					// Remove the last semicolon
					'#;+\}#',
					// Minify object attribute(s) except JSON attribute(s). From `{'foo':'bar'}` to `{foo:'bar'}`
					'#([\{,])([\'])(\d+|[a-z_][a-z0-9_]*)\2(?=\:)#i',
					// --ibid. From `foo['bar']` to `foo.bar`
					'#([a-z0-9_\)\]])\[([\'"])([a-z_][a-z0-9_]*)\2\]#i'
			),
			array(
					'$1',
					'$1$2',
					'}',
					'$1$3',
					'$1.$3'
			),
			$input);
	}
}
