<div class="doc-section clearfix content">
	<div>
	<h3><?=$head1;?></h3>
	<p><?=$para1;?></p>
	<h3><?=$head2;?></h3>
	<p><?=$para2;?></p>
	</div>
	<div class="row">
		<div class="seven columns alpha"><div>
			<h3><?=$head3;?></h3>
			<p><?=$para3;?></p>
			<h3><?=$head4;?></h3>
			<p><?=$para4;?></p>
			<h3><?=$head5;?></h3>
			<p><?=$para5;?></p>
		</div></div>
		<div class="five columns omega"><div>
<?php 
	if($quote1 && $cite1): 
?>	
			<blockquote>
				<p><?=$quote1;?></p>
				<cite><?=$cite1;?></cite>
			</blockquote>
<?php 
	endif; 

	if($quote2 && $cite2): 
?>
			<blockquote>
				<p><?=$quote2;?></p>
				<cite><?=$cite2;?></cite>
			</blockquote>
<?php 
	endif; 

	if($quote3 && $cite3): 
?>
			<blockquote>
				<p><?=$quote3;?></p>
				<cite><?=$cite3;?></cite>
			</blockquote>
<?php 
	endif; 
?>
		</div></div>
	</div>
</div>