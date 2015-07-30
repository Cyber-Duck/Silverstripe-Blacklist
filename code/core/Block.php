<?php

/**
 * Block
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class Block {

	function __construct()
	{

	}

	private function checkIP()
	{
		foreach($this->ips as $blocked) :
			if(is_array($blocked)) :
			
				$ip  = ip2long($this->userIP);
				$min = ip2long($blocked[0]);
				$max = ip2long($blocked[1]);
				
				if($ip >= $min && $ip <= $max) :
					$this->banned = true;
				endif;
			else :
				if($this->userIP == $blocked) :
					$this->banned = true;
				endif;
			endif;
		endforeach;
	}
	
	private function checkHost()
	{
		foreach($this->hosts as $blocked) :
			if(strpos($this->userHost, $blocked) !== false) :
				$this->banned = true;
			endif;
		endforeach;
	}
	
	private function checkReferer()
	{
		foreach($this->referers as $blocked) :
			if(strpos($this->userReferer, $blocked) !== false) :
				$this->banned = true;
			endif;
		endforeach;
	}
	
	private function block()
	{
		if($this->banned === true) :
			header('HTTP/1.0 403 Forbidden');
			die;
		endif;
	}
}