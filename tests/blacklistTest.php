<?php

class BlacklistTest extends PHPUnit_Framework_TestCase {

	public function testCore()
	{
		$blacklist = new Blacklist();
		$this->assertTrue($blacklist->test());
	}
}