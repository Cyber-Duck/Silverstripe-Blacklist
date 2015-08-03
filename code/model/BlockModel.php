<?php

/**
 * BlockModel
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class BlockModel extends DataObject {

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
	 * @static array $managed_models this class manages our BlockModel
	 **/
	private static $managed_models = array('BlockModel');

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