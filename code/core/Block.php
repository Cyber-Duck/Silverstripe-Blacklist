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

		$this->checkIP()
		$this->checkHost()
		$this->checkReferer();
	}

	private function checkIP()
	{
		if(is_array($blocked)) :
		
			$ip  = ip2long($this->userIP);
			$min = ip2long($blocked[0]);
			$max = ip2long($blocked[1]);
			
			if($ip >= $min && $ip <= $max) :
				$this->forbidden();
			endif;
		else :
			if($this->userIP == $blocked) :
				$this->forbidden();
			endif;
		endif;
	}
	
	private function checkHost()
	{
		if(strpos($this->userHost, $blocked) !== false) :
			$this->forbidden();
		endif;
	}
	
	private function checkReferer()
	{
		if(strpos($this->userReferer, $blocked) !== false) :
			$this->forbidden();
		endif;
	}
	
	private function forbidden()
	{
		header('HTTP/1.0 403 Forbidden');
		die;
	}
}