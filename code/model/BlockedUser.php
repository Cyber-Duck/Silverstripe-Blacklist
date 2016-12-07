<?php
/**
 * BlockedUser
 *
 * @package silverstripe-blacklist
 * @license MIT License https://github.com/Cyber-Duck/Silverstripe-Blacklist/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class BlockedUser extends DataObject
{
    /**
     * Model database fields
     *
     * @since version 1.0.0
     *
     * @config array $db
     **/
	private static $db = [
		'Description' => 'Varchar(256)',
		'Ip' 	      => 'Varchar(256)',
		'IpMin'	      => 'Varchar(256)',
		'IpMax'       => 'Varchar(256)',
		'Host' 	      => 'Varchar(256)',
		'Referer'     => 'Varchar(256)'
	];

    /**
     * Fields in the Grid field
     *
     * @since version 1.0.0
     *
     * @config array $summary_fields
     **/
	private static $summary_fields = [
		'Created'     => 'Logged',
		'Description' => 'Description',
		'Ip' 	      => 'IP',
		'Host'		  => 'Host',
		'Referer'     => 'Referer'
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
    private static $singular_name = 'Blocked User';

    /**
     * Plural English title
     *
     * @since version 1.0.0
     *
     * @config string $plural_name 
     **/
    private static $plural_name = 'Blocked Users';

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
			TextField::create('Description'),
			HeaderField::create('IP Data'),
			TextField::create('Ip', 'IP Address'),
			TextField::create('IpMin', 'Min IP range'),
			TextField::create('IpMax', 'Max IP range'),
			HeaderField::create('Host Data'),
			TextField::create('Host', 'Hostname'),
			HeaderField::create('Referer Data'),
			TextField::create('Referer', 'Referer URL')
		]);

		return $fields;
	}
}
