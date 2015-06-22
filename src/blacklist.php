<?php
/* PHPUnit
 *
 * Copyright (c) 2015, Andrew Mc Cormack <andrewm@cyber-duck.co.uk>.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of Andrew Mc Cormack nor the names of his
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 */

namespace cyberduckandy\blacklist;

class blacklist {
	
	private $banned = false;
	
	private $ips;
	
	private $hosts;
	
	private $referers;
	
	private $userIP;
	
	private $userHost;
	
	private $userReferer;
	
	function __construct($ips = array(), $hosts = array(), $referers = array())
	{
		$this->ips = $ips;
		$this->hosts = $hosts;
		$this->referers = $referers;
		
		$this->getClientDetails();
	}
	
	public function setIPs($ips = array())
	{
		$this->ips = array_merge($this->ips, $ips);
	}
	
	public function setHosts($hosts = array())
	{
		$this->hosts = array_merge($this->hosts, $hosts);
	}
	
	public function setReferers($referers = array())
	{
		$this->referers = array_merge($this->referers, $referers);
	}
	
	public function user()
	{
		return array(
			'ip' => $this->userIP,
			'host' => $this->userHost,
			'referer' => $this->userReferer
		);
	}
	
	private function getClientDetails()
	{
    	foreach(array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) :
        	if (array_key_exists($key, $_SERVER) === true) :
            	foreach (explode(',', $_SERVER[$key]) as $ip) :
                	$ip = trim($ip);

                	if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) :
                    	$this->userIP = $ip;
						$this->checkIP();
                	endif;
            	endforeach;
        	endif;
    	endforeach;
		
		$this->userHost = gethostbyaddr($this->userIP);
		if($this->userHost !== false) :
			$this->checkHost();
		endif;
		
		if(isset($_SERVER['HTTP_REFERER'])) :
			$this->userReferer = $_SERVER['HTTP_REFERER'];
			$this->checkReferer();
		endif;
		
		$this->block();
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