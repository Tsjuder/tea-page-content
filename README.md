# Tea Page Content
## Introduction
**Tea Page Content** is a powerful plugin for Wordpress, that allows create blocks with content of any page, post, etc, and customize look of blocks via template system.

## Why?
I created this plugin because too many times features of simple small plugins was not enough for my tasks, and big powerful frameworks was too big for little specialized tasks. This plugin lies between these two edges.

## How?
Just install this via Wordpress Plugin Catalog or manually (upload, then unpack), and enable it. Then create widget or add shortcode into your post, and enjoy! You also can customize appearance of widget or shortcode by changing settings or built-in templates.

## By the way, about templates...
Templates is a powerful tool for full flexibility. Don't need create filters in your functions.php, just change built-in template or create your own! Every template is a simple php file which defines how will be look every post what you select. Since version 1.1.x, templates have a special variables that I called **template-level variables**.

## Template-Level Variables
Template variables is a feature for extreme flexibility. Now you can 

In short, this is a set of parameters, which are set in the template header, and which can then be changed in the widget or shortcode. There is support of default values, names, and several types of parameters: select, checkbox, textarea and text. For example, in the case of the bootstrap-template, you can explicitly specify the number of columns you want to see on each breakpoint, or choose the order of output records. In the case of custom templates, you can create any variable and to make with the help of it any conditions, managing all of this from the admin panel. Wonderful, isn't it? Let's take a closer look.
```php
@param color select White|Red|Yellow|Blue // List with all available colors
@param greeting text Hello! // Just text input with default value
@param show-greeting checkbox 0 // Checkbox, unchecked by default.
```
In the foregoing example, we create a few variables. All the variables should be created by mask `{name} {type} {default value}`. Names should be written in Latin letters, and the default value may be either a single word \ number, and a list separated by symbol `|`. In the template you can access to the parameters that stored in the variable `$template_variables`:
```php
if($template_variables['show-greeting']) {
	echo $template_variables['greeting'];
}
```
Please note that all variables exists. This means that you don't need check variable with `isset()`.

## Template Overview
### Default
Simple but effective template with one column. Ideal for sidebars or small blocks on site pages. Support two layouts: padded and standart. **Padded** means that around content of template will be padding with count of 1em. Standart don't have paddings around.

### Bootstrap 3.x
Powerful template for sites that builted with Bootstrap 3. This template have six variables. Let's take a closer look.
* **container-type** is a css class of wrapping div. You can select `container` or `container-fluid` (for responsive). Please note that `container` is unsuitable for sidebars.
* **ordering-type** is a order of matrix output. In bootstrap, we have rows and cols, i.e. a kind of associative array. With horizontal ordering type output order "left to right" will remain unchanged, but with transposed ordering type rows will be swapped with the columns and vice versa. Matrix will be **transposed**. Please note, that in `transposed` mode order of entries will be broken at lower resolutions.
* **column-count-x** is a count of columns for each of available breakpoints. If you select 3 columns, css class for every col will be `col-x-4` (because 12 will be divided on 3). Please note, that correct functioning depends of your mix of columns.

## Options
There is some built-in options for more flexibility, that can be used in shortcodes (or can be checked \ unchecked in widgets). This is:
* **show_page_thumbnail** allows you enable or disable displaying thumbnail of entry. If you don't want see page thumbnail in widget or shortcode, just uncheck checkbox (for widget) or type `show_page_thumbnail="false"` (for shortcode). Default - **true**.
* **show_page_content** allows you enable or disable displaying content of entry. Default - **true**.
* **show_page_title** allows you enable or disable displaying title of entry. Default - **true**.
* **linked_page_title** allows you enable or disable linking title of entry. In other words, title will be link to full article. Default - **false**.
* **linked_page_thumbnail** allows you enable or disable linking thumbnail of entry. In other words, thumbnail will be link to full article. Default - **false**.
* **order** allows you set entries order. All posts and pages will be sorted by date, and you can choose a direction - by ascending or by descending. Sorting by descending is a default behaviour.
* **template** allows you choose layout which will look as you want. In shortcode just type full name of your template without extension, for example `default` or `tpc-your-template-name`.
* **posts** allows you choose posts what you want to display. In widget, all what you need is just check desired posts, but in shortcode you need write ids of posts manually. F.e., `posts="12,4,63"`.

Please note: some of these options is just flags that depends of used template. This means that if you used your own template and set parameter `show_page_content`, don't forget add condition in your template file. Without condition content will be appear in any case. All built-in templates **have** support for all options. 

**Independent** options (depends of plugin) is: `order`, `template`, `posts`.

## Creating custom templates
By default plugin will be search custom templates in a folder named "templates" in your theme. For create the your one just create a new file with name like "tpc-{template-name}.php". Every template should be named by that mask! Then put in created file template code. Here you can see simplest example:
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

## Parameters \ Variables in template
Above you can see very simple example of custom template with `title` and `content` variables. But this is not all - there is a full list of allowed variables which you can use.
* **$entries** - List of posts, pages, etc.
	* **title** - Title of current entry
	* **content** - Content of current entry. When page have more tag, will be used `the_content` function, in other cases will be used `the_excerpt`
	* **thumbnail** - Thumbnail of entry (if exists)
	* **link** - Link of entry
	* **id** - Entry ID
* **$count** - Count of all passed entries
* **$instance** - Array with user defined and default parameters. There is all list of options from self-titled section above.

## Filters and actions
Will be very soon...

## What's next?
In next versions will be released these features:
* Page-level variables
* Waterfall template
* Customize ordering
* UI improvements
* **And more!**

## Migration Guide
### From 1.0.x to 1.1.x
Since 1.1.x, nothing was deleted. But some options was marked as deprecated. We recommend do these steps:
1. If you're using **default padded** template, change it on **default** with layout what you prefer.
2. If you're using shortcodes, replace parameter `id` to `posts`.
3. If you're using widgets with **turned off** thumbnail option, re-save each of it.