    <div class="slider-wrapper theme-default">
        <div id="slider" class="nivoSlider">
<?php
	for($i=1;$i<$imgcount+1;$i++):
?>
            <img src="<?=$img.$gallery;?>/<?=$i;?>.jpg" data-thumb="<?=$img.$gallery;?>/<?=$i;?>.jpg" alt="" data-transition="fade" />
<?php
	endfor;
?>
        </div>
    </div>


    <script type="text/javascript" src="<?=$js;?>jquery-1.7.1.min.js"></script>
    <script type="text/javascript" src="<?=$js;?>jquery.nivo.slider.js"></script>
    <script type="text/javascript">
    $(window).load(function() {
        $('#slider').nivoSlider();
    });
    </script>
