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

	function __construct($ip, $host, $referer)
	{
		$this->ip = $ip;
		$this->host = $host;
		$this->referer = $referer;
	}

	public function save() 
	{
		$traffic = TrafficModel::create();

		$traffic->datetime = SS_Datetime::now();
		$traffic->type     = $this->getTrafficType();
		$traffic->ip       = $this->ip;
		$traffic->host     = $this->host;
		$traffic->referer  = $this->referer;

		$traffic->write();
	}

	private function getTrafficType()
	{
		$bots = require_once(BLACKLIST_PATH.'clients/bots.php');

		foreach($bots as $bot) :
			if(strpos($bot, $this->host) !== false) :
				return 'bot';
			endif;
		endforeach;
		return 'human';
	}
}