			<div class="one-third column content"><div>
	    		<h3><?=$formtitle;?></h3>
	    		<div class="message"><?=$form_message;?></div>
	    		<form action="<?=$uri;?>public/contact/<?=$_locale_uri;?>" method="post">
	    			<label for="name"><?=$formname;?></label>
	    			<input type="text" name="name" id="name" value="<?=$name;?>" />
	    			
	    			<label for="mail"><?=$mail;?></label>
	    			<input type="email" name="mail" id="mail" value="<?=$mail;?>"required />
	    			
	    			<label for="subject"><?=$formsubject;?></label>
	    			<input type="text" name="subject" id="subject" value="<?=$subject;?>"/>
	    			
	    			<label for="tel"><?=$tel;?></label>
	    			<input type="text" name="tel" id="tel" value="<?=$tel;?>"/>
	    			
	    			<label for="message"><?=$formmessage;?></label>
	    			<textarea name="message" id="message"><?=$message;?></textarea> 
	    			
	    			<button type="submit"><?=$formsubmit;?></button>
	    		</form>
	    	</div></div>