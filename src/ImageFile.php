<?php 
namespace ZenOAuth2;

class ImageFile{
	
	public $content;
	
	public $filename;
	
	public function __construct($content, $filename){
		$this->content = $content;
		$this->filename = $filename;
	}
	
	public static function createByUrl($url){
		$content = file_get_contents($url);
		
		if (empty($content))
			throw new Exception('Image is Empty');
		
		$array = explode( '?', basename( $url ) );
		$filename = $array[0];
		
		return new self($content, $filename);
	}
}
