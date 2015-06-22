<?php

use CyberduckAndy\Blacklist\Blacklist;

class BlacklistTest extends PHPUnit_Framework_TestCase {

	public function testCore()
	{
		$blacklist = new Blacklist();
		$this->assertTrue(is_array($blacklist->user()));
	}
}