<?php

/**
 * Traffic
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class Traffic {

	/**
	 * @var string $ip the current user IP address
	 **/
	private $ip;

	/**
	 * @var string $host the current user host
	 **/
	private $host;

	/**
	 * @var string $referer the current user referer
	 **/
	private $referer;

	/**
	 * @var boolean $saveBots Set whether to log bot traffic to database
	 **/
	private $saveBots = true;

	/**
	 * Our constructor is paased the user information and assigns it to class properties
	 *
	 * @param string  $ip       the current user IP
	 * @param string  $host     the current user host
	 * @param string  $referer  the current user referer
	 * @param boolean $saveBots save bot traffic or not
	 * @return void
	 **/
	function __construct($ip, $host, $referer, $saveBots = true)
	{
		$this->ip = $ip;
		$this->host = $host;
		$this->referer = $referer;
		$this->saveBots = $saveBots;
	}

	/**
	 * The method to save traffic to our database
	 * @return void
	 **/
	public function save() 
	{
		// dont save logged in user traffic
		if(Member::currentUserID() != 0) :
			return false;
		endif;
		
		// check if our user is human or a bot
		$type = $this->getTrafficType();

		// if our user is a bot and saving bot traffic is disabled we return
		if($type == 'bot' && $this->saveBots === false) :
			return false;
		endif;

		// save our user inforamtion to the database
		$traffic = TrafficModel::create();

		$traffic->datetime = SS_Datetime::now();
		$traffic->type     = $type;
		$traffic->ip       = $this->ip;
		$traffic->host     = $this->host;
		$traffic->referer  = $this->referer;

		$traffic->write();
	}

	/**
	 * Checks the user host against a list of known bots and returns the user type
	 * @return string our user type
	 **/
	private function getTrafficType()
	{
		// require the list of bot hostnames
		$bots = require_once(BASE_PATH.'/'.BLACKLIST_PATH.'/clients/bots.php');

		foreach($bots as $bot) :
			if(strpos($bot, $this->host) !== false) :
				return 'bot';
			endif;
		endforeach;
		return 'human';
	}
}