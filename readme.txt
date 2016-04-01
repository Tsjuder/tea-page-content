=== Tea Page Widget & Content ===
Contributors: Tsjuder
Tags: plugin, widget, shortcode, posts, post, pages, page, content
Requires at least: 4.0
Tested up to: 4.4.2
Stable tag: 1.1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Plugin that allows create widget or shortcode with content of any post, and customize look of blocks via templates.

== Description ==

Tea Page Content is a powerful plugin that allows create blocks with content of any page, post, etc, and customize look of blocks via template system. You can select one or more entries, choose template (or create your own) and display it by widget or shortcode. Templates presents very flexible system for appearance control of created blocks. At this moment there is a two built-in templates: default and bootstrap 3.x. Supported languages: English and Russian.

= Key features =
* Very flexible template system
* Native support of all post types
* Possibility to create your own templates
* Developer and user friendly
* Easy to use

= Migration Guides =
Stay tuned with new versions. For make updates safe and fast, check migration guide at <a href="https://wordpress.org/plugins/tea-page-content/other_notes/">Other Notes</a> tab.

= Documentation =
You can find primary description at <a href="https://wordpress.org/plugins/tea-page-content/other_notes/">Other Notes</a> tab, and details at <a href="http://tsjuder.github.io/tea-page-content/">Github Page</a>.

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

= I don't have link to full post. How I can add it? =

Links to full entry appears automatically - this feature depends of theme settings and Wordpress core. If you have more-tag in post content, or if content length long enough, "read more" link will be available. We don't override this behavior. Use native wordpress hooks for change it.

= I found a bug or have a suggestion. What I can do? =
You can create new topic in forum at wordpress.org, or send me email (in my profile). I will answer you as soon as possible.

== Changelog ==

= 1.1.0 =
* \+ Native support for all existed post types (and custom too)
* \+ Count of entries now passed in template
* \+ New feature - template-level variables
* \+ New template: Bootstrap 3.x
* \+ Added possibility hide title, content and link it. This feature depends of used template (all built-in templates except deprecated supports it)
* \- Default-Padded template, `thumbnail` widget and shortcode parameter, `id` shortcode parameter is **deprecated**. These features will be **deleted** in version 1.2. See docs for migration guide
* \* CSS for frontend part changed, improved paddings, adds hover effects
* \* Global code refactoring. We are friendly for developers!
* \* Bug fixes

= 1.0.0 =
* First release with basic functionality

== Documentation ==

= Migration Guide =
** From 1.0.x to 1.1.x **
Since 1.1.x, nothing was deleted. But some options was marked as deprecated. We strongly recommend do these steps:
1. If you're using **default padded** template, change it on **default** with layout what you prefer.
2. If you're using shortcodes, replace parameter `id` to `posts`.
3. If you're using widgets with **turned off** thumbnail option, re-save each of it.

= Shortcodes =
There is just one shortcode `tea_page_content`. Below there is an example with basic parameters.
`[tea_page_content template="default" order="asc" posts="12,45,23"]`
You also can used template variables (see Templates section at documentation) and options.

= Parameters =
There is some built-in options. Let's take a closer look:

* *order* allows you set entries order. All posts and pages will be sorted by date, and you can choose a direction - by ascending or by descending. Sorting by descending is a default behaviour.
* *Template* allows you choose layout which will look as you want. In shortcode just type full name of your template without extension, for example `default` or `your-template-name`.
* *show_page_thumbnail* allows you enable or disable displaying thumbnail of entry. If you don't want see page thumbnail, type `show_page_thumbnail="false"`. Default - *true*.
* *show_page_content* allows you enable or disable displaying content of entry. Default - *true*.
* *show_page_title* allows you enable or disable displaying title of entry. Default - *true*.
* *linked_page_title* allows you enable or disable linking title of entry. In other words, title will be link to full article. Default - *false*.
* *linked_page_thumbnail* allows you enable or disable linking thumbnail of entry. In other words, thumbnail will be link to full article. Default - *false*.

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
	* **count** - Count of all passed entries
* **$instance** - Array with user defined and default parameters. There is all list of options from self-titled section above.