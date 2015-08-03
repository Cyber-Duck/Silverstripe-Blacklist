<?php

/**
 * LoggerModel
 * This sets up our database fields to store inforation about our app traffic
 * Just like other data objects; the information in accessable in our admin CMS
 * Unlike most data objects, data is not inserted into this database table through
 * the admin sytem and instead everything is inserted through our blacklist core
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class LoggerModel extends DataObject {

	/**
	 * @static array $db the traffic database fields
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
	 * @static array $summary_fields the fields to show in our admin CMS grid
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
	 * @static array $managed_models this class manages our LoggerModel
	 **/
	private static $managed_models = array('LoggerModel');

	/**
	 * @static string $url_segment the CMS URL segment
	 **/
	private static $url_segment = 'traffic';

	/**
	 * @static string $menu_title the CMS menu link text
	 **/
	private static $menu_title = 'Traffic';

	/**
	 * @static string $menu_icon the CMS menu icon
	 **/
	private static $menu_icon = 'blacklist/images/menu-icons/16x16/traffic.png';

	/**
	 * @static string $page_length the number of records to show per page in the CMS
	 **/
	private static $page_length = 100;

	/**
	 * @static string $menu_priority we set this very high so this will be the last CMS menu item
	 **/
	private static $menu_priority = -99;

	private static $model_importers = array();

	public $showImportForm = false;
}

class TrafficAdminExtension extends Extension {

	function updateEditForm(&$form)
	{
		$c = $form->fields->dataFieldByName('LoggerModel');
		$config = $c->config;
		$config->removeComponent($config->getComponentByType('GridFieldAddNewButton'));
	}
}