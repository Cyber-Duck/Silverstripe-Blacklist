<?php

/**
 * BlacklistLogger
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class BlacklistLogger {

	/**
	 * @var string $ip the current user IP address
	 **/
	private $userIP;

	/**
	 * @var string $host the current user host
	 **/
	private $userHost;

	/**
	 * @var string $referer the current user referer
	 **/
	private $userReferer;

	/**
	 * @var boolean $saveBots Set whether to log bot traffic to database
	 **/
	private $saveBots = true;

	/**
	 * Our constructor is paased the user information and assigns it to class properties
	 *
	 * @param  string $ip       the current user IP
	 * @param  string $host     the current user host
	 * @param  string $referer  the current user referer
	 * @param boolean $saveBots save bot traffic or not
	 * 
	 * @return void
	 **/
	function __construct($ip, $host, $referer, $saveBots = true)
	{
		$this->userIP = $ip;
		$this->userHost = $host;
		$this->userReferer = $referer;
		$this->saveBots = $saveBots;
	}

	/**
	 * The method to save traffic to our database
	 *
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

		// if our user is a bot and saving bot traffic is disabled we bail
		if($type == 'bot' && $this->saveBots === false) :
			return false;
		endif;

		// save our user information to the database
		$traffic = LoggerModel::create();

		$traffic->datetime = SS_Datetime::now();
		$traffic->type     = $type;
		$traffic->ip       = $this->userIP;
		$traffic->host     = $this->userHost;
		$traffic->referer  = $this->userReferer;

		$traffic->write();
	}

	/**
	 * Checks the user host against a list of known bots and returns a string
	 * containing the user type for insertion into the database
	 *
	 * @return string our user type
	 **/
	private function getTrafficType()
	{
		// require the list of bot hosts
		$bots = require_once(BASE_PATH.'/'.BLACKLIST_PATH.'/clients/bots.php');

		// loop and check
		foreach($bots as $bot) :
			if(strpos($bot, $this->userHost) !== false) :
				return 'bot';
			endif;
		endforeach;
		return 'human';
	}
}