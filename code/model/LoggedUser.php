<?php
/**
 * LoggerModel
 *
 * This sets up our database fields to store information about our logged traffic
 * Just like other data objects; the information in accessable in our admin CMS.
 * Data is not inserted into this database table through the admin system CMS
 * but instead is inserted through our BlacklistLogger class.
 *
 * @package silverstripe-blacklist
 * @license MIT License https://github.com/Andrew-Mc-Cormack/Silverstripe-Blacklist/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class LoggedUser extends DataObject
{
    /**
     * @since version 1.0.0
     *
     * @config array $db Model database fields
     **/
	private static $db = [
		'Type' 	   => 'Varchar(255)',
		'Ip' 	   => 'Varchar(255)',
		'Host' 	   => 'Varchar(255)',
		'Referer'  => 'Varchar(255)',
		'Url'      => 'Varchar(255)'
	];

    /**
     * @since version 1.0.0
     *
     * @config array $summary_fields Fields in the Grid field
     **/
	public static $summary_fields = [
		'Created'  => 'Logged',
		'Type' 	   => 'User',
		'Ip' 	   => 'IP',
		'Host' 	   => 'Host',
		'Referer'  => 'Referer',
		'Url'      => 'URL'
  	];

    /**
     * @since version 1.0.0
     *
     * @config string $default_sort Sort tags by name by default
     **/
    private static $default_sort = 'Created DESC';

    /**
     * @since version 1.0.0
     *
     * @config string $singular_name Singular English name
     **/
    private static $singular_name = 'Logged User';

    /**
     * @since version 1.0.0
     *
     * @config string $plural_name Plural English name
     **/
    private static $plural_name = 'Logged Users';

	/**
	 * Create the CMS fields where we can enter any blocked data
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