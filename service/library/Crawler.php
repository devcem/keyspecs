<?php

	class Crawler{

		private $results = array();

		function __construct($links){
			$curl = new Zebra_cURL();
			$curl->cache('temp/cache', 3600);
			$curl->ssl(false);

			$curl->threads = 30;
			
			$curl->get($links, array($this, 'parse'), $links);
		}

		function parse($result, $pages){
			if ($result->info['http_code'] == 200) {
				$this->results[] = $result->body;
			}
		}

		function get(){
			return $this->results;
		}
	}