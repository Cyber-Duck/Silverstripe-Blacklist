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
     * CMS models
     *
     * @since version 1.0.0
     *
     * @config array $managed_models
     **/
	private static $managed_models = ['LoggedUser', 'BlockedUser'];

    /**
     * CMS URL Segment
     *
     * @since version 1.0.0
     *
     * @config string $url_segment
     **/
	private static $url_segment = 'blacklist';

    /**
     * CMS nav title
     *
     * @since version 1.0.0
     *
     * @config string $menu_title
     **/
	private static $menu_title = 'Blacklist';

    /**
     * CMS nav icon
     *
     * @since version 1.0.0
     *
     * @config string $menu_icon
     **/
	private static $menu_icon = 'blacklist/images/menu-icons/16x16/block.png';

    /**
     * CMS grid length
     *
     * @since version 1.0.0
     *
     * @config int $page_length
     **/
	private static $page_length = 100;

    /**
     * CMS menu priority
     *
     * @since version 1.0.0
     *
     * @config int $menu_priority
     **/
	private static $menu_priority = -100;

    /**
     * Model importers
     *
     * @since version 1.0.0
     *
     * @config array $model_importers
     **/
	private static $model_importers = [];

    /**
     * Show import form
     *
     * @since version 1.0.0
     *
     * @config bool $showImportForm
     **/
	public $showImportForm = false;
}