<?php

	/**
	* Simple cache for cURL requests
	*/
	class Cache
	{
		private $filename;
		private $post;
		private $hash;

		function __construct($url, $post = array())
		{
			$this->hash = md5($url.json_encode($post));
			$this->filename = 'temp/'.$this->hash;
			$this->post     = $post;
		}

		function hash(){
			return $this->hash;
		}

		function get(){
			if(file_exists($this->filename)){
				return file_get_contents($this->filename);
			}else{
				return false;
			}
		}

		function set($data){
			file_put_contents($this->filename, $data);
		}
	}