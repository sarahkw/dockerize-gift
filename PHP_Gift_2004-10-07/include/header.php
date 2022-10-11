<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=iso-8859-15"/>
		<link rel="Shortcut Icon" type="image/png" href="/images/flower.png"/>
		<title><?php
if ($windowTitle)
	echo $windowTitle;
else
	echo $defaultWindowTitle;
		?></title>
		<script language="javascript" src="/data/global.js" type='text/javascript'></script>
		<link rel="stylesheet" href="/data/global.css" type="text/css"/>
	</head>
	<body>
		<div class="titleBar">
			<a href="/">
				<img src="/images/titlebar.bg.left.jpg" height="50" width="95" border="0" alt="" class="top"/>
				<span class="titleBarTextShadow">gift. m o n o sock.org</span>
				<span class="titleBarText">gift. m o n o sock.org</span>
			</a>
		</div>
		<div class="pageTitle">|&nbsp;<?php
			if ($pageTitle)
				echo $pageTitle;
			elseif (dirname($_SERVER['SCRIPT_NAME']) != '/'){
				$currentPage = dirname($_SERVER['SCRIPT_NAME']);
				$pageTitle = ereg_replace('^/', '', $currentPage);
				$pageTitle = str_replace('/', ' | ', $pageTitle);
				$pageTitle = str_replace('_', ' ', $pageTitle);
				echo $pageTitle;
			}
			else
			echo "PHP::Gift&nbsp;$version";
			?>&nbsp;</div>
		<div class="menu" style="float: right;">
		<ul id="nav">
			<li><a href="/">home</a></li>
		<?php
			$menu = getMenuItems ($_SERVER["DOCUMENT_ROOT"]);
			echo displayMenu($_SERVER["DOCUMENT_ROOT"], $menu, $currentPage);
			if ($_REQUEST['slideshow'] == 'show')
				$toggle = "hide";
			else
				$toggle = "show";
		?>
		</ul>
		<br/>
		</div>
		<div class="content center">
