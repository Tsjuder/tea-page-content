<?php

/**
 * Keys of this array is config paths. All this paths
 * will be visible to modify in Settings Page.
 */
return [
    'system.settings.include-css' => [
        'type' => 'switch',
        'structure' => 'select',
        'filter' => 'string',

        'label' => __('Enable plugin\'s CSS?', 'tea-page-content'),
        'description' => __('You can exclude css of plugin from output if you do not use default templates.', 'tea-page-content'),
    ]
];