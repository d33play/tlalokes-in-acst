<div class="sixteen columns">
	<div class="three columns">
		<a href="<?=$uri;?>public/index<?=$_locale_uri;?>"><img src="<?=$img;?>logo.png" height="90px" /></a>
	</div>
	<nav class="eleven columns">
		<ul class="navegacion n1" style="float: right;">
			<li><a href="<?=$uri;?>public/<?=$_locale_uri;?>"><?=$menu0;?></a></li>
			<li><a href="<?=$uri;?>public/about<?=$_locale_uri;?>"><?=$menu1;?></a></li>
			<li><a href="<?=$uri;?>public/products<?=$_locale_uri;?>"><?=$menu2;?></a></li>
			<li><a href="<?=$uri;?>public/contact<?=$_locale_uri;?>"><?=$menu3;?></a></li>
		</ul>
		
<?php if(strpos($_action,"product")!==false): ?>
		<ul class="navegacion n2" style="float: right;">
			<li><a href="<?=$uri;?>public/product1<?=$_locale_uri;?>"><?=$productmenu1;?></a></li>
			<li><a href="<?=$uri;?>public/product2<?=$_locale_uri;?>"><?=$productmenu2;?></a></li>
			<li><a href="<?=$uri;?>public/product3<?=$_locale_uri;?>"><?=$productmenu3;?></a></li>
			<li><a href="<?=$uri;?>public/product4<?=$_locale_uri;?>"><?=$productmenu4;?></a></li>
			<li><a href="<?=$uri;?>public/product5<?=$_locale_uri;?>"><?=$productmenu5;?></a></li>
		</ul>
<?php elseif($_action=="contact" || $_action=="agents"): ?>
		<ul class="navegacion n2" style="float: right;">
			<li><a href="<?=$uri;?>public/agents<?=$_locale_uri;?>"><?=$contactmenu1;?></a></li>
		</ul>
<?php endif; ?>
	</nav>
	<div class="one column" style="float: right">
		<a href="<?=$uri;?>public/<?=$_action.$changelang;?>"><img src="<?=$img.$_locale.'.png'?>" /></a>
	</div>
</div>
