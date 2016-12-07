<?php
/**
 * BlockedUser
 *
 * This class sets up our database fields and object so we can easily add 
 * troublesome IP addresses, host, and referers through our admin CMS. One of 
 * the big features of this module is the ability to exclude a range of IP 
 * addresses. Having this functionality can save a huge amount of time and 
 * effort entering IP addresses in the same range manually. You can exclude
 * entire countries from your site if need be. When we check a particular record
 * we got through the process of checking the IP first, then IP range, then host, 
 * then referer. If there is a match to anything blocked we instantly return a 
 * 503 forbidden header along with a blank page. The module is targeted toward 
 * stopping spam bots crawling your website content and inflating page impressions 
 * in things like Google Analytics. This module isn't meant to be a full substitute
 * for .htaccess blocking as troublesome users will actually make it into your app. 
 *
 * @package silverstripe-blacklist
 * @license MIT License https://github.com/Cyber-Duck/Silverstripe-Blacklist/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class BlockedUser extends DataObject
{
    /**
     * @since version 1.0.0
     *
     * @config array $db Model database fields
     **/
	private static $db = [
		'Description' => 'Varchar(255)',
		'Ip' 	      => 'Varchar(255)',
		'IpMin'	      => 'Varchar(255)',
		'IpMax'       => 'Varchar(255)',
		'Host' 	      => 'Varchar(255)',
		'Referer'     => 'Varchar(255)',
	];

    /**
     * @since version 1.0.0
     *
     * @config array $summary_fields Fields in the Grid field
     **/
	public static $summary_fields = [
		'Created'     => 'Logged',
		'Description' => 'Description',
		'Ip' 	      => 'IP',
		'Host'		  => 'Host',
		'Referer'     => 'Referer'
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
    private static $singular_name = 'Blocked User';

    /**
     * @since version 1.0.0
     *
     * @config string $plural_name Plural English name
     **/
    private static $plural_name = 'Blocked Users';

	/**
	 * Create the CMS fields where we can enter any blocked data
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
