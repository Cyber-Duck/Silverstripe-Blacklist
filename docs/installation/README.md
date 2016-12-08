# Installation

## Composer

Add the following to your composer.json file

```json
{  
    "require": {  
        "cyber-duck/silverstripe-blacklist": "1.0.*"
    }
}
```

Run composer and then visit /dev/build?flush=all to rebuild the database and flush the cache.

## Controller

If you wish to run Blacklist app-wide then you can call it in your Page_Controller, or if you want it page specific then you can call it in the specific Page controller(s) within the init method. 

### Log Users

Call the doLog method to log user details

```php
class Page_Controller extends ContentController
{
    public function init()
    {
    	parent::init();

        $blacklist = new Blacklist();
		$blacklist->doLog();
    }
}
```

### Block Users

Call the doBlock method to block users based on entries in the Blacklist CMS section

```php
class Page_Controller extends ContentController
{
    public function init()
    {
        parent::init();

        $blacklist = new Blacklist();
        $blacklist->doBlock();
    }
}
```