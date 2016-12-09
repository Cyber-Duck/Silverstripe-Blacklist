<?php
/**
 * LoggedUser
 *
 * Model to hold logged user data
 *
 * @package silverstripe-blacklist
 * @license MIT License https://github.com/cyber-duck/silverstripe-blacklist/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class LoggedUser extends DataObject
{
    /**
     * Model database fields
     *
     * @since version 1.0.0
     *
     * @config array $db
     **/
	private static $db = [
		'Type' 	   => 'Varchar(256)',
		'Ip' 	   => 'Varchar(256)',
		'Host' 	   => 'Varchar(256)',
		'Referer'  => 'Varchar(256)',
		'Url'      => 'Varchar(256)'
	];

    /**
     * Fields in the Grid field
     *
     * @since version 1.0.0
     *
     * @config array $summary_fields
     **/
	private static $summary_fields = [
		'Created'  => 'Logged',
		'Type' 	   => 'User',
		'Ip' 	   => 'IP',
		'Host' 	   => 'Host',
		'Referer'  => 'Referer',
		'Url'      => 'URL'
  	];

    /**
     * Model CMS grid sorting
     *
     * @since version 1.0.0
     *
     * @config string $default_sort
     **/
    private static $default_sort = 'Created DESC';

    /**
     * Singular English title
     *
     * @since version 1.0.0
     *
     * @config string $singular_name 
     **/
    private static $singular_name = 'Logged User';

    /**
     * Plural English title
     *
     * @since version 1.0.0
     *
     * @config string $plural_name 
     **/
    private static $plural_name = 'Logged Users';

	/**
	 * Create the CMS fields where we can enter any blocked data
	 *
     * @since version 1.0.0
	 * 
	 * @return object
	 **/
	public function getCMSFields()
	{
		$fields = parent::getCMSFields();

		$fields->addFieldToTab('Root.Main', HeaderField::create('Logged User'));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('Created', 'Logged'));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('Type', 'User Type'));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('Ip', 'IP Address'));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('Host', 'Hostname'));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('Referer', 'Referer URL'));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('Url', 'URL'));

		return $fields;
	}
}