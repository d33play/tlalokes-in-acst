<?php 
require 'TlalokesCoreController.php';

/**
 * @ControllerDefinition( default='index' )
 */
class PublicCtl extends TlalokesCoreController {

  /**
   * @ActionDefinition( layout='public_layout.tpl', zone='header:header;body:gallery;footer:pie' )
   */
  public function index ()
  {
  	//Define the gallery directory
  	$this->response->gallery = "maingallery";

  	//Obtain the number of files in the directory
  	$this->response->imgcount = PublicBss::readDirectory( $this->path,
  			                                              $this->response->gallery );
  	
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  			$this->default_locale );
  }

  /**
   * @ActionDefinition( layout='public_layout.tpl', zone='header:header;body:content;footer:pie' )
   */
  public function about ()
  {
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  			$this->default_locale );
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone="header:header;body:content;footer:pie' )
   */
  public function products ()
  {
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  			$this->default_locale );
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone="header:header;body:gallery;body:product;footer:pie' )
   */
  public function product1 ()
  {
  	//Define the urls
  	$this->response->url2 = "";
  	
  	//Define the gallery directory
  	$this->response->gallery = "acustiflex1100";
  	
  	//Obtain the number of files in the directory
  	$this->response->imgcount = PublicBss::readDirectory( $this->path,
  														  $this->response->gallery );
  	
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  			$this->default_locale );
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone="header:header;body:gallery;body:product;footer:pie' )
   */
  public function product2 ()
  {
  	//Define the gallery directory
  	$this->response->gallery = "metalmovil1000";
  	 
  	//Obtain the number of files in the directory
  	$this->response->imgcount = PublicBss::readDirectory( $this->path,
  			$this->response->gallery );
  	 
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  			$this->default_locale );
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone="header:header;body:gallery;body:product;footer:pie' )
   */
  public function product3 ()
  {
  	//Define the gallery directory
  	$this->response->gallery = "portable850";
  
  	//Obtain the number of files in the directory
  	$this->response->imgcount = PublicBss::readDirectory( $this->path,
  			$this->response->gallery );
  
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  									   $this->default_locale );
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone="header:header;body:gallery;body:product;footer:pie' )
   */
  public function product4 ()
  {
  	//Define the gallery directory
  	$this->response->gallery = "acustiflex1050";
  
  	//Obtain the number of files in the directory
  	$this->response->imgcount = PublicBss::readDirectory( $this->path,
  			$this->response->gallery );
  
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  									   $this->default_locale );
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone="header:header;body:gallery;body:product;footer:pie' )
   */
  public function product5 ()
  {
  	//Define the gallery directory
  	$this->response->gallery = "ensamblado";
  
  	//Obtain the number of files in the directory
  	$this->response->imgcount = PublicBss::readDirectory( $this->path,
  			$this->response->gallery );
  
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  									   $this->default_locale );
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone='header:header;body:form;body:address;body:gmap;footer:pie' )
   */
  public function contact ()
  {
  	//Evaluate the active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  									   $this->default_locale );
	
	$a=0;
									   
  	//Evaluate if is getting a variable by post, send mail
  	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
  		
  		//validating the entries
  		if(preg_match("/^[a-zA-Z][a-zA-Z ]+$/", $this->request->name)){
  			$this->response->name_value = $this->request->name;
			$this->response->name_message = false;
		}
		else {
			$this->response->name_message = true;
			$a++;
		}
		
		if(preg_match("/^[0-9a-z_\-\.]+@[0-9a-z\-\.]+\.[a-z]{2,4}$/", $this->request->mail)){
  			$this->response->mail_value = $this->request->mail;
			$this->response->mail_message = false;
		}
		else {
			$this->response->mail_message = true;
			$a++;
		}
		
		if(preg_match("/\w+$/", $this->request->subject)){
  			$this->response->subject_value = $this->request->subject;
			$this->response->subject_message = false;
		}
		else {
			$this->response->subject_message = true;
			$a++;
		}
		
		if(preg_match("/\w+$/", $this->request->message)){
  			$this->response->message_value = $this->request->message;
			$this->response->message_message = false;
		}
		else {
			$this->response->message_message = true;
			$a++;	
		}
		
		
  		// send mail
  		if($a == 0){
  			$this->response->form_message = true;

			$to1= $this->response->mail_value;
			$subject1 = "Contacto con Acustimuros: ".(string)$this->response->subject_value;
			$mess1 = '			
				<div style="margin: auto; padding: auto; color: black; text-align: center;">
				<h3 style=" color:#190710; " >Gracias por tus comentarios! </h3>
				<p>Estimado '.(string)$this->response->name_value.': </p>
				<p>Hemos recibido exitosamente tu mensaje:<br />
				<br /><br />
				<strong>'.(string)$this->response->message_value.'</strong><br /></p>
				<p>En breve uno de nuestros ejecutivos se pondrá en contacto contigo.</p>
				</div>';
			
			$header = "From: contacto@acustimuros.com\n";
			$header .= "Content-type: text/html\r\n";
			$header .= "Content-type: text/html; charset=UTF-8\r\n";
			
			//message for success send
			if(mail($to1, $subject1, $mess1, $header))
				$this->response->form_message = true;

			
			//clear all the variable values 			
  			unset($this->response->name_value);
			unset($this->response->mail_value);
			unset($this->response->subject_value);
			unset($this->response->message_value);
  			
		}
  	}

  	
  	 
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone='header:header;body:map;footer:pie' )
   */
  public function agents ()
  {
  
  	//Evaluate the active language to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  									   $this->default_locale );
  
  }
}

?>