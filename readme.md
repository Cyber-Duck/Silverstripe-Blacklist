# Blacklist
A SilverStripe module for logging traffic and blocking users. Takes advantage of SilverStripe admin features for easy data management and filtering.

- Logs the user type, IP address, host, referer, as well as the URL requested and logged time.
- Blocks users based on IP address, host, or referer.

## Installation
Add the following to your composer.json file

    {  
        "require": {  
            "Andrew-Mc-Cormack/Silverstripe-Blacklist": "master"  
        },  
        "repositories": [  
            {  
                "type": "vcs",  
                "url": "https://github.com/Andrew-Mc-Cormack/Silverstripe-Blacklist"  
            }  
        ]  
    }

Run composer install and composer update to download the module  

Create a new blacklist instance. 
If you wish to run Blacklist app-wide then you can call it in your Page_Controller, or if you want it page specific then you can call it in the specific Page controller(s). You should probably run it app wide by default.

    $blacklist = new Blacklist();
    $blacklist->run();

If you need to set any extra configuration options, do so before you call the run method

    $blacklist = new Blacklist()
    $blacklist->logBots(false);
    $blacklist->run();

Append the following to your app URL  
**/dev/build?flush=all**  
This rebuilds your database and clears your cache. When you run this initially the database columns for storing data are created.

Thats pretty much it!  

If you visit your site admin you should see two new entries in your CMS admin menu  

- Traffic (all logged users)
- Blocked Users