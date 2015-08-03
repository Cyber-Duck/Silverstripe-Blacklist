<?php
/**
 * Blacklist
 * The core of the Blacklist module here does very little related to the logic 
 * of the module. Instead it is responsible for setting dependencies in our
 * module. Through this approach we allow the easy extension of the module 
 * itself and leave it open for easy integration of new features in future.
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class Blacklist {

	/**
	 * @var boolean $saveTraffic Set whether to log traffic to database
	 **/
	private $saveTraffic = true;

	/**
	 * @var boolean $saveBlocked Set whether to log blocked traffic to database
	 **/
	private $saveBlocked = false;

	/**
	 * @var boolean $saveBots Set whether to log bot traffic to database
	 **/
	private $saveBots = true;

	/**
	 * @var string $userIP The current user IP
	 **/
	private $userIP;

	/**
	 * @var string $userIP The current user referer
	 **/
	private $userReferer;

	/**
	 * @var string $userIP The current user host
	 **/
	private $userHost;

	/**
	 * @var array $serverIPs $_SERVER variables we can check to get the user IP
	 **/
	private $serverIPs = array(
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR'
		);

	/**
	 * Our constructor tries to attain our user IP, referer, and host
	 *
	 * @return void
	 **/
	function __construct()
	{
		$this->getUserIP();
		$this->getUserReferer();
		$this->getUserHost();
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
	 * @param boolean $save set true or false
	 *
	 * @return void
	 **/
	public function saveTraffic($save = true)
	{
		$this->saveTraffic = $save;
	}
	
	/**
	 * Set whether to log  blocked traffic to the database
	 *
	 * @param boolean $save set true or false
	 *
	 * @return void
	 **/
	public function saveBlocked($save = false)
	{
		$this->saveBlocked = $save;
	}
	
	/**
	 * Set whether to log bot traffic to the database
	 *
	 * @param boolean $save set true or false
	 *
	 * @return void
	 **/
	public function saveBots($save = true)
	{
		$this->saveBots = $save;
	}
	
	/**
	 * Check the available $_SERVER variables to get the user IP address
	 *
	 * @return void
	 **/
	private function getUserIP()
	{
		foreach($this->serverIPs as $key) :
			if(array_key_exists($key, $_SERVER) === true) :
				foreach (explode(',', $_SERVER[$key]) as $ip) :
					$ip = trim($ip);

					// validate we have have a proper IP address
					if(filter_var($ip, FILTER_VALIDATE_IP
						, FILTER_FLAG_NO_PRIV_RANGE 
						| FILTER_FLAG_NO_RES_RANGE) !== false) :

						// if we have a proper IP address we assign it
						$this->userIP = $ip;
					endif;
				endforeach;
			endif;
		endforeach;
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
	 * Get the user host information
	 * @return void
	 **/
	private function getUserHost()
	{
		if(isset($this->userIP)) :
			$this->userHost = gethostbyaddr($this->userIP);
		endif;
	}
	
	/**
	 * Run the blacklist core and log traffic or block the user if necessary
	 *
	 * @return void
	 **/
	public function run()
	{
		// initiate a traffic object for logging and pass in our user info
		$this->traffic = new Traffic(
			$this->userIP,
			$this->userHost,
			$this->userReferer,
			$this->saveBots
			);

		// save the traffic information if we need to
		if($this->saveTraffic === true) :
			$this->traffic->save();
		endif;

		// check for a blocked user
		$this->block = new Block(
			$this->userIP,
			$this->userHost,
			$this->userReferer
			);
	}
}