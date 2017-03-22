<?php

use Phalcon\Mvc\View;
use Ajax\semantic\html\elements\HtmlButton;
use Ajax\semantic\html\elements\HtmlButtonGroups;
use Ajax\semantic\html\elements\HtmlLabel;
class IndexController extends ControllerBase{

    public function indexAction(){
    	$this->secondaryMenu($this->controller,$this->action);
    	$this->tools($this->controller,$this->action);

    	$semantic=$this->semantic;

    	$semantic->htmlButton("btApache","Apache file","green")->getOnClick("Index/readApache","#file");
		$semantic->htmlButton("btNginx","NginX file","black")->getOnClick("Index/readNginX","#file");
		$semantic->htmlButton("btStypes","Types de servers","red")->getOnClick("SType/index","#content-container");
		$semantic->htmlButton("btAdmin","Administration","red")->getOnClick("Admin/index","#content-container");
		$btEx=$semantic->htmlButton("btEx","Test des échanges client/serveur")->getOnClick("ServerExchange/index","#content-container");
		$btEx->addLabel("New");

		$grid=$semantic->htmlGrid("grid");
		$grid->setStretched()->setCelled(true);
		$grid->addRow(2)->setValues(["Page principale user",$this->createBts("mainUser",["Mes services"=>"/my/index"],"","1&nbsp;&nbsp;&nbsp;")]);
		$grid->addRow(2)->setValues(["VirtualHosts par machine (host)",$this->createBts("hosts",["Liste des VirtualHosts par host"=>"/Display/host/1"],"","2&nbsp;&nbsp;&nbsp;")]);
		$grid->addRow(2)->setValues(["Détail d'un virtualhost sur dédié",$this->createBts("virtualhosts",["Virtualhost detail sur host"=>"/Display/virtualhost/4/1"],"","3.a")]);
		$grid->addRow(2)->setValues(["Détail d'un virtualhost sur mutualisé",$this->createBts("virtualhosts",["Virtualhost detail"=>"/Display/virtualhost/2"],"","3.b")]);

		$grid->setColWidth(0,4);
		$grid->setColWidth(1,12);
		$this->jquery->getOnClick(".clickable", "","#content-container",["attr"=>"data-ajax"]);
		$this->jquery->get("Auth/index","#divInfoUser");
		$this->view->setVar("ajax", $this->request->isAjax());
		$this->jquery->compile($this->view);
    }

    private function createBts($name,$actions,$color="",$todo=null){
    	$bts=new HtmlButtonGroups("bg-".$name);
    	foreach ($actions as $k=>$action){
    		$bt=new HtmlButton($k."-".$action);
    		$bt->setValue($k);
    		$bt->setProperty("data-ajax", $action);
    		$bt->addToProperty("class", "clickable");
    		$bt->setColor($color);
    		if(isset($todo)){
    			$bt->addLabel("//TODO ".$todo,true)->setColor("blue");
    		}
    		$bts->addElement($bt);
    	}

    	return $bts;

    }

    public function hostsAction(){
    	$this->tools($this->controller,$this->action);
    	$this->jquery->get("Index/secondaryMenu/{$this->controller}/{$this->action}","#secondary-container");
		$this->jquery->compile($this->view);
    }

    public function virtualhostsAction(){
    	$this->tools($this->controller,$this->action);
    	$this->jquery->get("Index/secondaryMenu/{$this->controller}/{$this->action}","#secondary-container");
    	$this->jquery->compile($this->view);
    }

    public function newVirtualhostAction(){

    }

    public function readApacheAction(){
    	$this->readAndHighlightAll("apache", "apacheconf");
    }

    public function readNginXAction(){
    	$this->readAndHighlightAll("nginx", "javascript");
    }

    private function readAndHighlightAll($file,$language){
    	$fileContent=trim(htmlspecialchars(file_get_contents($this->view->getViewsDir()."/{$file}.cnf")));
    	echo "<pre><code class='language-{$language}'>".$fileContent."</code></pre>";
    	$this->jquery->exec("Prism.highlightAll();",true);
    	echo $this->jquery->compile($this->view);
    }

}

