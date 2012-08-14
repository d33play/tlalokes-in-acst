			<div class="one-third column content"><div>
	    		<h3><?=$formtitle;?></h3>
	    		<form>
	    			<label for="name"><?=$formname;?></label>
	    			<input type="text" name="name" id="name" />
	    			
	    			<label for="mail"><?=$mail;?></label>
	    			<input type="email" name="mail" id="mail" required />
	    			
	    			<label for="subject"><?=$formsubject;?></label>
	    			<input type="text" name="subject" id="subject" />
	    			
	    			<label for="tel"><?=$tel;?></label>
	    			<input type="text" name="tel" id="tel" />
	    			
	    			<label for="message"><?=$formmessage;?></label>
	    			<textarea name="message" id="message"></textarea> 
	    			
	    			<button type="submit"><?=$formsubmit;?></button>
	    		</form>
	    	</div></div>