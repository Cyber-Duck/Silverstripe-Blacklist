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
		'Created'     => 'Created',
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

		$fields->addFieldToTab('Root.Main', HeaderField::create('Blocked User'));
		$fields->addFieldToTab('Root.Main', ReadonlyField::create('Created'));
		$fields->addFieldToTab('Root.Main', TextareaField::create('Description'));

		$fields->addFieldToTab('Root.Main', HeaderField::create('IP Data')->setHeadingLevel(3));
		$fields->addFieldToTab('Root.Main', TextField::create('Ip', 'IP Address'));
		$fields->addFieldToTab('Root.Main', TextField::create('IpMin', 'Min IP range'));
		$fields->addFieldToTab('Root.Main', TextField::create('IpMax', 'Max IP range'));

		$fields->addFieldToTab('Root.Main', HeaderField::create('Host Data')->setHeadingLevel(3));
		$fields->addFieldToTab('Root.Main', TextField::create('Host', 'Hostname'));

		$fields->addFieldToTab('Root.Main', HeaderField::create('Referer Data')->setHeadingLevel(3));
		$fields->addFieldToTab('Root.Main', TextField::create('Referer', 'Referer URL'));

		return $fields;
	}
}
