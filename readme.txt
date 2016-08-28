=== Tea Page Widget & Content ===
Contributors: Tsjuder
Tags: plugin, widget, shortcode, posts, post, pages, page, content, template, templates
Requires at least: 4.0, PHP 5.3
Tested up to: 4.6
Stable tag: 1.2.0
Author URI: https://github.com/Tsjuder
Plugin URI: http://tsjuder.github.io/tea-page-content/
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin that allows create widget or shortcode with content of any post, and customize look of blocks via templates.

== Description ==

Tea Page Content is a powerful plugin that allows create blocks with content of any page, post, etc, and customize look of blocks via template system. You can select one or more entries, choose template (or create your own) and display it by widget or shortcode. Templates presents very flexible system for appearance control of created blocks. At this moment there is a two built-in templates: default and bootstrap 3.x.

= Key features =
* Very flexible template system
* Native support of all post types
* Possibility to create your own templates
* Developer and user friendly
* Easy to use and beautiful UI

= Migration Guides =
Stay tuned with new versions. For make updates safe and fast, check changelog at <a href="https://wordpress.org/plugins/tea-page-content/changelog/">Changelog</a> tab.

If you found a bug or have a suggestion, please create topic on forum or send me email (raymondcostner at gmail.com).

= Documentation =
You can find primary description at <a href="https://wordpress.org/plugins/tea-page-content/other_notes/">Other Notes</a> tab, and details at <a href="http://tsjuder.github.io/tea-page-content/">Github Page</a>.

== Installation ==

1. Upload the plugin archive to the `/wp-content/plugins` directory and unpack it, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress

== Screenshots ==
1. First screen with list of posts and basic parameters
2. Second screen after choosing template and list of template variables

== Frequently Asked Questions ==

= Is it just another page content plugin? =

Yes, and no. This plugin lies between two edges - small plugins for little specific tasks and big frameworks.

= Is this plugin compatible with custom post types? =

Yes, it is.

= Is this plugin compatible with my theme? =

Yes. But every theme have unique css, and appearance of widget \ shortcode will be depend on theme styles.

= I don't have link to full post. How I can add it? =

Links to full entry appears automatically - this feature depends of theme settings and Wordpress core. If you have more-tag in post content, or if content length long enough, "read more" link will be available. We don't override this behavior. Use native wordpress hooks for change it.

= I found a bug or have a suggestion. What I can do? =
You can create new topic in forum at wordpress.org, or send me email. I will answer you as soon as possible.

== Changelog ==

= 1.2.0
* \+ New feature - page-level variables
* \+ Added button for inserting shortcode in editor
* \+ New availaibe property in templates - `caller`. Value of caller maybe `widget` or `shortcode` if template in this moment using in shortcode or widget resp.
* \+ Enclosed shortcodes availaible
* \* User Interface was improved for more usability
* \* Checked for Wordpress 4.6 support

= 1.1.1 =
* \+ Added new template-variable type "caption" that allows you describe your template
* \* Checked for Wordpress 4.5 support
* \* Improved Bootstrap template

= 1.1.0 =
* \+ Native support for all existed post types (and custom too)
* \+ Count of entries now passed in template
* \+ New feature - template-level variables
* \+ New template: Bootstrap 3.x
* \+ Added possibility hide title, content and link it. This feature depends of used template (all built-in templates except deprecated supports it)
* \- Default-Padded template, `thumbnail` widget and shortcode parameter, `id` shortcode parameter is **deprecated**. See docs for migration guide
* \* CSS for frontend part changed, improved paddings, adds hover effects
* \* Global code refactoring. We are friendly for developers!
* \* Bug fixes

= 1.0.0 =
* First release with basic functionality

== Documentation ==

= Shortcodes =
There is just one shortcode `tea_page_content`. Below there is an example with basic parameters.
`[tea_page_content template="default" order="asc" posts="12,45,23"]`
You also can used template variables (see Templates section at documentation) and options.

= Parameters =
There is some built-in options. Let's take a closer look:

* **order** allows you set entries order. All posts and pages will be sorted by date, and you can choose a direction - by ascending or by descending. Sorting by descending is a default behaviour.
* **template** allows you choose layout which will look as you want. In shortcode just type full name of your template without extension, for example `default` or `your-template-name`.
* **show_page_thumbnail** allows you enable or disable displaying thumbnail of entry. If you don't want see page thumbnail, type `show_page_thumbnail="false"`. Default - *true*.
* **show_page_content** allows you enable or disable displaying content of entry. Default - *true*.
* **show_page_title** allows you enable or disable displaying title of entry. Default - *true*.
* **linked_page_title** allows you enable or disable linking title of entry. In other words, title will be link to full article. Default - *false*.
* **linked_page_thumbnail** allows you enable or disable linking thumbnail of entry. In other words, thumbnail will be link to full article. Default - *false*.

= Creating simplest custom template =
By default plugin will be search custom templates in a folder named `templates` in your theme. For create the one just add into this directory a new file with name like `tpc-{template-name}.php`. Every template **should** be named by that mask! Then put in created file your code. For example:
`
<?php foreach ($entries as $entry) : ?>
	<div class="entry">
		<h3>
			<?php echo $entry['title'] ?>
		</h3>
		
		<div class="post-content">
			<?php echo $entry['content'] ?>
		</div>
	</div>
<?php endforeach; ?>
`
For using your templates in shortcode, you need just choose it in widget or pass full filename (but without extension) in shortcode. For example:
`template="tpc-my-template"`

= Parameters in template =
Above you can see very simple example of custom template with `title` and `content` parameters. But this is not all - there is a full list of allowed parameters which you can use.

* **$count** - Count of all passed entries
* **$instance** - Array with user defined and default parameters. There is all list of options from self-titled section above.
* **$entries** - List of posts, pages, etc.
	* **title** - Title of current entry
	* **content** - Content of current entry. When page have more tag, will be used `the_content` function, in other cases will be used `the_excerpt`
	* **thumbnail** - Thumbnail of entry (if exists)
	* **link** - Link of entry
	* **id** - Entry ID

= Details =
Because full manual is too long, you can see it at <a href="http://tsjuder.github.io/tea-page-content/">Github Page</a>. Get details and updating information about new features includes filters, template-level variables and more.

= Migration Guide =
**From 1.0.x to 1.1.x**
Since 1.1.x, nothing was deleted. But some options was marked as deprecated. We recommend do these steps:
* If you're using **default padded** template, change it on **default** with layout what you prefer.
* If you're using shortcodes, replace parameter `id` to `posts`.
* If you're using widgets with **turned off** thumbnail option, re-save each of it.