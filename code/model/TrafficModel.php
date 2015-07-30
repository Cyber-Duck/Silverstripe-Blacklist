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
		'datetime' => 'SS_Datetime',
		'type' 	   => 'Varchar(255)',
		'ip' 	   => 'Varchar(255)',
		'host' 	   => 'Varchar(255)',
		'referer'  => 'Varchar(255)',
		'url'      => 'Varchar(255)'
		);

	public static $summary_fields = array(
		'datetime' => 'Logged',
		'type' 	   => 'User',
		'ip' 	   => 'IP',
		'host' 	   => 'Host',
		'referer'  => 'Referer'
		'url'      => 'URL'
  	);
}

class TrafficAdmin extends ModelAdmin {

	private static $managed_models = array('TrafficModel');

	private static $url_segment = 'traffic';

	private static $menu_title = 'Traffic';

	private static $menu_icon = 'blacklist/images/menu-icons/16x16/traffic.png';

	private static $page_length = 100;

	private static $menu_priority = -99;

	private static $model_importers = array();

	public $showImportForm = false;
}

class TrafficAdminExtension extends Extension {

	function updateEditForm(&$form)
	{
		$c = $form->fields->dataFieldByName('TrafficModel');
		$config = $c->config;
		$config->removeComponent($config->getComponentByType('GridFieldAddNewButton'));
	}
}