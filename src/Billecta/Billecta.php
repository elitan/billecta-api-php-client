<?php

namespace Billecta;

class Billecta {

	var $host;
	var $token;

	function __construct($host, $token) {
		$this->host = $host;
		$this->token = $token;
	}

	function setHost($host) {
		$this->host = $host;
	}

	function getHost() {
		return $this->host;
	}

}
