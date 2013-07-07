=== AdSense Click-Fraud Monitoring ===
Contributors: ReneHermi
Donate link: http://www.clickfraud-monitoring.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: AdSense click fraud monitoring, AdSense, Ajax, Klickbetrug, notification, shortcode, Plugin, links, google, posts, links invalid klicks, click bomb, click bombing, clickbomb, admin, ads, advertisement, akismet, seo, click protection, click protect, clickfraud, click-fraud, PPC
Requires at least: 3.1+
Tested up to: 3.5.1
Stable tag: 1.2

Click-Fraud monitor for Google AdSense and other pay per click vendors. 
Minimize the risk to be banned and excluded from your AdSense account due to malicious third party clicks on advertisements on your website.


== Description ==

This plugin is in active development and will be updated on a regular basis - Please do not rate negative before i tried my best to solve your issue. Thanks buddy!

Official Website for docs, support:
http://www.clickfraud-monitoring.com

Get the premium version with email notification at:
http://codecanyon.net/item/adsense-clickfraud-monitor/4759515

= Why i need AdSense Click Fraud Monitoring? =

* Prevent malicious clicks by bots on your Ads
* Prevent malicious clicks by human user on you Ads
* Prevent clicks by friends on your Ads who want to “help” you.
* Prevent unintended clicks by your own
* New: Block your own IP
* New: Use custom name for your ad block class

In easy words, e.g. Google AdSense: Google takes care that every click on any AdSense advertising is done by a real human visitor. Automatic clicks by crawl bots or a lot of automated or manual processed clicks (a so-called Clickbomb) can lead to a complete and permanent exclusion from the google AdSense service. All your earnings are lost and it is very hard to get back the access to your account. (In most cases impossible)
The motivation behind such automatic clicks is very different. Maybe some competitior wants to harm you or a technical leads to multiple clicks by a human user. They all have in common that you as the AdSense account owner are responsible for any click fraud. You recognize unusual clicks when your page CTR is 1 - 3 % averaged and than it jumps up to 5, 10 or even more. 

This is were the "Click Fraud Monitor" comes in...for prevention!

= Features =

* Disable your Ads when a user clicks too often on them
* User will be banned and blocked for further clicks
* Manual unblocking of IP adresses possible
* Works, even without reloading of your site (jQuery and Ajax)
* Works with every Theme
* E-Mail notification when a user is blocked (Only Premium)
* Activating and disabling of all Ads with only one click
* Works in content, sidebar and widget section
* Simple installation and setup
* No garbage: Removes all plugin tables and settings in the WP database
* Service and support by the author
* Periodic updates and improvements. (Feel free to make your wish)

= How does it work? =

The Plugin counts all clicks on your Ads. When the clicks exceeds a specified number, the ad will be deactivated and removed from the source code. The clicking user or bot is blocked on a IP basis for further clicks. At the same time the plugin sends you a notification by email. (Only Premium)
 
= How to install and setup? =
Install this plugin and wrap your AdSense or other advertising code into a new div with the class "cfmonitor". 

For AdSense your code should look like: `<div class="cfmonitor">YOUR AdSense CODE HERE</div>`

From now on every click on your ads are counted by the IP and current user session. If the clicks reach a adjusted number of clicks within a specified timerange, the advertising is deactivated for that visitor and no further click fraud is possible. If that happens, you´ll get a email notofication and are able to see in the admin panel what´s going on by a list of blocked visitors.
If there should be a larger attack on your site with a lot of different IP´s you are able to hide all advertisings complete or only selected by country. That helps enourmous to react and investigate the case without loosing too much of your earnings.

