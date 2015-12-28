=== Tea Page Content ===
Contributors: Tsjuder
Tags: plugin, widget, shortcode, posts, post, pages, page, content
Requires at least: 4.0
Tested up to: 4.4
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Powerful plugin that allows create blocks with content of any page, post, etc, and customize look of blocks via templates.

== Description ==

Tea Page Content is a powerful plugin that allows create blocks with content of any page, post, etc, and customize look of blocks via template system. You can select one or more entries, choose template (or create your own) and display it by widget or shortcode. Templates presents very flexible system for appearance control of created blocks. At this moment there is a two built-in templates: default and padded default.

== Installation ==

1. Upload the plugin archive to the `/wp-content/plugins` directory and unpack it, or install the plugin through the WordPress plugins screen directly
2. Activate the plugin through the 'Plugins' screen in WordPress

== Frequently Asked Questions ==

= Is it just another page content plugin? =

Yes, and no. This plugin lies between two edges - small plugins for little specific tasks and big frameworks.

= Is this plugin compatible with custom post types? =

At this moment answer is no, but you can add your post type via filter `tpc_post_types`. Native support of custom post types will be added in next release.

= Is this plugin compatible with my theme? =

Yes. But every theme have unique css, and appearance of widget \ shortcode will be depend on theme styles.

== Changelog ==

= 1.0 =
* First release with basic functionality

== Documentation ==

= Shortcodes =
There is just one shortcode `tea_page_content`. Below there is an example with all of possible parameters.
`[tea_page_content template="default" order="asc" id="12,45,23" thumbnail="false"]`

= Parameters =
There is some built-in options. This is thumbnail, order and template. Let's take a closer look:

* *Thumbnail* allows you enable or disable displaying thumbnail of entry. If you don't want see page thumbnail in widget or shortcode, just uncheck checkbox (for widget) or type `thumbnail="false"` (for shortcode).
* *Order* allows you set entries order. All posts and pages will be sorted by date, and you can choose a direction - by ascending or by descending. Sorting by descending is a default behaviour.
* *Template* allows you choose layout which will look as you want. In shortcode just type full name of your template without extension, for example `default` or `your-template-name`.

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

= Variables in template =
Above you can see very simple example of custom template with `title` and `content` variables. But this is not all - there is a full list of allowed variables which you can use.

* **$entries** - List of posts, pages, etc.
	* **title** - Title of current entry
	* **content** - Content of current entry. When page have more tag, will be used `the_content` function, in other cases will be used `the_excerpt`
	* **thumbnail** - Thumbnail of entry (if exists)
	* **link** - Link of entry
	* **id** - Entry ID
* **$instance** - Array with user defined and default parameters
