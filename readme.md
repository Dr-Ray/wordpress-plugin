=== Plugin Name  ===
Bio Table Generator

=== Plugin Details ===
Contributors: Raypeter, Josh Cook (age-calculator plugin)
contributors link: http://www.joshcook.net/,  http://www.peterayuzowihe.com/
Donate link: http://www.peterayuzowihe.com/
Tags: age, calculator, table, generator, Auto Bio table generator

== Description ==

Using the quicktag [auto_bio_adder name='John Maliakay' networth='$20,000' image='http://localhost/wordpress/wp-content/uploads/2023/01/th-4.jpg' age='1968/01/10'] will a table with data specified in the field and Age = "55 years".

Visit [www.peterayuzowihe.com](http://www.peterayuzowihe.com "www.peterayuzowihe.com") for update information or to submit feature and support requests.

== Installation ==

1. Upload the 'auto-bio' directory to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' section within WordPress
3. Configure Dashboard Links from the 'Settings -> Auto Bio table generator' menu option

== Configuration ==

**Using the Quicktag**

The Auto Bio table generator quicktag can be used in any post or page using the following format:

[auto_bio_adder name='' networth='' image='http://localhost/wordpress/wp-content/uploads/2023/01/th-4.jpg' age='2000-01-10']

The following attributes can be used within the quicktag. Default values can be configured on the Auto Bio table generator's settings page.

_age_
The age attribute is to be entered Year, Month and then Day.  You can use either a . (period), a `/` (forward slash) or a - (hyphen) as the seperator.

_name_
The name attribute is mandatory field else default value would be used

_networth_
The networth attribute is mandatory field else default value would be used

_image_
The image field only accepts links as text. 




**Auto Bio table generator options page**

Configure the default text used when the plugin returns data. All values can be overridden within the quicktag.

== Frequently Asked Questions ==

= Your plugin rocks!  Can I donate? =

Absolutely!!!  Visit [www.peterayuzowihe.com](http://www.peterayuzowihe.com "www.peterayuzowihe.com") for more information!

= Development Notes =

*	I didn't see the reason for creating an object for such a simple plugin. In theory it would have taken more processor power on the server than needed.  So simple functions it is.

= 0.1.0 =
* First Public Release