Google is pretty good to recognize a few mistaken clicks but a larger attack should be reported at the official Google [Invalid Clicks Contact Form](https://support.google.com/AdSense/bin/request.py?hl=en&contact_type=invalid_clicks_contact&rd=2 "Invalid Clicks contact form")

= Why would Google ban me? =

In easy words: Google takes care that every click on any AdSense advertising is done by a real human visitor. Automatic clicks by crawl bots or a lot of automated or manual processed clicks (a so-called Clickbomb) can lead to a complete and permanent exclusion from the Google AdSense service. All your earnings are lost and it is very hard to get back the access to your account. (In most cases impossible) The motivation behind such automatic clicks is very different. Maybe some competitor wants to harm you or a technical leads to multiple clicks by a human user. They all have in common that you as the AdSense account owner are responsible for any click fraud. You recognize unusual clicks when your page CTR is 1 – 3 % averaged and than it jumps up to 5, 10 or even more.

== Installation ==
1. Download the plugin "Click Fraud Monitoring" , unzip and place it in your wp-content/plugins/ folder. You can alternatively upload and install it via the WordPress plugin backend.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Select Plugins->Click fraud monitor 

== Screenshots ==
1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
3. screenshot-4.png

== Changelog ==

= 1.2 =
* Some coding improvements

= 1.1 =
* New: Block own IP
* New: Use custom element classes. E.g. div='myad1'
* Fixed Google AdSense iframe issues

= 1.0 = 
* First revision

== Issues ==
Not known yet

== Frequently Asked Questions ==

= Is the use of the plugin against the terms of service (TOS) of Google AdSense, is the ad code modified? =
No, it´s not against the terms. The `<div>` is not changing the original AdSense source code. It´s only a container wrapped around it.

= How does the plugin count the clicks? =
The function counts every click on a container that own a userdefined class (default class is 'cfmonitor'). As that div is wraped around it´s able to count mouse events like clicks.

= Does it work on Wordpress MU (Multisites) =
Not tested yet. Please let me know if you have any issues or if you test it successfully

= Does it work with YOOST SEO =
Yes

= Does it work with 'Quick AdSense' WordPress Plugin =
Yes

= Does it work with installation on localhost? =
If you are testing on a localhost environment make sure that you are running your site on http://127.0.0.1/. If you are using http://localhost the plugin is not able to block
your access for testing purposes.

= Is there a 100% guarantee that this plugin prevents you from exlusion of your AdSense Account? =
Due to the possible technical eventualities like rotating IP adresses there is no 100% safety. But this plugin helps you are lot to minimize the risk to be banned because of any malicious clicks.
It´s developed on a regular basis to cover as many eventualities as possible.

= Does it work in sidebars and widgets? =
Yes


= Does it work with alternative ad venders or only with AdSense? =
It´s working with any pay per click ad vendor. It´s not focused on AdSense.

Get the complete documentation at: http://www.clickfraud-monitoring.com/ 

== Upgrade Notice ==

= 1.1 =
* New: Block own IP
* New: Use custom element classes. E.g. div='myad1'
* Fixed Google AdSense iframe issues

= 1.0 =
* First revision

== Official Site ==
* http://www.clickfraud-monitoring.com=======
=== AdSense Click-Fraud Monitoring ===
Contributors: ReneHermi
Donate link: http://www.clickfraud-monitoring.vom
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: Click Fraud Monitoring, AdSense click fraud monitoring, AdSense, Klickbetrug, invalid klicks, ungültige klicks, click bomb, click bombing, clickbomb, ad, ads, advertisement, click protection, click protect, clickfraud, click-fraud, PPC
Requires at least: 3.1+
Tested up to: 3.5.1
Stable tag: 1.2

Click-Fraud monitor for Google AdSense and other pay per click vendors. 
Minimize the risk to be banned and excluded from your AdSense account due to malicious third party clicks on advertisements on your website.


== Description ==

 ## This plugin is in active development and will be updated on a regular basis - Please do not rate negative before i tried my best to solve your issue. Thanks buddy! ##

Click fraud monitor for Google AdSense and every other pay per click advertising vendor. 
Minimize the risk to be banned and excluded from your AdSense account due to malicious third party clicks on advertisements on your website.

= Why i need AdSense Click Fraud Monitoring? =

* Prevent malicious clicks by bots on your Ads
* Prevent malicious clicks by human user on you Ads
* Prevent clicks by friends on your Ads who want to “help” you.
* Prevent unintended clicks by your own
* New: Block your own IP
* New: Use custom name for your ad block class

In easy words, e.g. Google AdSense: Google takes care that every click on any AdSense advertising is done by a real human visitor. Automatic clicks by crawl bots or a lot of automated or manual processed clicks (a so-called Clickbomb) can lead to a complete and permanent exclusion from the google AdSense service. All your earnings are lost and it is very hard to get back the access to your account. (In most cases impossible)
The motivation behind such automatic clicks is very different. Maybe some competitior wants to harm you or a technical leads to multiple clicks by a human user. They all have in common that you as the AdSense account owner are responsible for any click fraud. You recognize unusual clicks when your page CTR is 1 - 3 % averaged and than it jumps up to 5, 10 or even more. 

This is were the "Click Fraud Monitor" comes in...for prevention!

= Features =

* Disable your Ads when a user clicks too often on them
* User will be banned and blocked for further clicks
* Manual unblocking of IP adresses possible
* Works, even without reloading of your site (jQuery and Ajax)
* Works with every Theme
* E-Mail notification when a user is blocked (Only Premium)
* Activating and disabling of all Ads with only one click
* Works in content, sidebar and widget section
* Simple installation and setup
* No garbage: Removes all plugin tables and settings in the WP database
* Service and support by the author
* Periodic updates and improvements. (Feel free to make your wish)

= How does it work? =

The Plugin counts all clicks on your Ads. When the clicks exceeds a specified number, the ad will be deactivated and removed from the source code. The clicking user or bot is blocked on a IP basis for further clicks. At the same time the plugin sends you a notification by email. (Only Premium)
 
= How to install and setup? =
Install this plugin and wrap your AdSense or other advertising code into a new div with the class "cfmonitor". 

For AdSense your code should look like: `<div class="cfmonitor">YOUR AdSense CODE HERE</div>`

From now on every click on your ads are counted by the IP and current user session. If the clicks reach a adjusted number of clicks within a specified timerange, the advertising is deactivated for that visitor and no further click fraud is possible. If that happens, you´ll get a email notofication and are able to see in the admin panel what´s going on by a list of blocked visitors.
If there should be a larger attack on your site with a lot of different IP´s you are able to hide all advertisings complete or only selected by country. That helps enourmous to react and investigate the case without loosing too much of your earnings.

Google is pretty good to recognize a few mistaken clicks but a larger attack should be reported at the official Google [Invalid Clicks Contact Form](https://support.google.com/adsense/contact/invalid_clicks_contact?hl=en&rd=2 "Invalid Clicks contact form")

= Why would Google ban me? =

In easy words: Google takes care that every click on any AdSense advertising is done by a real human visitor. Automatic clicks by crawl bots or a lot of automated or manual processed clicks (a so-called Clickbomb) can lead to a complete and permanent exclusion from the Google AdSense service. All your earnings are lost and it is very hard to get back the access to your account. (In most cases impossible) The motivation behind such automatic clicks is very different. Maybe some competitor wants to harm you or a technical leads to multiple clicks by a human user. They all have in common that you as the AdSense account owner are responsible for any click fraud. You recognize unusual clicks when your page CTR is 1 – 3 % averaged and than it jumps up to 5, 10 or even more.

== Installation ==
1. Download the plugin "Click Fraud Monitoring" , unzip and place it in your wp-content/plugins/ folder. You can alternatively upload and install it via the WordPress plugin backend.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Select Plugins->Click fraud monitor 

== Screenshots ==
1. screenshot-1.png
2. screenshot-2.png
3. screenshot-3.png
3. screenshot-4.png

== Changelog ==

= 1.2 =
* Some coding improvements

= 1.1 =
* New: Block own IP
* New: Use custom element classes. E.g. div='myad1'
* Fixed Google AdSense iframe issues

= 1.0 = 
* First revision

== Issues ==
Not known yet

== Frequently Asked Questions ==

= Is the use of the plugin agains the terms of service (TOS) of Google AdSense, is the ad code modified? =
No, it´s not agains the terms. The `<div>` is not changing the original AdSense source code. It´s only a container wrapped around it.

= How does the plugin count the clicks? =
The function counts every click on a container that own a userdefined class (default class is 'cfmonitor'). As that div is wraped around it´s able to count mouse events like clicks.

= Does it work on Wordpress MU (Multisites) =
Not tested yet. Please let me know if you have any issues or if you test it successfully

= Does it work with YOOST SEO =
Yes

= Does it work with 'Quick AdSense' WordPress Plugin =
Yes

= Does it work with installation on localhost? =
If you are testing on a localhost environment make sure that you are running your site on http://127.0.0.1/. If you are using http://localhost the plugin is not able to block
your access for testing purposes.

= Is there a 100% guarantee that this plugin prevents you from exlusion of your AdSense Account? =
Due to the possible technical eventualities like rotating IP adresses there is no 100% safety. But this plugin helps you are lot to minimize the risk to be banned because of any malicious clicks.
It´s developed on a regular basis to cover as many eventualities as possible.

= Does it work in sidebars and widgets? =
Yes


= Does it work with alternative ad venders or only with AdSense? =
It´s working with any pay per click ad vendor. It´s not focused on AdSense.

Get the complete documentation at: http://www.clickfraud-monitoring.com/ 

== Upgrade Notice ==

= 1.1 =
* New: Block own IP
* New: Use custom element classes. E.g. div='myad1'
* Fixed Google AdSense iframe issues

= 1.0 =
* First revision

== Official Site ==
* http://www.clickfraud-monitoring.com
