<?php
/**
 * BlacklistLogger
 * Logs data about our user to the database including IP address, host, referer,
 * logged time, and page URL.
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class BlacklistLogger {

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
	 * @var boolean $logBots Set whether to log bots to database
	 **/
	private $logBots = true;

	/**
	 * Our constructor assigns user information to class properties
	 *
	 * @param string  $ip      The current user IP
	 * @param string  $host    The current user host
	 * @param string  $referer The current user referer
	 * @param boolean $logBots Set whether to log bot traffic
	 * 
	 * @return void
	 **/
	function __construct($ip, $host, $referer, $logBots = true)
	{
		$this->userIP = $ip;
		$this->userHost = $host;
		$this->userReferer = $referer;
		$this->logBots = $logBots;
	}

	/**
	 * Save logged traffic data to our database
	 *
	 * @return void
	 **/
	public function save() 
	{
		// Don't save logged in user traffic
		if(Member::currentUserID() != 0) :
			return false;
		endif;
		
		// Check if our user is human or a bot
		$type = $this->getTrafficType();

		// If our user is a bot and saving bot traffic is disabled we bail
		if($type == 'bot' && $this->logBots === false) :
			return false;
		endif;

		// Save our user information to the database
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
	 * @return string
	 **/
	private function getTrafficType()
	{
		// Require the list of crawler hosts
		$bots = require_once(BASE_PATH.'/'.BLACKLIST_PATH.'/clients/bots.php');

		// Loop and check
		foreach($bots as $bot) :
			if(strpos($bot, $this->userHost) !== false) :
				return 'bot';
			endif;
		endforeach;
		return 'human';
	}
}