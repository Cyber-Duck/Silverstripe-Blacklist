<?php

/**
 * Block
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class Block {

	private $ip;

	private $host;

	private $referer;

	private $blockedIPs;

	private $blockedHosts;

	private $blockedReferers;

	function __construct($ip, $host, $referer)
	{
		$this->ip = $ip;
		$this->host = $host;
		$this->referer = $referer;

		$this->getBlockedData();

		$this->checkIP()
		$this->checkHost()
		$this->checkReferer();
	}

	private function getBlockedData()
	{
		
	}

	private function checkIP()
	{
		foreach($this->ips as $blocked) :
			if(is_array($blocked)) :
			
				$ip  = ip2long($this->ip);
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
		endforeach;
	}
	
	private function checkHost()
	{
		foreach($this->hosts as $blocked) :
			if(strpos($this->userHost, $blocked) !== false) :
				$this->forbidden();
			endif;
		endforeach;
	}
	
	private function checkReferer()
	{
		foreach($this->referers as $blocked) :
			if(strpos($this->userReferer, $blocked) !== false) :
				$this->forbidden();
			endif;
		endforeach;
	}
	
	private function forbidden()
	{
		header('HTTP/1.0 403 Forbidden');
		die;
	}
}