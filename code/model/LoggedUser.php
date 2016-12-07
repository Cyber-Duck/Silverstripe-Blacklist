<?php
/**
 * LoggedUser
 *
 * @package silverstripe-blacklist
 * @license MIT License https://github.com/Cyber-Duck/Silverstripe-Blacklist/blob/master/LICENSE
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

		$fields->addFieldsToTab('Root.Main', [
			HeaderField::create('User Data'),
			TextField::create('Ip', 'IP Address'),
			TextField::create('Host', 'Hostname'),
			TextField::create('Referer', 'Referer URL'),
			TextField::create('Url', 'URL')
		]);

		return $fields;
	}
}