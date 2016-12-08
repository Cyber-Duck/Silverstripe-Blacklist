<?php
/**
 * Blacklist
 *
 * @package silverstripe-blacklist
 * @license MIT License https://github.com/Cyber-Duck/Silverstripe-Blacklist/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class BlackList
{
    /**
     * 
     *
     * @since version 1.0.0
     *
     * @var array $bots
     **/
	private $bots = [];

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @var string $type
     **/
	private $type = 'human';

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @var string $ip
     **/
	private $ip;

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @var string $host
     **/
	private $host;

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @var string $referer
     **/
	private $referer;

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @var bool $logUsers
     **/
	private $logUsers = true;

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @var bool $logBots
     **/
	private $logBots = true;

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @var array $ipHeaders
     **/
	private $ipHeaders = [
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR'
	];

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	public function __construct()
	{
		$this->bots = Config::inst()->get('Blacklist', 'bots');

		$this->setUserType();
		$this->setUserIP();
		$this->setUserHost();
		$this->setUserReferer();
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @param bool $enabled
     *
     * @return void
     **/
	public function logUsers($enabled = true)
	{
		$this->logUsers = $enabled;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @param bool $enabled
     *
     * @return void
     **/
	public function logBots($enabled = true)
	{
		$this->logBots = $enabled;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @param string $type
     *
     * @return void
     **/
	public function setType($type)
	{
		$this->type = $type;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @param string $ip
     *
     * @return void
     **/
	public function setIP($ip)
	{
		$this->ip = $ip;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @param string $host
     *
     * @return void
     **/
	public function setHost($host)
	{
		$this->host = $host;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @param string $referer
     *
     * @return void
     **/
	public function setReferer($referer)
	{
		$this->referer = $referer;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return string
     **/
	public function getType()
	{
		return $this->type;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return string
     **/
	public function getIP()
	{
		return $this->ip;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return string
     **/
	public function getHost()
	{
		return $this->host;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return string
     **/
	public function getReferer()
	{
		return $this->referer;
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
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

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	public function doBlock()
	{
		foreach(BlockedUser::get() as $blocked) {
			if($blocked->Ip) {
				if(trim($this->ip) == trim($blocked->Ip)) $this->forbidden();
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

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	private function setUserType()
	{
		foreach($this->bots as $bot) {
			if(strpos($this->host, $bot) !== false) {
				return $this->setType('bot');
			}
		}
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	private function setUserIP()
	{
		foreach($this->ipHeaders as $header) {
			if(array_key_exists($header, $_SERVER)) {
				foreach (explode(',', $_SERVER[$header]) as $ip) {
					$ip = trim($ip);

					if(filter_var($ip, FILTER_VALIDATE_IP)) {
						return $this->setIP($ip);
					}
				}
			}
		}
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	private function setUserHost()
	{
		if(isset($this->ip)) $this->setHost(gethostbyaddr($this->ip));
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	private function setUserReferer()
	{
		if(isset($_SERVER['HTTP_REFERER'])) $this->setReferer($_SERVER['HTTP_REFERER']);
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	private function writeLog()
	{
		$logger = LoggedUser::create();

		$logger->Type = $this->getType();
		$logger->Ip = $this->getIP();
		$logger->Host = $this->getHost();
		$logger->Referer = $this->getReferer();
		$logger->Url = Controller::curr()->getRequest()->getUrl();

		$logger->write();
	}

    /**
     * 
     *
     * @since version 1.0.0
     *
     * @return void
     **/
	private function forbidden()
	{
		return Controller::curr()->httpError(403, 'Blacklisted');
	}
}