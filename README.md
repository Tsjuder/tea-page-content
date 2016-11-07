# Tea Page Content
## Introduction
**Tea Page Content** is a powerful plugin for Wordpress, that allows create blocks (widgets and shortcodes) with content of any page, post, etc, and customize look of blocks via template system.

## Why?
I created this plugin because too many times features of simple small plugins was not enough for my tasks, and big powerful frameworks was too big for little specialized tasks. This plugin lies between these two edges.

## How?
Just install this via Wordpress Plugin Catalog or manually (upload, then unpack), and enable it. Then create widget or add shortcode into your post, and enjoy! You also can customize appearance of widget or shortcode. Just change settings or choose one of built-in templates.

## By the way, about templates...
Templates is a powerful tool for full flexibility. Don't need create filters in your functions.php, just change built-in template or create your own! Every template is a simple php file which defines how will be look every post what you select. Since version 1.1.x, templates have a special variables that I call **template-level variables**.

## Template-Level Variables
In short, this is a set of parameters, which are set in the template header, and which can then be changed in the widget or shortcode. There is support of default values, names, and several types of parameters: select, checkbox, textarea and text. For example, in the case of the bootstrap-template, you can explicitly specify the number of columns you want to see on each breakpoint, or choose the order of output records. In the case of custom templates, you can create any variable and use it in your template to implement any conditions and manage its content from the admin panel. Wonderful, isn't it? Let's take a closer look.
```php
@param color select White|Red|Yellow|Blue // List with all available colors
@param greeting text Hello! // Just text input with default value
@param show-greeting checkbox 0 // Checkbox, unchecked by default.
```
In the foregoing example, we create a few variables. All the variables should be created by mask `{name} {type} {default value}`. Names must be presented the Latin alphabet, and the default value can be a word, number or list за words, separated by symbol `|`. In the template you can access to this variables via variable named `$template_variables`:
```php
if($template_variables['show-greeting']) {
	echo $template_variables['greeting'];
}
```
Please note that all variables exists. This means that you don't need check property in `$template_variables` with `isset()`.

## Page-level variables
Sometimes there are situations when you need to change the widget thumbnail or introductory text of entry, keeping a link to it. Sometimes the built-in Wordpress tools are not sufficient to implement this functionality. One way is to use a text widget, but with the Tea Page Content has become possible to overwrite the title of displayed content and thumbnail of entry without losing the link to it. Great, isn't it? Page-level variables are strictly predetermined, but you can use filters to add another variable.

To set page-level variables for the selected page, click on gear icon next to the name of a page in a widget interface. This will open a modal window in which you can specify a new title, content and thumbnail of the page in this particular widget.

In template will be available variable `$entries`, combined with page-level variables that you set before. In other words, you can not get the original title if it has been overwritten by you earlier. You can change this behavior by using filters.

## Template Overview
### Default
Simple but effective template with one column. Ideal for sidebars or small blocks on site pages. Support two layouts: padded and standart. **Padded** means that around content of template will be 1em of padding. Standart layout don't have paddings around.

### Bootstrap 3.x
Powerful template for sites that builted with Bootstrap 3. This template have six variables. Let's take a closer look.
* **container-type** is a css class of wrapping div. You can select `container` or `container-fluid` (for responsive). Please note that `container` is unsuitable for sidebars.
* **ordering-type** is a order of matrix output. In bootstrap, we have rows and cols, i.e. a kind of associative array. With horizontal ordering type output order "left to right" will remain unchanged, but with transposed ordering type rows will be swapped with the columns and vice versa. Matrix will be **transposed**. Please note, that in `transposed` mode order of entries will be broken at lower resolutions.
* **column-count-x** is a count of columns for each of available breakpoints. If you select 3 columns, css class for every col will be `col-x-4` (because 12 will be divided on 3). Please note, that correct functioning depends of your mix of columns.

### Bootstrap 4.x
Similar with Bootstrap 3.x template, but for new version of the framework.

### Waterfall
Flexible multi-column template.

## Options
There is some built-in options for more flexibility, that can be used in shortcodes or widgets.
* **show_page_thumbnail** allows you enable or disable displaying thumbnail of entry. If you don't want see page thumbnail in widget or shortcode, just uncheck checkbox (for widget) or type `show_page_thumbnail="false"` (for shortcode). Default - **true**.
* **show_page_content** allows you enable or disable displaying content of entry. Default - **true**.
* **show_page_title** allows you enable or disable displaying title of entry. Default - **true**.
* **linked_page_title** allows you enable or disable linking title of entry. In other words, title will be link to full article. Default - **false**.
* **linked_page_thumbnail** allows you enable or disable linking thumbnail of entry. In other words, thumbnail will be link to full article. Default - **false**.
* **order** allows you set entries order. All posts and pages will be sorted by date, and you can choose a direction - by ascending or by descending. Sorting by descending is a default behaviour.
* **template** allows you choose layout which will look as you want. In shortcode just type full name of your template without extension, for example `default` or `tpc-your-template-name`.
* **posts** allows you choose posts what you want to display. In widget, all what you need is just check desired posts, but in shortcode you need write ids of posts manually. F.e., `posts="12,4,63"`.

