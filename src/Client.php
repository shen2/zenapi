<?php
namespace ZenAPI;

class Client extends BaseClient {
	/**
	 *
	 * @param string $host
	 */
	public function __construct($host = '') {
		$this->host = $host;
	}
}
