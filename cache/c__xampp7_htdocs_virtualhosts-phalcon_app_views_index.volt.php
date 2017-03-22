<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
<title>Phalcon PHP Framework</title>
<?= $this->tag->stylesheetLink('https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.css') ?>
<?= $this->tag->stylesheetLink('https://cdnjs.cloudflare.com/ajax/libs/prism/1.5.1/themes/prism-okaidia.min.css') ?>
<?= $this->tag->stylesheetLink('public/css/styles.css') ?>
<?= $this->tag->javascriptInclude('https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js') ?>

</head>
<body>
	<header class="navbar navbar-static-top bs-docs-nav" id="top"
		role="banner">
		<div class="ui container">
			<div class="ui menu secondary">
				<div class="header item">
					<i class="snapchat ghost big link icon"></i>
					Virtualhosts
				</div>
				<a class="item">Features</a> 
				<a class="item">Testimonials</a>
				<div class="right menu">
					<div id="divInfoUser" class="item">Login</div>
				</div>
			</div>
		</div>
	</header>
	<div class="pagehead">
		<div id="secondary-container" class="ui container">
			<?= $q['secondary'] ?>
		</div>
	</div>
	<div id="main-container" class="ui container">
		<div id="tools-container">
			<?= $q['tools'] ?>
		</div>
		<div id="content-container" class="ui segment"><?= $this->getContent() ?></div>
	</div>
	<footer>
		<div class="ui container">Mentions légales :
			<ul>
				<li><a href="https://phalconphp.com/fr/">© 2016 phalcon 3.0</a></li>
				<li><a href="http://phpmv-ui.kobject.net/">© 2017 phpMv-UI 2.2</a></li>
			</ul>
		</div>
	</footer>
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<!-- Latest compiled and minified JavaScript -->
	<?= $this->tag->javascriptInclude('https://cdnjs.cloudflare.com/ajax/libs/prism/1.5.1/prism.min.js') ?>
	<?= $this->tag->javascriptInclude('https://cdnjs.cloudflare.com/ajax/libs/prism/1.5.1/components/prism-apacheconf.min.js') ?>
	<?= $this->tag->javascriptInclude('https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.2.6/semantic.min.js') ?>
	<?php if (isset($script_foot)) { ?>
	<?= $script_foot ?>
	<?php } ?>
</body>
</html>
