<?php
/**
 * @package Tea Page Content
 * @version 1.1.0
 */

$config = array(
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
			'per-page' => -1
		),
		'shortcode' => array(
			'posts' => '',
			'id' => '', // @deprecated since 1.1
			'template' => 'default',
			'show_page_thumbnail' => true,
			'show_page_content' => true,
			'show_page_title' => true,
			'linked_page_title' => false,
			'linked_page_thumbnail' => false,
			'order' => 'desc',
		),
		'post-types' => array(
			'public' => true
		),
		'template-variables' => array(
			'type' => 'text',
			'defaults' => '',
		),
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
		'template-directories' => array(
			'plugin' => TEA_PAGE_CONTENT_PATH . '/templates/',
			'theme' => get_template_directory() . '/templates/'
		),
		'versions' => array(
			'plugin' => '1.1.0',
			'scripts' => '1.1',
			'styles' => '1.1'
		)
	)
);