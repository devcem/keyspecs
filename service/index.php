<?php

	header('Content-Type: application/json');

	include 'library/Search.php';
	include 'library/Cache.php';
	include 'library/cURL.php';
	include 'library/ebayParser.php';


	$output  = array();
	$request = @$_GET['request'];

	if($request == 'query'){
		$product_name = @$_GET['product_name'];

		$ebay_search  = new Search('site:ebay.com '.$product_name);
		$ebay_search  = $ebay_search->get();
		$ebay_search  = array_splice($ebay_search, 0, 3);

		$ebay_results = new EbayParser($ebay_search);
		$ebay_results = $ebay_results->get();

		$output = array_merge($ebay_results, $output);
	}

	echo json_encode($output);