=== Auto Version ===

Contributors:
Donate link:
Tags: CSS, JS, JavaScript, Version
Requires at least:
Tested up to:
Stable tag:
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-3.0.html

== Description ==

Auto-versioning CSS and JavaScript files in WordPress, allowing for cache busting when these files are changed.

== Installation ==

1. Upload files to the `/wp-content/plugins/` directory of your WordPress installation.
  * Either [download the latest files](https://github.com/artcomventure/wordpress-plugin-autoVersion/archive/master.zip) and extract zip (optionally rename folder)
  * ... or clone repository:
  ```
  $ cd /PATH/TO/WORDPRESS/wp-content/plugins/
  $ git clone https://github.com/artcomventure/wordpress-plugin-autoVersion.git
  ```
  If you want a different folder name than `wordpress-plugin-autoVersion` extend clone command by ` 'FOLDERNAME'` (replace the word `'FOLDERNAME'` by your chosen one):
  ```
  $ git clone https://github.com/artcomventure/wordpress-plugin-autoVersion.git 'FOLDERNAME'
  ```
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. **Enjoy**

== Usage ==

You can find all settings on the 'Auto Version' settings page listed in the submenu of 'Settings'.

1. Disable/Set/Force auto versioning for CSS and/or JavaScript files.
2. Optionally add a custom version number. By default it's the modification timestamp of the corresponding file.
3. Click 'Save Changes'.

== Plugin Updates ==

Although the plugin is not _yet_ listed on https://wordpress.org/plugins/, you can use WordPress' update functionality to keep it in sync with the files from [GitHub](https://github.com/artcomventure/wordpress-plugin-autoVersion).

**Please use for this our [WordPress Repository Updater](https://github.com/artcomventure/wordpress-plugin-repoUpdater)** with the settings:

* Repository URL: https://github.com/artcomventure/wordpress-plugin-autoVersion/
* Subfolder (optionally, if you don't want/need the development files in your environment): build

_We test our plugin through its paces, but we advise you to take all safety precautions before the update. Just in case of the unexpected._

== Questions, concerns, needs, suggestions? ==

Don't hesitate! [Issues](https://github.com/artcomventure/wordpress-plugin-autoVersion/issues) welcome.

== Changelog ==

= 1.0.0 - 2017-07-18 =
**Added**

* Initial file commit.
