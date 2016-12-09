<?php
/**
 * Blacklist
 *
 * Logs user details and blocks users based on IP, host, or referer.
 *
 * @package silverstripe-blacklist
 * @license MIT License https://github.com/cyber-duck/silverstripe-blacklist/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class BlackList
{
    /**
     * Array of bot names
     *
     * @since version 1.0.0
     *
     * @var array $bots
     **/
	private $bots = [];

    /**
     * Current user type
     *
     * @since version 1.0.0
     *
     * @var string $type
     **/
	private $type = 'human';

    /**
     * Current user IP
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
     * Current user referer
     *
     * @since version 1.0.0
     *
     * @var string $referer
     **/
	private $referer;

    /**
     * Log users
     *
     * @since version 1.0.0
     *
     * @var bool $logUsers
     **/
	private $logUsers = true;

    /**
     * Log bots
     *
     * @since version 1.0.0
     *
     * @var bool $logBots
     **/
	private $logBots = true;

    /**
     * Array of Server headers
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
     * Set up default configuration
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
     * Enable logging of users
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
     * Enable logging of bots
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
     * Set the current user type
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
     * Set the current user IP
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
     * Set the current user host
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
     * Set the current user referer
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
     * Get the current user type
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
     * Get the current user IP
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
     * Get the current user host
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
     * Get the current user referer
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
     * Perform the logging action
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
     * Perform the blocking action
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
     * Set the default user type
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
     * Set the default user IP
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
     * Set the default user host
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
     * Set the default user referer
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
     * Write the current user log
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
     * Forbidden user redirect
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