<?php
/**
 * BlacklistBlocker
 * This class controls our blocking functionality. We check all stored blocked
 * IP, host, and referer information against our current user and block them
 * if necessary.
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class BlacklistBlocker {

	/**
	 * @var string $userIP The current user IP
	 **/
	private $userIP;

	/**
	 * @var string $userHost The current user host
	 **/
	private $userHost;

	/**
	 * @var string $userReferer The current user referer
	 **/
	private $userReferer;

	/**
	 * Our constructor assigns user information to class properties
	 *
	 * @param string $ip      The current user IP
	 * @param string $host    The current user host
	 * @param string $referer The current user referer
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
	 * Retrieve blocked user data from our database
	 *
	 * @return void
	 **/
	private function getBlockedData()
	{
		$blocked = BlockerModel::get();

		// lopped through our blocked data
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

	/**
	 * Check our injected blocked IP address and IP range
	 *
	 * @param string $ip    A blocked IP address
	 * @param string $ipMin A blocked IP address range start
	 * @param string $ipMax A blocked IP address range end
	 *
	 * @return void
	 **/
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
	
	/**
	 * Check the injected host value and block the user if necessary
	 *
	 * @param string $host The blocked host value to check
	 *
	 * @return void
	 **/
	private function checkHost($host)
	{
		if(strpos($this->userHost, $host) !== false) :
			$this->forbidden();
		endif;
	}
	
	/**
	 * Check the injected referer value and block the user if necessary
	 *
	 * @param string $referer The blocked referer value to check
	 *
	 * @return void
	 **/
	private function checkReferer($referer)
	{
		if(strpos($this->userReferer, $referer) !== false) :
			$this->forbidden();
		endif;
	}
	
	/**
	 * Returns a 403 forbidden header and kills our application
	 *
	 * @return die
	 **/
	private function forbidden()
	{
		header('HTTP/1.0 403 Forbidden');
		die;
	}
}