<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>

	<!-- Basic Page Needs
  ================================================== -->
	<meta charset="utf-8">
	<title><?=$title;?></title>
	<meta name="description" content="<?=$description;?>">
	<meta name="author" content="<?=$author;?>">

	<!-- Mobile Specific Metas
  ================================================== -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS
  ================================================== -->
	<link rel="stylesheet" href="<?=$css;?>base.css">
	<link rel="stylesheet" href="<?=$css;?>skeleton.css">
	<link rel="stylesheet" href="<?=$css;?>layout.css">

	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- Favicons
	================================================== -->
	<link rel="shortcut icon" href="<?=$img;?>favicon.ico">
	<link rel="apple-touch-icon" href="<?=$img;?>apple-touch-icon.png">
	<link rel="apple-touch-icon" sizes="72x72" href="<?=$img;?>apple-touch-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="114x114" href="<?=$img;?>apple-touch-icon-114x114.png">

	<!-- Nivo Slider
  	================================================== -->
  	<link rel="stylesheet" href="<?=$css;?>nivo-slider.css" type="text/css" media="screen" />
  	<link rel="stylesheet" href="<?=$css;?>theme-nivoslider.css" type="text/css" media="screen" />
  	
  	<!-- Map
  	================================================== -->
  	<script src="https://ajax.googleapis.com/ajax/libs/mootools/1.3.0/mootools.js"></script>
</head>
<body>



	<!-- Primary Page Layout
	================================================== -->

	<div class="container">
		<header>
<?php

tlalokes_layout_zone('header',$_layout);

?>		
		</header>
		<div class="sixteen columns">
<?php

tlalokes_layout_zone('body',$_layout);

?>
		</div>
<?php 
	if($title1 && $content1): 
?>	
    	<div class="one-third column content"><div>
			<h3><?=$title1;?></h3>
			<p><?=$content1;?></p>
		</div></div>
<?php 
	endif; 

	if($title2 && $content2): 
?>	
		<div class="one-third column content"><div>
			<h3><a href="<?=$url2;?>"><?=$title2;?></a></h3>
			<p><?=$content2;?></p>
		</div></div>	
<?php 
	endif; 

	if($title3 && $content3): 
?>	
		<div class="one-third column content"><div>
			<h3><?=$title3;?></h3>
			<p><?=$content3;?></p>
		</div></div>
<?php 
	endif; 
?>		
		<div style="clear: both"></div>
		<footer>
<?php

tlalokes_layout_zone('footer',$_layout);

?>			
		</footer>

	
	</div><!-- container -->
	

<!-- End Document
================================================== -->
</body>
</html>