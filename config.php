<?php
/**
 * @package Tea Page Content
 * @version 1.2.0
 */

return array(
	// Predefined default values, e.g. default parameters
	'defaults' => array(
		'widget' => array(
			'title' => '',
			'posts' => '',
			'show_page_thumbnail' => true,
			'show_page_content' => true,
			'show_page_title' => true,
			'linked_page_title' => false,
			'linked_page_thumbnail' => false,
			'template' => 'default',
			'order' => 'desc',
			'per-page' => -1,

			'caller' => 'widget',
		),
		'shortcode' => array(
			'title' => '',
			'posts' => '',
			'id' => '', // @deprecated since 1.1
			'template' => 'default',
			'show_page_thumbnail' => true,
			'show_page_content' => true,
			'show_page_title' => true,
			'linked_page_title' => false,
			'linked_page_thumbnail' => false,
			'order' => 'desc',

			'caller' => 'shortcode',
		),
		'post-types' => array(
			'public' => true
		),
		'template-variables' => array(
			'type' => 'text',
			'defaults' => '',
		),
		'page-variables' => array(
			'page_title' => array( // @todo include {prefix} placeholder for DRY
				'type' => 'text',
				'title' => __('Title', 'tea-page-content')
			),
			'page_content' => array(
				'type' => 'textarea',
				'title' => __('Content', 'tea-page-content')
			),
			'page_thumbnail' => array(
				'type' => 'media',
				'title' => __('Thumbnail', 'tea-page-content')
			)
		)
	),

	// Predefined system values, e.g. logical operators, needly constants or system paths
	'system' => array(
		'posts' => array(
			'types-operator' => 'or'
		),
		'predefined-templates' => array(
			// @deprecated default-padded template, since 1.1
			'default', 'default-padded', 'bootstrap-3'
		),
		'template-variables' => array(
			'mask' => array(
				'name', 'type', 'defaults'
			),
		),
		'page-variables' => array(
			'prefix' => 'page_'
		),
		'template-directories' => array(
			'plugin' => TEA_PAGE_CONTENT_PATH . '/templates/',
			'theme' => get_template_directory() . '/templates/'
		),
		'versions' => array(
			'plugin' => '1.2.0',
			'scripts' => '1.2',
			'styles' => '1.2'
		)
	)
);