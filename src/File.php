<?php 
namespace ZenAPI;

class File{
	
	public $content;
	
	public $filename;
	
	public $mime;
	
	public function __construct($content, $filename, $mime = 'image/unknown'){
		$this->content = $content;
		$this->filename = $filename;
		$this->mime = $mime;
	}
	
	public static function createByUrl($url){
		$content = file_get_contents($url);
		
		if ($content === false)
			throw new Exception('Failed to get file ' . $url);
		
		$array = explode( '?', basename( $url ) );
		$filename = $array[0];
		
		return new self($content, $filename);
	}
}
