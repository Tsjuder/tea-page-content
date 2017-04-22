<?php

/**
 * Список зависимостей. Можно просто указать абсолютное имя класса,
 * с неймспейсами, перечислив в массиве все зависимости, которые должны
 * быть зарезольвены. В этом случае в указанный класс передастся список
 * аргументов в указанном порядке.
 * 
 * Можно объявить отложенную зависимость при помощи флага `delay`. 
 * В этом случае зависимость будет разрешена уже *после* создания класса,
 * когда конструктор отработает. Класс должен реализовывать интерфейс IDelayedInhection
 * для того, чтобы можно было делать так.
 * 
 * Можно объявить ленивое разрешение зависимостей при помощи флага `lazy`.
 * В этом случае класс *не будет* создан: будут созданы лишь его зависимости.
 * Инъекция их может происходить, а может и не происходить, по желанию
 * разработчика. В общем случае инъекция реализуется в классе при помощи
 * функции inject_deps.
 */
return [
    // Lazy Dependencies
    'Main' => [
        'dependencies' => [
            'Main\\Modals' => 'Modals',
            'Main\\Registrator' => 'Registrator',
            'Main\\Assets' => 'Assets',
            'Main\\Callbacks' => 'Callbacks',
            'Main\\Settings' => 'Settings',
            'Main\\AdminElements' => 'AdminElements',
            'Main\\ConfigHandler' => 'ConfigHandler',
            'Main\\AdminNotices' => 'AdminNotices',
        ],

        'lazy' => true,
    ],
    'Modules\\Widget' => [
        'dependencies' => [
            'Modules\\Widget\\Extractor' => 'WidgetExtractor',
            'Modules\\Widget\\Renderer' => 'WidgetRenderer',
            'Modules\\Widget\\Combiner' => 'WidgetCombiner',
            'Modules\\Widget\\ParamsBuilder' => 'WidgetParamsBuilder',
            'Modules\\Widget\\InstanceBuilder' => 'WidgetInstanceBuilder',
            'Templates\\Renderer' => 'TemplatesRenderer',
        ],

        'lazy' => true,
    ],

    // Main
    'Main\\Modals' => [
        'Config\\Repository',
        'Templates\\Resolver',
        'Templates\\Renderer',
        'Entries\\Repository',
        'Templates\\Repository',
        'TemplateVariables\\Repository',
    ],
    'Main\\Registrator' => [
        'Modules\\Shortcode',
    ],
    'Main\\Assets' => [
        'Config\\Repository',
    ],
    'Main\\ShortcodeGenerator' => [
        'Config\\Repository',
        'TemplateVariables\\Repository',
        'PageVariables\\Decoder',
    ],
    'Main\\AdminNotices' => [
        'Config\\Repository',
        'Notices\\Manager',
    ],
    'Main\\Settings' => [
        'Config\\Repository',
        'Config\\Combiner',
        'Config\\Mapper',
        'Templates\\Resolver',
        'Templates\\Renderer',
    ],
    'Main\\AdminElements' => [
        'Main\\Settings',
    ],
    'Main\\ConfigHandler' => [
        'Config\\Repository',
        'Config\\Map\\Repository',
    ],
    'Main\\Callbacks' => [
        'Config\\Repository',
        'Main\\ShortcodeGenerator',
        'Notices\\Manager',
        'TemplateVariables\\Repository',
        'Templates\\Resolver',
        'Templates\\Renderer',
    ],

    // Widget
    'Modules\\Widget\\InstanceBuilder' => [
        'TemplateVariables\\Repository',
        'Modules\\Widget\\Appender',
        'Config\\Repository',
    ],
    'Modules\\Widget\\ParamsBuilder' => [
        'Modules\\Widget\\Extractor',
        'Modules\\Widget\\Appender',
        'Templates\\Resolver',
        'Templates\\Renderer',
    ],
    'Modules\\Widget\\Combiner' => [
        'Config\\Repository',
        'TemplateVariables\\Repository',
    ],
    'Modules\\Widget\\Appender' => [
        'PageVariables\\Decoder',
        
        'Modules\\Widget\\Extractor',
        
        'Templates\\Repository',
        'Entries\\Repository',
    ],
    'Modules\\Widget\\Extractor' => [
        'Modules\\Widget\\TemplatesExtractor',
        'Modules\\Widget\\VariablesExtractor',
        'Modules\\Widget\\ParamsExtractor',
    ],
    'Modules\\Widget\\TemplatesExtractor' => [
        'Config\\Repository',
        'Templates\\Renderer',
        'Templates\\Resolver',
    ],
    'Modules\\Widget\\ParamsExtractor' => [
        'Config\\Repository',
        'Params\\Repository',
    ],
    'Modules\\Widget\\VariablesExtractor' => [
        'Modules\\Widget\\TemplatesExtractor',
        'PageVariables\\Repository',
        'TemplateVariables\\Repository',
    ],
    'Modules\\Widget\\Renderer' => [
        'Config\\Repository',
        'Templates\\Renderer',
        'Templates\\Resolver',
    ],

    // Shortcode
    'Modules\\Shortcode' => [
        'Modules\\Shortcode\\Builder',
        'Modules\\Shortcode\\Renderer',
    ],
    'Modules\\Shortcode\\Builder' => [
        'Config\\Repository',
        'Modules\\Shortcode\\Extractor',
        'Modules\\Shortcode\\Filtrator',
        'Modules\\Shortcode\\Combiner',
    ],
    'Modules\\Shortcode\\Filtrator' => [
        'Config\\Repository',
    ],
    'Modules\\Shortcode\\Extractor' => [
        'TemplateVariables\\Repository',
        'Entries\\Repository',
        'Params\\Repository',
    ],
    'Modules\\Shortcode\\Combiner' => [
        'PageVariables\\Combiner',
        'PageVariables\\Formatter',
    ],
    'Modules\\Shortcode\\Renderer' => [
        'Templates\\Resolver',
        'Templates\\Renderer',
    ],

    // Notices
    'Notices\\Manager' => [
        'Config\\Repository',
    ],

    // Config
    'Config\\Sanitizer' => [
        'Config\\Map\\Repository',
        'Config\\Filtrator',
    ],
    'Config\\Repository' => [
        'Config\\Loader',
        'Config\\Mapper',
        'Config\\Sanitizer',
        'Config\\Extractor',
    ],
    'Config\\Combiner' => [
        'Config\\Mapper',
        'Config\\Repository',
    ],
    'Config\\Mapper' => [
        'Config\\Map\\Repository',
    ],
    'Config\\Map\\Repository' => [
        'Config\\Map\\Loader',
    ],

    // Entries
    'Entries\\Repository' => [
        'Entries\\Params',
        'Entries\\Formatter',
    ],
    'Entries\\Formatter' => [
        'Entries\\Extractor',
    ],
    'Entries\\Params' => [
        'Config\\Repository',
        'Entries\\Extractor',
        'PostTypes\\Repository',
    ],

    // PostTypes
    'PostTypes\\Repository' => [
        'Config\\Repository',
    ],

    // PageVariables
    'PageVariables\\Repository' => [
        'PageVariables\\Extractor',
    ],
    'PageVariables\\Combiner' => [
        'Config\\Repository',
        'PageVariables\\Formatter',
        'PageVariables\\RulesActuator',
    ],
    'PageVariables\\Formatter' => [
        'Config\\Repository',
    ],
    'PageVariables\\RulesActuator' => [
        'Config\\Repository',
    ],
    'PageVariables\\Extractor' => [
        'PageVariables\\Decoder',
    ],
    'PageVariables\\Decoder' => [
        'PageVariables\\Builder',
    ],
    'PageVariables\\Builder' => [
        'PageVariables\\Formatter',
        'PageVariables\\RulesActuator',
    ],


    // Params
    'Params\\Repository' => [
        'Params\\Builder',
    ],
    'Params\\Extractor' => [
        'Config\\Repository',
        'PageVariables\\Repository',
        'PageVariables\\Formatter',
    ],
    'Params\\Builder' => [
        'Entries\\Repository',
        'Params\\Extractor',
    ],

    // Directories
    'Directories\\Repository' => [
        'Config\\Repository',
    ],

    // Templates
    'Templates\\Combiner' => [
        'Config\\Repository',
    ],
    'Templates\\Repository' => [
        'Templates\\Extractor',
    ],
    'Templates\\Extractor' => [
        'Directories\\Repository',
        'Templates\\Reader',
        'Templates\\Combiner',
    ],
    'Templates\\Reader' => [
        'Templates\\Filtrator',
    ],
    'Templates\\Resolver' => [
        'Config\\Repository',
    ],

    // TemplateVariables
    'TemplateVariables\\Repository' => [
        'TemplateVariables\\Extractor',
    ],
    'TemplateVariables\\Extractor' => [
        'Templates\\Resolver',
        'TemplateVariables\\Parser',
    ],
    'TemplateVariables\\Parser' => [
        'TemplateVariables\\Filtrator',
    ],
    'TemplateVariables\\Filtrator' => [
        'Config\\Repository',
    ],
];