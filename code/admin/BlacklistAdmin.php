<?php
/**
 * Blacklist Admin
 *
 * @package silverstripe-blacklist
 * @license MIT License https://github.com/Cyber-Duck/Silverstripe-Blacklist/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class BlacklistAdmin extends ModelAdmin
{
	/**
	 * @static array $managed_models This class manages our BlockerModel
	 **/
	private static $managed_models = ['LoggedUser', 'BlockedUser'];

	/**
	 * @static string $url_segment the CMS URL segment
	 **/
	private static $url_segment = 'blacklist';

	/**
	 * @static string $menu_title The CMS menu link text
	 **/
	private static $menu_title = 'Blacklist';

	/**
	 * @static string $menu_icon The CMS menu icon
	 **/
	private static $menu_icon = 'blacklist/images/menu-icons/16x16/block.png';

	/**
	 * @static string $page_length the number of records to show in the CMS grid
	 **/
	private static $page_length = 100;

	/**
	 * @static string $menu_priority Set high so this will be last CMS menu item
	 **/
	private static $menu_priority = -100;

	/**
	 * @static array $model_importers Model importing arrays
	 **/
	private static $model_importers = [];

	/**
	 * @static boolean $showImportForm Show import form
	 **/
	public $showImportForm = false;
}