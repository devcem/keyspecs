<?php

	/**
	* 
	*/
	class Search
	{
		public $result = '';

		function __construct($query){
			$site = new cURL('https://duckduckgo.com/html/?q='.urlencode($query).'');
			$html = $site->get();

			preg_match_all('/<a class="result__url" href="(.*?)">/', $html, $matches);

			$output = array();

			foreach ($matches[1] as $key => $item) {
				if(strpos($item, 'youtube') === false){
					$output[] = urldecode($item);
				}
			}
			
			/*
			preg_match_all('/<h3 class="r"><a href="(.*?)"/', $html, $matches);

			$output = array();

			foreach ($matches[1] as $key => $item) {
				if(strpos($item, 'youtube') === false){
					$output[] = urldecode($item);
				}
			}
			*/

			$this->result = $output;
		}

		function get(){
			return $this->result;
		}
	}