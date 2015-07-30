<?php

/**
 * BlockingModel
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class TrafficModel extends DataObject {

	private static $db = array(
		'ip' 	  => 'Varchar(255)',
		'host' 	  => 'Varchar(255)',
		'referer' => 'Varchar(255)'
		);
}