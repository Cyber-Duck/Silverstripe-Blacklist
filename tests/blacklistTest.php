<?php

use cyberduck\blacklist\core;

class blacklistTest extends PHPUnit_Framework_TestCase {

	public function testCore()
	{
		$blacklist = new core;
		$this->assertTrue($blacklist->init());
	}
}