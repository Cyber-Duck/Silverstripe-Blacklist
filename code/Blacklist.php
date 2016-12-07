<?php

class BlackList
{
	private $bots = [];

	private $type = 'human';

	private $ip;

	private $host;

	private $referer;

	private $logUsers = true;

	private $logBots = true;

	private $ipHeaders = [
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR'
	];

	public function __construct()
	{
		$this->bots = Config::inst()->get('Blacklist', 'bots');

		$this->setUserType();
		$this->setUserIP();
		$this->setUserHost();
		$this->setUserReferer();
	}

	public function logUsers($enabled = true)
	{
		$this->logUsers = $enabled;
	}

	public function logBots($enabled = true)
	{
		$this->logBots = $enabled;
	}

	public function setType($type)
	{
		$this->type = $type;
	}

	public function setIP($ip)
	{
		$this->ip = $ip;
	}

	public function setHost($host)
	{
		$this->host = $host;
	}

	public function setReferer($referer)
	{
		$this->referer = $referer;
	}

	public function getType()
	{
		return $this->type;
	}

	public function getIP()
	{
		return $this->ip;
	}

	public function getHost()
	{
		return $this->host;
	}

	public function getReferer()
	{
		return $this->referer;
	}

	public function doLog()
	{
		if($this->logUsers === true) {
			if($this->type == 'human') {
				$this->writeLog();
			}
		}
		if($this->logBots === true) {
			if($this->type == 'bot') {
				$this->writeLog();
			}
		}
	}

	public function doBlock()
	{
		foreach(BlockedUser::get() as $blocked) {
			if($blocked->Ip) {
				if($this->ip == $blocked->Ip) $this->forbidden();
			}
			if($blocked->IpMin && $blocked->IpMax) {
				$ip  = ip2long($this->ip);
				$min = ip2long($blocked->ipMin);
				$max = ip2long($blocked->ipMax);
				
				if($ip >= $min && $ip <= $max) $this->forbidden();
			}
			if(strpos($this->host, $blocked->host) !== false) $this->forbidden();

			if(strpos($this->referer, $blocked->referer) !== false) $this->forbidden();
		}
	}

	private function setUserType()
	{
		foreach($this->bots as $bot) {
			if(strpos($bot, $this->host) !== false) {
				return $this->setType('bot');
			}
		}
	}

	private function setUserIP()
	{
		foreach($this->ipHeaders as $header) {
			if(array_key_exists($header, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$header]) as $ip) :
					$ip = trim($ip);

					if(filter_var($ip
						, FILTER_VALIDATE_IP
						, FILTER_FLAG_NO_PRIV_RANGE 
						| FILTER_FLAG_NO_RES_RANGE) !== false) {

						return $this->setIP($ip);
					}
				}
			}
		}
	}

	private function setUserHost()
	{
		if(isset($this->ip)) $this->setHost(gethostbyaddr($this->ip));
	}

	private function setUserReferer()
	{
		if(isset($_SERVER['HTTP_REFERER'])) $this->setReferer($_SERVER['HTTP_REFERER']);
	}

	private function writeLog()
	{
		$logger = LoggedUser::create();

		$logger->type = $this->getType();
		$logger->ip = $this->getIP();
		$logger->host = $this->getHost();
		$logger->referer = $this->getReferer();
		$logger->Url = Controller::curr()->getRequest()->getUrl();

		$logger->write();
	}
	
	private function forbidden()
	{
		header('HTTP/1.0 403 Forbidden');
		die;
	}
}