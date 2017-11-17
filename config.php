<?php

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
            'orderby' => 'date',

            'caller' => 'widget',
        ),

        'shortcode' => array(
            'title' => '',
            'posts' => '',
            'template' => 'default',
            'show_page_thumbnail' => true,
            'show_page_content' => true,
            'show_page_title' => true,
            'linked_page_title' => false,
            'linked_page_thumbnail' => false,

            'order' => 'desc',
            'orderby' => 'date',

            'caller' => 'shortcode',
        ),

        'post-types' => array(
            'public' => true,
        ),

        'posts' => array(
            'post_status' => 'publish', // may be an array too
            'has_password' => false,
            'posts_per_page' => -1,
            'order' => 'desc',
            'orderby' => 'date',
            'post__in' => [],
        ),

        'template-variables' => array(
            'type' => 'text',
            'defaults' => '',
        ),
        'page-variables' => array(
            'page_title' => array(
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
            ),
        ),

        'thumbnails' => [
            'size' => 'post-thumbnail',
        ],

        'templates' => [
            'admin' => [
                'partials' => [
                    'template-variables' => [
                        'name' => 'template-variables',
                        'path' => '{admin}/partials/',
                    ],
                ],
                'modals' => [
                    'page-variables' => [
                        'name' => 'page-variables',
                        'path' => '{admin}/modals/',
                    ],
                    'insert-shortcode' => [
                        'name' => 'insert-shortcode',
                        'path' => '{admin}/modals/',
                    ],
                ],
                'pages' => [
                    'settings' => [
                        'name' => 'settings',
                        'path' => '{admin}/pages/',
                    ]
                ],
                'modules' => [
                    'widget' => [
                        'name' => 'widget',
                        'path' => '{admin}/modules/',
                    ]
                ],
            ],

            'client' => [
                'default-template' => 'default',
                'widget' => [
                    'name' => 'widget',
                    'path' => '{client}/modules/',
                ],
            ],
        ],
    ),

    // Predefined system values, e.g. logical operators, needly constants or system paths
    'system' => array(
        'posts' => array(
            'types-operator' => 'or',
        ),
        'predefined-templates' => array(
            'default', 'bootstrap-3', 'bootstrap-4', 'waterfall'
        ),
        'template-variables' => array(
            'mask' => [
                'structure' => [
                    'name', 'type', 'defaults'
                ],
                'placeholder' => '{mask}'
            ]
        ),
        'page-variables' => array(
            'prefix' => 'page_'
        ),
        'template-directories' => array(
            'plugin' => [
                'placeholder' => '{plugin}',
                'path' => \TeaPageContent\PLUGIN_PATH,
            ],

            'admin' => [
                'placeholder' => '{admin}',
                'path' => \TeaPageContent\PLUGIN_PATH . '/templates/admin',
            ],
            'client' => [
                'placeholder' => '{client}',
                'path' => \TeaPageContent\PLUGIN_PATH . '/templates/client',
            ],
            'layouts' => [
                'placeholder' => '{layouts}',
                'path' => \TeaPageContent\PLUGIN_PATH . '/templates/client/layouts',
            ],

            'theme' => [
                'placeholder' => '{theme}',
                'path' => get_stylesheet_directory() . '/templates',
            ],
        ),
        'versions' => array(
            'plugin' => '1.3.1',
            'scripts' => '1.2.3',
            'styles' => '1.2.3',
        ),

        'settings' => array(
            'include-css' => true,
        ),
    )
);