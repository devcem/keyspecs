<?php

	header('Content-Type: application/json');

	include 'library/pdo.php';
	include 'library/configuration.php';
	include 'library/Search.php';
	include 'library/Cache.php';
	include 'library/cURL.php';
	include 'library/ebayParser.php';
	include 'library/Crawler.php';
	include 'library/Transform.php';
	include 'vendor/autoload.php';

	$request = @$_GET['request'];
	$token   = @$_GET['token'] ? $_GET['token'] : $_POST['token'];
	$output  = array();

	//Check if it is hash
	if(strlen($token) == 32){
		$account = $db->select('accounts', 'token = "'.$token.'"', 'id');
		$account = $account[0]['id'];
	}

	if($request == 'account_create'){
		$token = sha1(time());
		$array = array(
			'token'     => $token,
			'purchase'  => @$_GET['purchase'],
			'email'     => @$_GET['email'],
			'package'   => @$_GET['package']
		);

		$result = $db->insert('accounts', $array);
		$output = array('token' => $token, 'result' => $result);
	}

	if($request == 'query' && $token){
		$product_name = @$_GET['product_name'];
		$product_meta = metaphone($product_name);

		$query = $db->select('queries', 'metaphone = "'.$product_meta.'"', 'result');

		if(@$query[0]){
			$output = json_decode($query[0]['result']);
		}else{
			$ebay_search  = new Search('site:https://www.ebay.com/p/ '.$product_name.'');
			$ebay_search  = $ebay_search->get();
			$ebay_search  = array_splice($ebay_search, 0, 10);

			$ebay_content = new Crawler($ebay_search);
			$ebay_content = $ebay_content->get();

			$ebay_results = new EbayParser($ebay_content);
			$ebay_results = $ebay_results->get();

			$groups = new Transform($ebay_results);
			$output = $groups->get();

			if($output){
				$search_array   = array(
					'content'    => json_encode($ebay_search),
					'result'     => json_encode($output),
					'metaphone'  => $product_meta,
					'keyword'    => $product_name,
					'account'    => $account
				);

				$search_result = $db->insert('queries', $search_array);
			}
		}
	}

	echo json_encode($output);