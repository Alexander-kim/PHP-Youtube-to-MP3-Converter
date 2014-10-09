<?php

/*
 * Hakan YOLAT
 * hakanyolat@hotmail.com
 *
 */

class youtubemp3{
	
	// Cool
	private $videoID;
	private $fileName;
	private $fileTime;
	private $initURL;
	private $fileCC;
	public  $fileInfo;
	
	// Create cURL init url with constructor
	public function __construct($videoID){
		$this->videoID 	= $videoID;
		$this->fileTime = time();
		$this->fileCC   = $this->videoID.$this->fileTime;
		$this->initURL  = "http://www.youtube-mp3.org/get?ab=128&video_id={$this->videoID}&r={$this->fileTime}.{$this->cc($this->fileCC)}";
	}
	
	// Create "r" parameter part for init url
	private function cc($a){
		$m = 65521;
		$b = 1;
		$c = 0;
		$d = "";
		for($i=0; $i<strlen($a); $i++){
			$d = ord($a[$i]);
			$b = ($b+$d)%$m;
			$c = ($c+$b)%$m;
		}
		return $c<<16|$b;
	}
	
	// In fact, this is a fake conversion function.
	public function convert(){
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $this->initURL);
		curl_setopt ($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
		curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie.jar');
		curl_setopt ($ch, CURLOPT_REFERER, "http://www.youtube-mp3.org/");
		curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 1);
		$content = curl_exec ($ch);
		curl_close ($ch);
		$pm		 = preg_match("/Location: (.*?)/iU", $content, $matches);
		$r 		 = rtrim($matches[1]);
		$result  = isset($r) ? array("status" => true, "url" => $r) : array("status" => false);
		if($result["status"]){
			$headers = get_headers($result["url"],1);
			$fn = explode("?", basename($result["url"]));
			$this->fileName = $fn[0];
			$this->fileInfo = array(
				"id"   => $this->videoID,
				"name" => $this->fileName,
				"size" => $headers["Content-Length"],
				"url" => $result["url"],
				"headers" => array(
					"Content-Type: {$headers["Content-Type"]}",
					"Content-Length: {$headers["Content-Length"]}",
					"Content-Transfer-Encoding: Binary",
					"Content-disposition: attachment; filename={$this->fileName}"
				)
			);
		}else{
			$this->fileInfo = false;
		}
		return $this;
	}
	
	// Create header & read file
	public function download(){
		if($this->fileInfo){
			foreach($this->fileInfo["headers"] as $headerString){
				header($headerString);
			}
			readfile($this->fileInfo["url"]);
		}
	}
}


/*
 * EXAMPLE
 * EXAMPLE URL : http://www.youtube.com/watch?v=ydRAb9cwHnA
 * EXAMPLE URL ID : ydRAb9cwHnA
 */

$MP3 	= new youtubemp3("ydRAb9cwHnA");
$MYFILE = $MP3->convert();

/*
 * Optional data ( Never use with download function! )
 * print_r($MYFILE->fileInfo);
 */

$MYFILE->download();

/*
 * tadaaa! Your MP3 file downloaded...
 * 
 * You can use this way 
 * $MP3->convert()->download();
 * because this class have a fluent interface.
 */

?>
