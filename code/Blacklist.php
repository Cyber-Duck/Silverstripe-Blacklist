<?php
/**
 * Blacklist
 * The core of the Blacklist module is responsible for setting dependencies and 
 * settings and initialising the module functionality.
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class Blacklist {

	/**
	 * @var boolean $logTraffic Set whether to log traffic to database
	 **/
	private $logTraffic = true;

	/**
	 * @var boolean $logBlocked Set whether to log blocked users to database
	 **/
	private $logBlocked = false;

	/**
	 * @var boolean $logBots Set whether to log bots to database
	 **/
	private $logBots = true;

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
	 * Our constructor tries to attain our user IP, referer, and host info
	 *
	 * @return void
	 **/
	function __construct()
	{
		$this->getUserIP();
		$this->getUserHost();
		$this->getUserReferer();
	}
	
	/**
	 * A test method to verify our module installation
	 *
	 * @return boolean true
	 **/
	public function test()
	{
		return true;
	}
	
	/**
	 * Set whether to log traffic to the database
	 *
	 * @param boolean $save Set to true or false
	 *
	 * @return void
	 **/
	public function logTraffic($save = true)
	{
		$this->logTraffic = $save;
	}
	
	/**
	 * Set whether to log blocked user traffic to database
	 *
	 * @param boolean $save Set to true or false
	 *
	 * @return void
	 **/
	public function logBlocked($save = false)
	{
		$this->logBlocked = $save;
	}
	
	/**
	 * Set whether to log bot user traffic to database
	 *
	 * @param boolean $save Set to true or false
	 *
	 * @return void
	 **/
	public function logBots($save = true)
	{
		$this->logBots = $save;
	}
	
	/**
	 * Check the available $_SERVER variables to get the user IP address
	 *
	 * @return void
	 **/
	private function getUserIP()
	{
		// $_SERVER variables
		$serverIPs = array(
			'HTTP_CLIENT_IP',
			'HTTP_X_FORWARDED_FOR',
			'HTTP_X_FORWARDED',
			'HTTP_X_CLUSTER_CLIENT_IP',
			'HTTP_FORWARDED_FOR',
			'HTTP_FORWARDED',
			'REMOTE_ADDR'
		);

		foreach($serverIPs as $key) :
			if(array_key_exists($key, $_SERVER) === true) :
				foreach (explode(',', $_SERVER[$key]) as $ip) :
					$ip = trim($ip);

					// Validate user IP address
					if(filter_var($ip
						, FILTER_VALIDATE_IP
						, FILTER_FLAG_NO_PRIV_RANGE 
						| FILTER_FLAG_NO_RES_RANGE) !== false) :

						$this->userIP = $ip;
					endif;
				endforeach;
			endif;
		endforeach;
	}
	
	/**
	 * Get the user host information by IP address
	 *
	 * @return void
	 **/
	private function getUserHost()
	{
		if(isset($this->userIP)) :
			$this->userHost = gethostbyaddr($this->userIP);
		endif;
	}
	
	/**
	 * Get the user referer if the $_SERVER['HTTP_REFERER'] variable is set
	 *
	 * @return void
	 **/
	private function getUserReferer()
	{
		if(isset($_SERVER['HTTP_REFERER'])) :
			$this->userReferer = $_SERVER['HTTP_REFERER'];
		endif;
	}
	
	/**
	 * Run the blacklist core and log traffic or block the user if necessary
	 *
	 * @return void
	 **/
	public function run()
	{
		// Initiate a logging object and pass in our user info
		$logger = new BlacklistLogger(
			$this->userIP,
			$this->userHost,
			$this->userReferer,
			$this->logBots
		);

		// Log the traffic information if required
		if($this->logTraffic === true) :
			$logger->save();
		endif;

		// Block the user if required
		$blocker = new BlacklistBlocker(
			$this->userIP,
			$this->userHost,
			$this->userReferer
		);
	}
}