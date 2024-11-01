=== Simplest Form ===
Contributors: tresrl, sineverba, blackqueen
Tags: contact form, form
Requires at least: 4.7
Tested up to: 4.9.4
Stable tag: 2.0.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

This plugin create a simple contact form, with database save. From 1.6 with Google Analytics goal!


== Description ==

This plugin create a simple contact form, with database save. From 1.6 version, it is possible to set a goal for Google Analytics. When user submit form, goal is reached. Submit is server/server side, not AJAX.
Is there a simple antispam check. Because spammers fill the forms automatically (and without CSS), if the "trap" field is filled, we have a spam submit.

Credits:
- Inspired by https://www.sitepoint.com/build-your-own-wordpress-contact-form-plugin-in-5-minutes/
- Simple Server Side Analytics (from https://github.com/dancameron/server-side-google-analytics)
- Bootstrap for CSS alert.
- Facebook SDK for autoload

Insert following shortcode in page: [simplest_form_sf]

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/` directory

== Usage ==

1. Create new page and insert shortcode [simplest_form_sf]. Go to settings and set up the data.


== Changelog ==

= 2.0.4 =
* Bugfix

= 2.0.3 =
* Added translation

= 2.0.2 =
* Small bugfix

= 2.0.1 =
* Small bugfix

= 2.0 =
* Ask for confirm when email is sent

= 1.9.1 =
* Changed label for checkboxes
* Getting the IP of user that submit the form

= 1.9 =
* Added 2 new checkboxes for privacy
* Added API call (only for new checkboxes)

= 1.8.1 =
* Removed default checked privacy checkbox

= 1.8 =
* Added antispam

= 1.7.1 =
* Improved Google Analytics goal instructions
* Added 'Reply-To' fields for user submitting

= 1.7 =
* Bug fix

= 1.6.1 =
* Fixed update plugin

= 1.6 =
* NEW! Google Analytics Goal settings section add.
* Improvement code
* Reorganized settings

= 1.5.5 =
* Added url page from module is sent

= 1.5.4 =
* Added URL for privacy in settings

= 1.5.3 =
* Added BCC settings
* Added shortcuts to settings page on plugin list page

= 1.5.2 =
* Moved settings under general settings
* Custom CSS for admin (better readibility)

= 1.5.1 =
* Bug fixed

= 1.5 =
* Added sortable to columns on admin section

= 1.4 =
* Added columns to Inbox for improved usability
* Update messages.pot catalog

= 1.3 =
* Added developer note to simplestform.php to add new fields

= 1.2 =
* Added phone box

= 1.1 =
* Added privacy box
* Added CSS

= 1.0 =
* First relase