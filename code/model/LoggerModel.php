<?php
/**
 * LoggerModel
 * This sets up our database fields to store information about our logged traffic
 * Just like other data objects; the information in accessable in our admin CMS.
 * Data is not inserted into this database table through the admin system CMS
 * but instead is inserted through our BlacklistLogger class.
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class LoggerModel extends DataObject {

	/**
	 * @static array $db The traffic database fields
	 **/
	private static $db = array(
		'datetime' => 'SS_Datetime',
		'type' 	   => 'Varchar(255)',
		'ip' 	   => 'Varchar(255)',
		'host' 	   => 'Varchar(255)',
		'referer'  => 'Varchar(255)',
		'url'      => 'Varchar(255)'
		);

	/**
	 * @static array $summary_fields The fields to show in our admin CMS grid
	 **/
	public static $summary_fields = array(
		'datetime' => 'Logged',
		'type' 	   => 'User',
		'ip' 	   => 'IP',
		'host' 	   => 'Host',
		'referer'  => 'Referer',
		'url'      => 'URL'
  	);
}

class TrafficAdmin extends ModelAdmin {

	/**
	 * @static array $managed_models This class manages our LoggerModel
	 **/
	private static $managed_models = array('LoggerModel');

	/**
	 * @static string $url_segment the CMS URL segment
	 **/
	private static $url_segment = 'traffic';

	/**
	 * @static string $menu_title The CMS menu link text
	 **/
	private static $menu_title = 'Traffic';

	/**
	 * @static string $menu_icon The CMS menu icon
	 **/
	private static $menu_icon = 'blacklist/images/menu-icons/16x16/traffic.png';

	/**
	 * @static string $page_length the number of records to show in the CMS grid
	 **/
	private static $page_length = 100;

	/**
	 * @static string $menu_priority Set high so this will be last CMS menu item
	 **/
	private static $menu_priority = -99;

	/**
	 * @static array $model_importers Model importing arrays
	 **/
	private static $model_importers = array();

	/**
	 * @static boolean $showImportForm Show import form
	 **/
	public $showImportForm = false;
}