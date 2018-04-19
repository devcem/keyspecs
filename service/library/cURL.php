<?php

	/**
	* It allows to transfer data over the TCP protocol
	*/
	class cURL
	{
		private $response = '';
		private $status   = 500;
		private $url      = '';
		private $post     = array();

		function __construct($url, $post = false, $userAgent = false){
			$this->url   = $url;
			$this->post  = $post;
			$cache       = new Cache($this->url, $this->post);

			if($cache->get()){
				$this->response = $cache->get();
			}else{
				$handle = curl_init($this->url);

			    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
			    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);
			    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

			    if($userAgent){
			    	curl_setopt($handle, CURLOPT_USERAGENT, $userAgent);
			    }else{
			    	curl_setopt($handle, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/65.0.3325.181 Safari/537.36');
			    }

			    if($this->post){
			    	//Converts string
			    	$post = json_encode($this->post);

					curl_setopt($handle, CURLOPT_CUSTOMREQUEST, "POST");      
					curl_setopt($handle, CURLOPT_POSTFIELDS, $post);   

					curl_setopt($handle, CURLOPT_HTTPHEADER, array(                            
						'Content-Type: application/json',                                          
						'Content-Length: ' . strlen($post))
					); 
			    }

			    $response = curl_exec($handle);
			    $code     = curl_getinfo($handle, CURLINFO_HTTP_CODE);

			    curl_close($handle);

			    $this->status   = $code;
			    $this->response = $response;
			    
			    $cache = new Cache($this->url, $this->post, $response);
			    $cache = $cache->set($response);
			}
		}

		function parse($format = true){
			return json_decode($this->get(), $format);
		}

		function get(){
			return $this->response;
		}

		function status(){
			return $this->status;
		}
	}