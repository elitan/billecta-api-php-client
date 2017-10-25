<?php

namespace Billecta;

class Billecta {

	var $creditor_public_id;
	var $http_client;

	function __construct($host, $secure_token, $format = 'json', $version = 1) {

		// add version to host var
		$host = $host . '/v' . $version . '/';

		// vars
		$this->creditor_public_id = NULL;

		// check accept format
		if ($format == 'json') {
			$accept_format = 'application/json';
		} else {
			// oh don't feel intimidated.
			throw new Exception('JSON is the only supported format. For good reasons.');
		}

		// default headers used in every request
		$default_headers = [
			'Accept' => $accept_format,
			'Authorization' => 'SecureToken ' . base64_encode($secure_token)
		];

		// create http client
		$this->http_client = new \GuzzleHttp\Client(
			[
				'base_uri' => $host,
				'headers' => $default_headers,
				'http_errors' => false
			]
		);
	}

	/**
	 * Functions for
	 * private
	 */

	public function getGUID() {

		if (function_exists('com_create_guid') === true) {
			return trim(com_create_guid(), '{}');
		}

		return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

	// generate current date in the format Billecta likes it
	private function getCurrentDate() {
		return date('Y-m-d H:i:sO');
	}

	// convert to string and create php object from the json response
	private function returnResponseBody($response) {
		return json_decode((string)$response->getBody());
	}

	/**
	 * Functions for
	 * MiSC
	 */

	// set what company you are working with
	public function setCreditorPublicId($creditor_public_id) {
		$this->creditor_public_id = $creditor_public_id;
	}

	/**
	 * Functions for
	 * CREDITORS aka COMPANIES
	 */

	public function getCreditors() {

		$url = 'creditors/creditors';

		$response = $this->http_client->get($url);

		return $this->returnResponseBody($response);
	}

	/**
	 * Functions for
	 * DEBTORS aka CUSTOMERS
	 */

	public function createDebtor($debtor) {

		if (!array_key_exists('CreditorPublicId', $debtor)) {
			$debtor['CreditorPublicId'] = $this->creditor_public_id;
		}

		// do not create two debtors with the same external_id
		if (array_key_exists('DebtorExternalId', $debtor)) {
			$external_id = $debtor['DebtorExternalId'];
			$debtor_tmp = $this->getDebtorByExternalId($external_id);
			if ($debtor_tmp) {
				throw new Exception('Debtor with DebtorExternalId: ' . $external_id  . ' already exists', 1);
			}
		}

		// encode debtor to json
		$body = json_encode($debtor);

		$url = 'debtors/debtor/';

		$response = $this->http_client->post($url, ['body' => $body]);

		return $this->returnResponseBody($response);
	}

	public function createDebtors($debtors) {
		foreach ($debtors as $debtor) {
			$this->createDebtor($debtor);
		}
	}

	public function getDebtor($debtor_public_id) {

		$url = 'debtors/debtor/' . $debtor_public_id;

		$response = $this->http_client->get($url);

		return $this->returnResponseBody($response);
	}

	public function getAllDebtors() {

		$url = 'debtors/debtors/' . $this->creditor_public_id;

		$response = $this->http_client->get($url);

		return $this->returnResponseBody($response);
	}

	public function deleteDebtor($debtor_public_id) {

		$url = 'debtors/debtor/' . $debtor_public_id;

		$response = $this->http_client->delete($url);
	}

	public function updateDebtor($debtor) {

		$url = 'debtors/debtor/';

		$body = json_encode($debtor);

		$response = $this->http_client->put($url, ['body' => $body]);

		return $this->returnResponseBody($response);
	}

	public function getDebtorByExternalId($external_id) {

		$query = array(
			'externalid' => $external_id
		);

		$url = 'debtors/debtorbyexternalid/' . $this->creditor_public_id;

		$response = $this->http_client->get($url, [
			'query' => $query,
			'http_error' => false
		]);

		// if multiple debtors with same DebtorExternalId
		if ($response->getStatusCode() == 400) {
			return NULL;
		}

		return $this->returnResponseBody($response);
	}

	public function getDebtorEvents($debtor_public_id) {

		$url = 'debtors/debtorevents/' . $debtor_public_id;

		$response = $this->http_client->get($url);

		return $this->returnResponseBody($response);
	}

	/**
	 * Functions for
	 * PRODUCTS
	 */

	public function createProduct($product) {

		if (!array_key_exists('CreditorPublicId', $product)) {
			$product['CreditorPublicId'] = $this->creditor_public_id;
		}

		$url = 'products/product/';

		// encode debtor to json and create
		$body = json_encode($product);

		$response = $this->http_client->post($url, ['body' => $body]);

		return $this->returnResponseBody($response);
	}

	public function createProducts($products) {
		foreach ($products as $product) {
			$this->createProduct($product);
		}
	}

	public function deleteProduct($product_public_id) {

		$url = 'products/product/' . $product_public_id;

		$response = $this->http_client->delete($url);
	}

	public function getProduct($product_public_id) {

		$query = array(
			'productid' => $product_public_id
		);

		$url = 'products/product/' . $this->creditor_public_id;

		$response = $this->http_client->get($url, [
			'query' => $query,
		]);

		return $this->returnResponseBody($response);
	}

	public function getAllProducts() {

		$url = 'products/products/' . $this->creditor_public_id;

		$response = $this->http_client->get($url);

		return $this->returnResponseBody($response);

	}

	public function getProductByExternalId($external_id) {

		$query = array(
			'externalid' => $external_id
		);

		$url = 'products/productbyexternalid/' . $this->creditor_public_id;

		$response = $this->http_client->get($url, [
			'query' => $query,
			'http_error' => false
		]);

		// if multiple debtors with same DebtorExternalId
		if ($response->getStatusCode() == 400) {
			return NULL;
		}

		return $this->returnResponseBody($response);

	}

	public function updateProduct($product) {

		if (!array_key_exists('CreditorPublicId', $product)) {
			$product['CreditorPublicId'] = $this->creditor_public_id;
		}

		$url = 'products/product/';

		$body = json_encode($product);

		$response = $this->http_client->put($url, ['body' => $body]);

		return $this->returnResponseBody($response);
	}


	/**
	 * Functions for
	 * invoices
	 */

	public function getInvoicesByDebtor($debtor_public_id) {

		$url = 'invoice/openbydebtor/' . $debtor_public_id;

		$response = $this->http_client->get($url);

		return $this->returnResponseBody($response);

	}


}

class Exception extends \Exception {}
