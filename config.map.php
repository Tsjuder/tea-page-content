<?php
/**
 * @package Tea Page Content
 * @version 1.2.3
 */

return array(
	'system.settings.include-css' => array(
		'type' => 'switch',
		'structure' => 'select',
		'filter' => 'string',

		'label' => __('Enable plugin\'s CSS?', 'tea-page-content'),
		'description' => __('You can exclude css of plugin from output if you do not use default templates.', 'tea-page-content'),
	)
);