=== Global Plugin Update Notice ===
Contributors: Viper007Bond
Donate link: http://www.viper007bond.com/donate/
Tags: admin, plugins, update, notice, warning
Requires at least: 2.5
Stable tag: trunk

When a new version of an activated plugin is available for download from WordPress.org, you will recieve a notice on all admin pages. No more having to check the plugins page!

== Description ==

If you're like me, you want to make sure to always be running the latest version of all of the plugins that you have installed and activated. The problem though is that you have to keep checking your plugins page to see if any have updates available. Even worse, if you have a ton of plugins uploaded to your blog, that list is rather long.

Enter Global Plugin Update Notice. When a new version of an activated plugin is available for download, you will receive a notice on all admin pages much like you do when a new version of WordPress is available. This saves you time and makes sure your blog is secure and up to date.

*If you for some reason have decided not to upgrade to WordPress 2.5 (I dunno why you wouldn't upgrade), you'll need to use [this old version](http://downloads.wordpress.org/plugin/global-plugin-update-notice.1.0.1.zip) of the plugin.*

== Installation ==

###Upgrading From A Previous Version###

To upgrade from a previous version of this plugin, delete the entire folder and files from the previous version of the plugin and then follow the installation instructions below.

###Installing The Plugin###

Extract all files from the ZIP file, making sure to keep the file structure intact, and then upload the plugin's folder to `/wp-content/plugins/`.

This should result in the following file structure:

`- wp-content
    - plugins
        - global-plugin-update-notice
            | global-plugin-update-notice.php
            | readme.txt
            | screenshot-1.png`

Then just visit your admin area and activate the plugin.

**See Also:** ["Installing Plugins" article on the WP Codex](http://codex.wordpress.org/Managing_Plugins#Installing_Plugins)

== Screenshots ==

1. Example plugin update notification

== ChangeLog ==

**Version 1.1.1**

* Don't display this bar on the update plugin page.

**Version 1.1.0**

* Update for WordPress 2.5 by adding "upgrade automatically" links.
* Use the same text strings as WordPress uses so people don't have to translate this plugin specifically. This meant having each plugin have it's own update row.

**Version 1.0.1**

* Fixed an issue that occured when there was a single plugin needing updating.

**Version 1.0.0**

* Initial release.