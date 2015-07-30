<?php

/**
 * Traffic
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class Traffic {

	private $ip;

	private $host;

	private $referer;

	private $saveBots = true;

	function __construct($ip, $host, $referer, $saveBots = true)
	{
		$this->ip = $ip;
		$this->host = $host;
		$this->referer = $referer;
		$this->saveBots = $saveBots;
	}

	public function save() 
	{
		$type = $this->getTrafficType();

		if($type == 'bot' && $this->saveBots === false) :
			return false;
		endif;

		$traffic = TrafficModel::create();

		$traffic->datetime = SS_Datetime::now();
		$traffic->type     = $type;
		$traffic->ip       = $this->ip;
		$traffic->host     = $this->host;
		$traffic->referer  = $this->referer;

		$traffic->write();
	}

	private function getTrafficType()
	{
		$bots = require_once(BASE_PATH.'/'.BLACKLIST_PATH.'/clients/bots.php');

		foreach($bots as $bot) :
			if(strpos($bot, $this->host) !== false) :
				return 'bot';
			endif;
		endforeach;
		return 'human';
	}
}