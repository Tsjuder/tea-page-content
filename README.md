# Tea Page Content
## Introduction
**Tea Page Content** is a powerful plugin for Wordpress, that allows create blocks with content of any page, post, etc, and customize look of blocks via template system.

## Why?
I created this plugin because too many times features of simple small plugins was not enough for my tasks, and big powerful frameworks was too big for little specialized tasks. This plugin lies between these two edges.

## How?
Just install this via Wordpress Plugin Catalog or manually (upload, then unpack), and enable it. Then create widget or add shortcode into your post, and enjoy! You also can customize appearance of widget or shortcode by changing settings or built-in templates.

## By the way, about templates...
Templates is a powerful tool for full flexibility. Don't need create filters in your functions.php, just change built-in template or create your own! Every template is a simple php file which defines how will be look every post what you select.

## Options
There is some built-in options for more flexibility. This is thumbnail, order and template.
* Thumbnail allows you enable or disable displaying thumbnail of entry. If you don't want see page thumbnail in widget or shortcode, just uncheck checkbox (for widget) or type `thumbnail="false"` (for shortcode).
* Order allows you set entries order. All posts and pages will be sorted by date, and you can choose a direction - by ascending or by descending. Sorting by descending is a default behaviour.
* Template allows you choose layout which will look as you want. In shortcode just type full name of your template without extension, for example `default` or `your-template-name`.

## Creating custom templates
By default plugin will be search custom templates in a folder named "templates" in your theme. For create the  your one just create a new file with name like "tpc-{template-name}.php". Every template should be named by that mask! Then put in created file this code (for example):
```php
<?php if(isset($entries) && count($entries)) : ?>
<?php foreach ($entries as $key => $entry) : ?>
	<div class="tpc-entry-block">
		<h3 class="tpc-title">
			<?php echo $entry['title'] ?>
		</h3>
		
		<div class="tpc-content post-content">
			<?php echo $entry['content'] ?>
		</div>
	</div>
<?php endforeach; ?>
<?php endif; ?>
```

Very well! Now you can select your template via selectbox (in widget), or type `template="tpc-test-template"` in shortcode.

## Variables in template
Above you can see very simple example of custom template with `title` and `content` variables. But this is not all - there is a full list of allowed variables which you can use.
* $entries - List of posts, pages, etc.
	* title - Title of current entry
	* content - Content of current entry. When page have more tag, will be used `the_content` function, in other cases will be used `the_excerpt`
	* thumbnail - Thumbnail of entry (if exists)
	* link - Link of entry
	* id - Entry ID
* $instance - Array with user defined and default parameters

## Filters and actions
Will be soon...

## What's next?
I have very many ideas about future of this plugin. In next versions I planning add bootstrap, masonry and waterfall templates, some new options and more. Stay tuned!