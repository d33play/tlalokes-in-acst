<?php 

class PublicBss{
	
	/**
	 * @param array $path app path
	 * @param string $directory gallery directory
	 * @return int number of files in the directory
	 */
	public static function readDirectory( array &$path, $directory )
	{
		$gallery_path = preg_replace( '/^(.*)app\/?$/', '$1', $path['app'] ) .
		                'htdocs/img/' . $directory;
		
		return count( scandir( $gallery_path ) ) - 2;
	}

	/**
	 * @param string $current current locale
	 * @param string $default default locale
	 * @return string contains langurl, lang and changelang
	 */
	public static function evaluateLocale ( $current, $default )
	{
		if ( $current != $default ) {	
			return '/locale/spa';
		}
		return '/locale/eng';

	}
}

?>