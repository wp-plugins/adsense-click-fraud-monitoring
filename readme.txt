=== Google AdSense Click-Fraud Monitoring Plugin ===
Contributors: ReneHermi
Donate link: http://www.demo.clickfraud-monitoring.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: Google AdSense, AdSense, Ad Sense, Ajax, AdSense plugin, Klickbetrug, notification, shortcode, Plugin, links, google, posts, click bomb, clickbomb, admin, ads, advertisement, advertising, akismet, seo, click protection, click protect, clickfraud, click-fraud, PPC, widget
Requires at least: 3.1+
Tested up to: 4.1
Stable tag: 1.8.6

Google AdSense Plugin for Google AdSense and other PPC vendors. Prevents to be banned due to malicious clicks on your ads. Increases CPC and your revenue.

== Description ==

Prevents to be banned and excluded from your AdSense account due to malicious or unintended third party clicks on advertisements on your website.
Blocks visitor from seeing your ads when they click multiple times on them. Increases your revenue and CPC.
Find out why this plugin is fantastic:
<br>

<h3> Links </h3>

[DEMO](http://demo.clickfraud-monitoring.com/ "Adsense Click Fraud Monitor Demo")<br>
[More Information](http://www.clickfraud-monitoring.com/ "AdSense Click plugin documentation")

<h3>Extended Version</h3>

You can purchase an extended version of this plugin with additional features<br>
Purchase extended [Click-Fraud Monitoring](http://demo.clickfraud-monitoring.com/ "AdSense Click-Fraud Software")
</p>
</p>
Official site for demonstration, docs and support:
http://www.clickfraud-monitoring.com

This plugin is in active development and will be updated on a regular basis - Please do not rate negative before i tried my best to solve your issue. Thanks buddy!

= Why i need AdSense Click Fraud Monitoring Plugin? =

* Prevent malicious clicks by bots on your Ads
* Prevent malicious clicks by human user on you Ads
* Prevent clicks by friends on your Ads who want to “help” you.
* Prevent unintended clicks by your own
* New: Blocks a list of specific IP´s including detection of your own IP
* New: Use custom name for your ad block class
* New: Blocks a comma separated list of specific IP´s including detection of your own IP

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

Google is pretty good to recognize a few mistaken clicks but a larger attack should be reported at the official Google contact form [Invalid Clicks Contact Form](https://support.google.com/adsense/contact/invalid_clicks_contact?hl=en "Invalid Clicks contact form")

= Why would Google ban me? =

In easy words: Google takes care that every click on any AdSense advertising is done by a real human visitor. Automatic clicks by crawl bots or a lot of automated or manual processed clicks (a so-called Clickbomb) can lead to a complete and permanent exclusion from the Google AdSense service. All your earnings are lost and it is very hard to get back the access to your account. (In most cases impossible) The motivation behind such automatic clicks is very different. Maybe some competitor wants to harm you or a technical leads to multiple clicks by a human user. They all have in common that you as the AdSense account owner are responsible for any click fraud. You recognize unusual clicks when your page CTR is 1 – 3 % averaged and than it jumps up to 5, 10 or even more.

= Thanks and credit =
Thanks and credit goes to user Haval Aloussi for his help: http://profiles.wordpress.org/hoovilation/

== Other Notes ==

Let me explain why you need extra protection for your Google AdSense account.

= Definition of Click fraud and Invalid clicks = 

I often get questions from new website owners about the meaning what these two terms mean, and i'd like to help you to understand the difference.
Google Adsense defines Invalid clicks as clicks for which they decide not to charge their AdWords advertisers, since they may artificially drive up advertiser cost or publisher revenue. These include extraneous clicks without any value to the advertiser, such as the second click of a double-click. They also include many other types of clicks that the have determined aren't motivated by genuine user interests.

Google Adsense "Invalid clicks" are often confused with "clicking on your own ads". However, they would like to stress that invalid clicks are generally any clicks that artificially inflate advertiser cost or publisher revenue, regardless of their source.

Google Adsense Click fraud is a subset of invalid clicks that are generated with malicious or fraudulent intent -- in other words, clicks that are intended to drive up advertiser cost or publisher revenue artificially. Sources for these clicks include, but are not limited to:
* A publisher clicking on his own ads, or encouraging clicks on his ads
* Users or family members clicking to support the site / publisher
* Third-party programs with user incentives, such as paid-to-click services and click-exchanges
* Automated clicking tools, robots, or other deceptive software

The same principles above apply to ad impressions and conversions as well. Some sources of invalid impressions include, but are not limited to:
* Excessive page refreshes, generated either manually or automatically
* Third-party programs with user incentives, such as paid-to-surf or auto-surf programs
* Third-party programs for purchasing fixed amounts of traffic, e.g. "$10 for 1,000 page views"

As a reminder, any method that artificially generates impressions, conversions or clicks is strictly prohibited by the [Google AdSense program policies.](https://support.google.com/adsense/answer/48182?sourceid=aso&subid=ww-en-et-asblog_2008-08-08&medium=link) You can also find more information about these topics in the 
[Google AdSense Invalid Clicks FAQ](https://support.google.com/adsense/answer/16737?ref_topic=1348720&rd=2) and the [Google AdSense Ad Traffic Quality Resource Center.](http://www.google.com/ads/adtrafficquality/)

= Why do i need extra protection for Google AdSense =

Thats very easy: Google will not tell you in advance or at the same timepoint about any issue with your click conversion rate 
when they detect something strange like a lot of increasing clicks or clicks only from a specific country. The reason is obviously the fact that smart people could find out the detection formula behind the AdSense programm. They could be able to find out what they have to prevent so Google would not be able to recognize if a click had been done by a real visitor, a bot or any instructed visitor.
The complete AdSense programm would be totally broken and not working profitable any more

So, Google will watch you for a time and when you do not expect it, they will immediately close your account and will not give you any reason for it. They will even close your account for any detected issue which has been done in the past, from days or weeks ago. 
You have no chance to find out what you or any other party did on your website that leads to your banned AdSense account.

The only thing you can do is to take care that Google AdSense will never be able to detect any suspicious activities regarding the AdSense click behavior on your website, so have to reduce the malicious clicks on your site as much as possible.

Be safe, use the ClickFraud Monitor!

== Official Site ==
* http://www.clickfraud-monitoring.com

== Installation ==
1. Download the Google AdSense plugin  "Click Fraud Monitoring" , unzip and place it in your wp-content/plugins/ folder. You can alternatively upload and install it via the WordPress plugin backend.
2. Activate the AdSense plugin through the 'Plugins' menu in WordPress.
3. Select Plugins->Click fraud monitor 

== Screenshots ==
1. AdSense 1
2. AdSense 2
3. AdSense 3
3. AdSense 4

== Changelog ==

= 1.8.6 =
* Fix: Solves the error 'Call to a member function on a non-object' 
* New: Modify the function which checks if the class 'cfmonitor' is available on the page
* New: Add some more documentation how to check if the plugin is working as expected.

= 1.8.5 =
* Tested up to WP 4.1

= 1.8.4 =
* Tweak: Tested for WP 4.0.1

= 1.8.3 =
* Fix: Minor fixes

= Version 1.8.2 = 
* Fix: Some undefined vars
* Fix: missing double space in sql query ID prevents deltadb

= Version 1.8.1 =

* **Bugfixes**
        * *Solves a bug where AdSense clicks are not cummulated in the table of blocked IP addresses
* **Enhancements**
        * *Improved integration check. 

= Version 1.8.1 = 

Fix: Undefined var $_POST['data']
Fix: wrong arguments for method prepare_table which shows the ip from blocked AdSense user
New: Checked for WordPress 4.0

= Version 1.8.0 =
* Tested up to Wordpress 3.9.2

= Version 1.7.9 =
* Fix: Change sample url to working example google.de. Example.com results in timeouts on some apache server

= Version 1.7.8 =
Fix: New declaration of method names. Prevent duplicate use of simple_html_dom function with third party plugins that use the same class.

= Version 1.7.7 =
* Fix: Error when checking URL for integrated div class
* Fix: Change function name file_get_html to clickfraud_file_get_html
* Tested up to WP 3.8.1

= Version 1.7.6 (23.01.2014) = 
* New: Performance improvements
* New: Check script to determine if ads has been wrapped successfully with class 'cfmonitor' by the user
* Rewrite of the table listing (Better pagination)
* Fixed: Rewritten noConflict mode to be more compatible with third party plugins
* CSV Export (In premium edition)

= 1.7.4 - 1.7.5 =
* Some smaller improvements and fixes. Nothing relevant

= 1.7.3 = 
* Use of blockUI to prevent very fast multiple consecutively clicks

= 1.7.2 =
* New: See the path and URL of the clicked ad
* Some minor changes like spelling issues. Nothing safety related 

= 1.7.1 = 

* New: Use of native WordPress tables for list of blocked IP adresse
* New: Sort by IP and Last Click time possible
* New: premium features

= 1.7 =
* Fix: eventPrevent not working in IE

= 1.6 =
* New: Blocks a list of specific IP´s including detection of your own IP
  Thanks to user Haval Aloussi for his changes http://profiles.wordpress.org/hoovilation/

= 1.5 = 
* Some fixes to prevent js breaking with some third party themes *
* Compatible with WP 3.6
* Minified JS scripts for better performance

= 1.4 = 
* Fix

= 1.3 =
* Fix

= 1.2 =
* Some coding improvements

= 1.1 =
* New: Block own IP
* New: Use custom element classes. E.g. div='myad1'
* Fixed Google AdSense iframe issues

= 1.0 = 
* First revision


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