Please note: some of these options is just flags that depends of used template. This means that if you used your own template and set parameter `show_page_content`, don't forget add condition in your template file. Without condition content will be appear in any case. All built-in templates **have** support for all options. 

Independent options (depends of plugin) is: `order`, `template`, `posts`.

**Also note** that in shortcode you should always specify the desired options explicitly. This means that if you do not write `show_page_content="true"`, the value of this option will be considered false, regardless of its default value.

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
* **$template_variables** - Array with template-level variables.
* **$caller** - A special flag that indicates where the template was called: from the widget or shortcode. May have values `widget` or `shortcode`.

## Creating custom templates
By default plugin will be search custom templates in a folder named "templates" in your theme. For create the your one just create a new file with name like `tpc-{template-name}.php`, where `template-name` - desired name of your template in "templates" directory. Every template should be named by that mask! Then put in created file template code. Here you can see simplest example:
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

## Filters
### tpc_config_array
*Parameters:* `$config` Array Store itself loaded config associative array.
This filter allows change config before its using, if config was loaded correctly.

### tpc_get_template_path
*Parameters:* `$template_path` String Store itself full calculated path to template file.
Allows change path to template file, and therefore change template in admin-part or entire site.
This filter is universal and applying in all places where plugin getting path to template file.

### tpc_get_admin_template
*Parameters:* `$template_title` String Title of the template for admin-part.
Allows replace template for admin-part on your one. Actual for widget and shortcode UI.

### tpc_render_template
*Parameters:* `$content` String Loaded content of the template.
Allows change content of rendered template just before it's display. For example, you can change some separate tags via regexp. This filter is universal and works great in admin-part and entire site.

### tpc_post_types_operator
*Parameters:* `$operator` String Comparsion operator for function `get_post_types`. Value can be OR or AND.
Allows change comparsion operator for function `get_post_types`. Using in admin-part for getting list of availaibe post types.

### tpc_post_types_args
*Parameters:* `$args` Array Array with attributes for function `get_post_types`.
Filter for changing list of attributes for post types query. With help of this filter you can, f.e., exclude some types from final result. Using in admin-part for getting list of available post types.

### tpc_post_types
*Parameters:* `$types` Array Array with received post types.
Allows change final result with post types after its have been received.

### tpc_post_params
*Parameters:* `$params` Array Associative array with attributes for function `get_posts`.
One of key filters, using in function `getPosts()`. Allows filter input array for getting list of entries and subsequent output of this list in template. This filter is universal, and using in admin-part and in entire site.

### tpc_prepared_posts
*Parameters:* `$prepared_posts` Array Associative array with result of function `get_posts`.
One of key filters, using in function `getPosts()`. Allows filter entries that was received before. The `$prepared_posts` parameter represents either one-dimensional array that stores list of `WP_Post` objects; or two-dimensional array that stores entries which divided by its post type. This filter is universal, and using in admin-part and in entire site.

### tpc_get_template_variable
*Parameters:* `$variable` Array Associative array with currently handling template-level variable.
		   `$template` String Name of the template from which are extracted variables.
Allows filter template-level variables at the stage of variables extraction from template. The `$variable` parameter represent an array with some elements that set in config at address `system.template-variables.mask`.

### tpc_get_template_variables
*Parameters:* `$variables` Array One-dimensional array with extracted and handled template-level variables.
		   `$template` String Name of the template from which are extracted variables.
Allows filter template-level variables after extracting and handling.

### tpc_get_params
*Parameters:* `$params` Array Associative array with parameters that will be accessible in template.
One of key filters, using in function `getParams()`. Allows filter accessible in template parameters and vaeiables. For example, you can change `$caller`, `$instance` or any another parameter.

### tpc_get_admin_params
*Parameters:* `$params` Array Associative array with parameters that will be accessible in admin template.
Allows filter accessible in admin template (widget or shortcode UI) parameters and variables.

### tpc_page_variables_raw
*Parameters:* `$query_string` String Page-level variables in url-encoded format.
		   `$entry_id` Int ID of current page for which we extract variables.
This filter allows change unhandled string with page-level variables just before parse of it.

### tpc_page_variables
*Parameters:* `$page_variables` Array One-dimensional array with all handled page-level variables.
		   `$entry_id` Int ID of current page for which we extract variables.
Allows change extracted and handled variables just before passing it in the template.

### tpc_template_directories
*Parameters:* `$directories` Array One-dimensional array with path to the directories with templates.
Allows modify list of the directories where can be template files (and custom too). Using in admin-part.

### tpc_get_templates
*Parameters:* `$templates` Array One-dimensional array with names of templates.
Allows manage list of finded templates. Using in admin-part.

### tpc_template_name
*Parameters:* `$template` String Template name
Allows modify template in widget just before getting path to template and render of it.