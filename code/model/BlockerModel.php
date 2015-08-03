<?php

/**
 * BlockerModel
 * This class sets up our database fields and object so we can easily add troublesome
 * IP addresses, host, and referers through our admin CMS. One of the big features of
 * this module is the ability to exclude a range of IP addresses. Having this 
 * functionality can save a huge amount of time and effort entering IP addresses
 * in the same range manually. You can exclude entire countries from your site if
 * need be. When we check a particular record we got through the process of checking
 * the IP first, then IP range, then host, then referer. If there is a match to anything 
 * blocked we instantly return a 503 forbidden header along with a blank page. The module
 * is targeted toward stopping spam bots crawling your website content and inflating page
 * impressions in things like Google Analytics. This module isn't meant to be a full substitute
 * for .htaccess blocking as troublesome users will actually make it into your app. 
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class BlockerModel extends DataObject {

	/**
	 * @static array $db databse columns which contain blocked traffic
	 **/
	private static $db = array(
		'description' => 'Varchar(255)',
		'ip' 	      => 'Varchar(255)',
		'ipMin'	      => 'Varchar(255)',
		'ipMax'       => 'Varchar(255)',
		'host' 	      => 'Varchar(255)',
		'referer'     => 'Varchar(255)',
		);

	public static $summary_fields = array(
		'description' => 'Description',
		'ip' 	      => 'IP',
		'host'		  => 'Host',
		'referer'     => 'Referer'
  	);

	/**
	 * @static string $singular_name singular admin name
	 **/
	private static $singular_name = 'Blocked';

	/**
	 * create the CMS fields where we can enter any blocked IPs etc
	 * @return void
	 **/
	public function getCMSFields()
	{
		return new FieldList(
			new TextField('description'),
			new TextField('ip', 'IP'),
			new TextField('ipMin', 'Min IP range'),
			new TextField('ipMax', 'Max IP range'),
			new TextField('host'),
			new TextField('referer')
			);
	}
}

class BlockAdmin extends ModelAdmin {

	/**
	 * @static array $managed_models this class manages our BlockerModel
	 **/
	private static $managed_models = array('BlockerModel');

	/**
	 * @static string $url_segment the CMS URL segment
	 **/
	private static $url_segment = 'blocked';

	/**
	 * @static string $menu_title the CMS menu link text
	 **/
	private static $menu_title = 'Blocked Users';

	/**
	 * @static string $menu_icon the CMS menu icon
	 **/
	private static $menu_icon = 'blacklist/images/menu-icons/16x16/block.png';

	/**
	 * @static string $page_length the number of records to show per page in the CMS
	 **/
	private static $page_length = 100;

	/**
	 * @static string $menu_priority we set this very high so this will be the last CMS menu item
	 **/
	private static $menu_priority = -100;
}