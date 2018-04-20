<?php

	/**
	* 
	*/
	class EbayParser
	{
		private $results = array();
		
		function __construct($pages){
			foreach ($pages as $key => $content) {
				$this->results[] = $this->getTable($content);
			}
		}

		function getTable($content){
			$output = array();

			preg_match_all('/<li(.*?)><div class="s-name">(.*?)<\/div><div class="s-value">(.*?)<\/div><\/li>/', $content, $matches);

			foreach ($matches[2] as $key => $value) {
				$output[] = array(
					'key'    => $this->clean($matches[2][$key]),
					'values' => $this->clean($matches[3][$key])
				);
			}

			return $output;
		}

		function clean($value){
			$value = str_replace('<br>', ", ", $value);
			$value = str_replace('<br/>', ", ", $value);
			$value = strip_tags($value);

			$value = explode(', ', $value);

			if(count($value) > 1){
				return array_values(array_filter($value));
			}else{
				return $value[0];
			}
		}

		function get(){
			return $this->results;
		}
	}