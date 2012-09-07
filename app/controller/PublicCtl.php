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
  	//Evaluate if is getting a variable by post, show the message, else, show the form
  	if ( $_SERVER['REQUEST_METHOD'] == 'POST' ){
  		//validar entradas y en caso de estar bien, pasarlas a response
  		$this->response->name_valid = $this->request->name;
  		// send mail
  		$this->response->form_message = 'mandado';
  	} else {
  		// display form
  		$this->response->form_message = '';
	}
  	//Evaluate the active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  									   $this->default_locale );
  	 
  }
  
  /**
   * @ActionDefinition( layout='public_layout.tpl', zone='header:header;body:map;footer:pie' )
   */
  public function agents ()
  {
  
  	//Evaluate de active languaje to use it or not in the url
  	$this->response->changelang = PublicBss::evaluateLocale( $this->response->_locale,
  									   $this->default_locale );
  
  }
}

?>