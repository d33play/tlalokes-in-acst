			<div class="one-third column content"><div>
	    		<h3><?=$formtitle;?></h3>
<?php
	if($form_message):
?>
	    		<div class="message"><?=$form_sent_message;?></div>
<?php
	endif;
?>	 
	    		<form action="<?=$uri;?>public/contact/<?=$_locale_uri;?>" method="post">
	    			<label for="name"><?=$formname;?></label>
	    			<input type="text" name="name" id="name" value="<?=$name_value;?>" pattern="[a-zA-Z][a-zA-Z ]+" />
<?php
	if($name_message):
?>
	    			<div class="message"><?=$form_name_message;?></div>
<?php
	endif;
?>	    			
	    			<label for="mail"><?=$mail;?></label>
	    			<input type="email" name="mail" id="mail" value="<?=$mail_value;?>" required />
<?php
	if($mail_message):
?>
	    			<div class="message"><?=$form_mail_message;?></div>
<?php
	endif;
?>		    			
	    			<label for="subject"><?=$formsubject;?></label>
	    			<input type="text" name="subject" id="subject" value="<?=$subject_value;?>"/>
<?php
	if($subject_message):
?>
	    			<div class="message"><?=$form_subject_message;?></div>
<?php
	endif;
?>	    			
	    			<label for="tel"><?=$tel;?></label>
	    			<input type="tel" name="tel" id="tel" value="<?=$tel_value;?>"/>
	    			
	    			<label for="message"><?=$formmessage;?></label>
	    			<textarea name="message" id="message"><?=$message_value;?></textarea> 
<?php
	if($message_message):
?>
	    			<div class="message"><?=$form_message_message;?></div>
<?php
	endif;
?>	        			
	    			<button type="submit"><?=$formsubmit;?></button>
	    		</form>
	    	</div></div>