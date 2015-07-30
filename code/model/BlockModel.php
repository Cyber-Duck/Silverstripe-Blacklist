<?php

/**
 * BlockModel
 *
 * @package silverstripe-blacklist
 * @license BSD License http://www.silverstripe.org/bsd-license
 * @author <andrewm@cyber-duck.co.uk>
 **/
class BlockModel extends DataObject {

	private static $db = array(
		'description' => 'Varchar(255)',
		'ip' 	      => 'Varchar(255)',
		'ipMin'	      => 'Varchar(255)',
		'ipMax'       => 'Varchar(255)',
		'host' 	      => 'Varchar(255)',
		'referer'     => 'Varchar(255)',
		);

	private static $singular_name = 'Blocked';

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

	private static $managed_models = array('BlockModel');

	private static $url_segment = 'blocked';

	private static $menu_title = 'Blocked Users';

	private static $menu_icon = 'blacklist/images/menu-icons/16x16/block.png';

	private static $page_length = 100;

	private static $menu_priority = -100;
}