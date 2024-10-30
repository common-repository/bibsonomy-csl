=== Plugin Name ===
Contributors: seboettg, kchoong
Donate link:
Tags: bibsonomy, bibliography, publications, bookmark sharing, publication sharing, scientific publications
Requires at least: 3.0.1
Tested up to: 6.4.3
Stable tag: 2.2.3
License: GPLv3 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Creates publication lists based on the Citation Style Language (CSL) and Tag Clouds. Allows direct integration with BibSonomy/PUMA.

== Description ==

Plugin to create publication lists based on the Citation Style Language (CSL). Allows direct integration with the
social bookmarking and publication sharing systems [BibSonomy](https://www.bibsonomy.org) or [PUMA](https://www.academic-puma.de).

BibSonomy is a social bookmarking and publication-sharing system. It integrates the features of bookmarking
systems with team-oriented publication management. PUMA offers the same features and additional means for science groups and universities.
PUMA and BibSonomy provide to their users the ability to store and organize their bookmarks and publications online. Further features include
the integration of scientific communities through user groups as well as the collection, management, exchange, export and display of literature lists.

With this plugin you have the possibility to render a list of publications, which you can filter by user or group.

In addition you can filter the list by using tags or free text search. So it's possible to render your own
publications by selecting your own BibSonomy user and filtering the list with the tag 'myown'. It's also easily possible to integrate
your BibSonomy Tag Cloud.

To use this plugin, you need your PUMA/BibSonomy user name and API key.

A extensive description about the plugin is available at
https://blog.bibsonomy.org/2012/12/feature-of-week-add-publication-lists.html

== Installation ==

1. Unzip the `bibsonomy-csl.zip`
2. Upload the folder `bibsonomy-csl` to the `/wp-content/plugins/` directory
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Activate the 'TagCloudWidget' through the 'Plugins' menu in WordPress
5. Insert your API Settings through General Options on the BibSonomy CSL Options Page (find the latter on your settings-page in BibSonomy)
6. On 'new article' or 'new page' page now then you find a meta box 'Add BibSonomy Publications'. Here you can enter your settings for selecting the publications to display
	6.1 Choose your content type (user/group/viewable) and enter the user id or or the group id
	6.2 If you want filter your selection with tags (e.g. myown) or free text search
	6.3 Choose a style from the given list or enter a custom url of another style
	6.4 Customize the look and feel of the list, by changing the CSS
	6.5 Save the post and take a preview/final look.
7. To add a tag cloud go to Design => Widgets and drag the 'BibSonomyTagCloud Widget' to your preferred place and fill out the form.

= PHP Extenstion Requirements =
- dom
- mbstring
- json

Make sure you have the listed extentions in your PHP settings.

== Frequently Asked Questions ==

No questions yet.

== Screenshots ==

1. Rendered publication list from BibSonomy of group 'kde' and filtered by tag 'bibsonomy'
2. 'Add BibSonomy Publications' MetaBox to integrate a publication list in a post
3. Decorated tag cloud

== Changelog ==

= 2.2.3 =
- Add query searchtype selection to switch between search index and local requests (default: search index)
- Only apply extra search parameters if searchtype is set to 'searchindex'
- Fix user lists broken due to added sorting params in request

= 2.2.2 =
- Support for WordPress 6.4+
- Update to latest restclient changes
- Use file-based CSL styles
- Use sort key and order in post requests

= 2.2.1 =
- Support for WordPress 6.1+
- Update to latest restclient changes
- Update other dependencies

= 2.2.0 =
- Update to latest restclient version with new citeproc
- fix pdf preview and download
- fix tag cloud
- fix sorting
- fix year grouping
