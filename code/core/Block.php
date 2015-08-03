<?php

/**
 * Block
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class Block {

	/**
	 * @var string $ip the current user IP address
	 **/
	private $userIP;

	/**
	 * @var string $referer the current user referer
	 **/
	private $userReferer;

	/**
	 * @var string $host the current user host
	 **/
	private $userHost;

	/**
	 * Our constructor is paased the user information and assigns it to class properties
	 *
	 * @param  string $ip       the current user IP
	 * @param  string $host     the current user host
	 * @param  string $referer  the current user referer
	 * 
	 * @return void
	 **/
	function __construct($ip, $host, $referer)
	{
		$this->userIP = $ip;
		$this->userHost = $host;
		$this->userReferer = $referer;

		$this->getBlockedData();
	}

	/**
	 * @return void
	 **/
	private function getBlockedData()
	{
		$blocked = BlockModel::get();

		foreach($blocked as $data) :
			$this->checkIP(
				$data->ip,
				$data->ipMin,
				$data->ipMax
				);

			$this->checkHost($data->host);

			$this->checkReferer($data->referer);
		endforeach;
	}

	private function checkIP($ip, $ipMin, $ipMax)
	{
		// check IP address
		if($ip != '') :
			if($this->userIP == $ip) :
				$this->forbidden();
			endif;
		endif;

		// check IP range
		if($ipMin != '' && $ipMax != '') :
		
			$ip  = ip2long($this->userIP);
			$min = ip2long($ipMin);
			$max = ip2long($ipMax);
			
			if($ip >= $min && $ip <= $max) :
				$this->forbidden();
			endif;
		endif;
	}
	
	private function checkHost($host)
	{
		if(strpos($this->userHost, $host) !== false) :
			$this->forbidden();
		endif;
	}
	
	private function checkReferer($referer)
	{
		if(strpos($this->userReferer, $referer) !== false) :
			$this->forbidden();
		endif;
	}
	
	private function forbidden()
	{
		header('HTTP/1.0 403 Forbidden');
		die;
	}
}