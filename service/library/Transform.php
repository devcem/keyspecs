<?php
	
	/**
	* Transformer
	*/
	class Transform{

		private $result = array();
		private $keys   = array();

		function __construct($content){
			foreach ($content as $index => $item) {
				foreach ($item as $item_index => $row) {
					$key = metaphone($row['key']);

					if(!in_array($key, $this->keys)){
						$this->results[] = $row;
						$this->keys[] = ($key);
					}
				}
			}

			//Clear same values
		}

		function get(){
			return $this->results;
		}
	}