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

That's pretty much it!  

If you visit your site admin you should see two new entries in your CMS admin menu  

- Traffic (all logged users)
- Blocked Users

## About
I'm a Web Developer based in London and work for a digital agency specialising in user-experience called [Cyber Duck](https://www.cyber-duck.co.uk/)

## License

    Copyright (c) 2015, Andrew Mc Cormack <andrewm@cyber-duck.co.uk>.
    All rights reserved.

    Redistribution and use in source and binary forms, with or without
    modification, are permitted provided that the following conditions
    are met:

        * Redistributions of source code must retain the above copyright
          notice, this list of conditions and the following disclaimer.

        * Redistributions in binary form must reproduce the above copyright
          notice, this list of conditions and the following disclaimer in
          the documentation and/or other materials provided with the
          distribution.

    Neither the name of Andrew Mc Cormack nor the names of his
    contributors may be used to endorse or promote products derived
    from this software without specific prior written permission.

    THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
    "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
    LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
    FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
    COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
    INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
    BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
    LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
    CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
    LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
    ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
    POSSIBILITY OF SUCH DAMAGE.