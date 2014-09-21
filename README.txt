=== mind-body ===
Contributors: vimes1984
Tags: wordpress, mindbody, plugin
Requires at least: 3.5.1
Tested up to: 3.6
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Mind body API integration

== Description ==

Mind body API integration, this is a mind body api intergation kit for wordpress.
it's intention is to try and import the mindbody api lesson's, classes and staff over to wordpress/woocommerce.
If any further help is required drop me a line on here or contact http://accruemarketing.com/

you have a few shortcodes: 

[mindbodyeventscal]

and 
[mindbodyeventscalwid]

these may require additional javascript files
at the very least you'll  
need to add in the JS file for these if anybody is interested they can open a ticket and I'll upload them... 
There are also a few factories in the public.js file that pertain to the shop page loop and require a custom shop page loop using isotope if anybodies interested I can upload that aswell...


== Installation ==

1. Upload `` to the `/wp-content/plugins/` directory
1. Activate the plugin through the "Plugins" menu in WordPress
1. Place `<?php do_action("mind-body_hook"); ?>` in your templates

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif). Note that the screenshot is taken from
the /assets directory or the directory that contains the stable readme.txt (tags or trunk). Screenshots in the /assets
directory take precedence. For example, `/assets/screenshot-1.png` would win over `/tags/4.3/screenshot-1.png`
(or jpg, jpeg, gif).
2. This is the second screen shot

== Changelog ==

= 1.0 =
* Initial Commit