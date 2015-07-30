<?php
/* 
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
 *
 * Blacklist
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class Blacklist {

	private $saveBlocked = false;

	private $saveBots = true;

	private $userIP;

	private $userReferer;

	private $userHost;

	private $serverIPs = array(
		'HTTP_CLIENT_IP',
		'HTTP_X_FORWARDED_FOR',
		'HTTP_X_FORWARDED',
		'HTTP_X_CLUSTER_CLIENT_IP',
		'HTTP_FORWARDED_FOR',
		'HTTP_FORWARDED',
		'REMOTE_ADDR'
		);

	private $block;

	private $traffic;

	function __construct()
	{
		$this->getUserIP();
		$this->getUserReferer();
		$this->getUserHost();
	}

	public function test()
	{
		return true;
	}

	public function saveBlocked($save = false)
	{
		$this->saveBlocked = $save;
	}

	public function saveBots($save = true)
	{
		$this->saveBots = $save;
	}

	public function run()
	{
		$this->saveTraffic();
		$this->blockUser();

		$this->checkBlocked();
		$this->saveTraffic();
	}

	private function getUserIP()
	{
		foreach($this->serverIPs as $key) :
			if (array_key_exists($key, $_SERVER) === true) :
				foreach (explode(',', $_SERVER[$key]) as $ip) :
					$ip = trim($ip);

					if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) :
						$this->userIP = $ip;
						return;
					endif;
				endforeach;
			endif;
		endforeach;
	}

	private function getUserReferer()
	{
		if(isset($_SERVER['HTTP_REFERER'])) :
			$this->userReferer = $_SERVER['HTTP_REFERER'];
		endif;
	}

	private function getUserHost()
	{
		if(isset($this->userIP)) :
			$this->userHost = gethostbyaddr($this->userIP);
		endif;
	}

	private function checkBlocked()
	{
		$this->block = new Block();
	}

	private function saveTraffic()
	{
		$this->traffic = new Traffic();
	}
}