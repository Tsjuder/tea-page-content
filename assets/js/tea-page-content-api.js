var TeaPageContent_API = {
	'storage': {
		set: function(name, value) {
			if(name === 'set' || name === 'get') {
				return false;
			}

			TeaPageContent_API.storage[name] = value;
		},
		get: function(name) {
			if(name === 'set' || name === 'get' || !(name in TeaPageContent_API.storage)) {
				return false;
			}

			return TeaPageContent_API.storage[name];
		},
	},

	'handlers': {
		'modals': {
			'tpc-call-shortcode-modal' : function(e, $this) {
				var $dialog = TeaPageContent_API.storage.get('dialog');

				if(!$dialog) {
					return false;
				}

				var title = 'Insert Tea Page Content Shortcode';

				$dialog.dialog('option', 'title', title);
				$dialog.dialog('option', 'width', 800);
				$dialog.dialog('open');
			},

			'tpc-call-item-options-modal': function(e, $this) {
				var $dialog = TeaPageContent_API.storage.get('dialog');

				if(!$dialog) {
					return false;
				}

				var $target;
				var data;

				var id = $this.attr('data-id');
				var target = $this.attr('data-target');
				var title = $this.attr('data-title');
				var item = $this.attr('data-item');

				if(target) $target = jQuery('#' + target);
				
				if($target.length) {
					$dialog.attr('data-target', target);

					// Parse request uri. In data array will be arrays with 2 elems.
					// Index 0 is title of param, index 1 is value.
					var encoded = $target.val().replace(/\+/g, '%20');

					data = jQuery.map(encoded.split('&'), function(elem) {
						var splitted = elem.split('=');

						elem = {
							'title': decodeURIComponent(splitted[0]),
							'value': decodeURIComponent(splitted[1])
						}

						return elem;
					});

					data['data-thumbnail-url'] = $target.attr('data-thumbnail-url');

					$dialog.find('input, textarea').each(function() {
						var $input = jQuery(this);
						var name = $input.attr('name');

						var exists = jQuery.grep(data, function(elem, index) {
							var title = elem.title;

							if(name === title && elem.value) return true;

							return false;
						});

						// If in data exists field with same title,
						// we can fill current input with value of finded field
						if(exists.length) {
							$input.val(exists[0].value);

							switch(name) {
								case 'page_thumbnail':
									var $mediaElement = $input.closest('.tpc-modal-media-element');
									var previewArea = $mediaElement.attr('data-preview-area');

									if('data-thumbnail-url' in data) {
										jQuery('#' + previewArea).css('background-image', 'url(' + data['data-thumbnail-url'] + ')');

										if($input.attr('data-meaning')) {
											var requiredTarget = $input.attr('data-meaning');

											$input.attr(requiredTarget, data['data-thumbnail-url']);
										}
									}

									$mediaElement.removeClass('is-empty');
								break;
							}
						}
					});

					if(item) {
						$dialog.attr('data-item', item);
					}

					$dialog.dialog('option', 'title', title);
					$dialog.dialog('open');
				}
			}
		}
	},

	'listeners': {
		'accordeon_click': function() {
			var $this = jQuery(this);
			var $target = $this.next();
			
			if($this.hasClass('opened')) {
				$this.removeClass('opened');
			} else {
				$this.addClass('opened');
			}

			$target.slideToggle('fast');
		},

		'template_list_change': function() {
			var $this = jQuery(this);
			var $variablesArea = jQuery('#' + $this.attr('data-variables-area'));
			var $preloader = $this.closest('.tpc-preloader');

			$preloader.removeClass('is-hidden').addClass('is-loading');

			if(jQuery.trim($variablesArea.html())) {
				$variablesArea.slideUp('fast');
			} else {
				$variablesArea.hide();
			}

			var data = {
				'action': 'get_template_variables',
				'template': $this.val(),
				'mask': $variablesArea.attr('data-mask-name')
			};

			jQuery.post(ajaxurl, data, function(response) {
				if(jQuery.trim(response)) {
					$variablesArea.html(response).slideDown('fast');	
				}
				
				$preloader.removeClass('is-loading');

				setTimeout(function() {
					$preloader.addClass('is-hidden');
				}, 500);
			});
		},

		'call_modal': function(e) {
			var $this= jQuery(this);

			var modal = $this.attr('data-modal');

			var $dialog = modal ? TeaPageContent_API.storage.get('dialog-' + modal) : null;

			if(!modal || !$dialog) {
				return false;
			}

			TeaPageContent_API.storage.set('dialog', $dialog);

			if(modal in TeaPageContent_API.handlers.modals) {
				TeaPageContent_API.handlers.modals[modal](e, $this);
			}

			e.preventDefault();

			return false;
		},

		'modal_overlay_click': function(e) {
			var $dialog = TeaPageContent_API.storage.get('dialog');

			$dialog.dialog('close');

			//TeaPageContent_API.storage.set('dialog', null);

			e.preventDefault();
		},

		'dialog_ok_button_click': function() {
			var $dialog = TeaPageContent_API.storage.get('dialog');

			var target = $dialog.attr('data-target');
			var item = $dialog.attr('data-item');
			
			var $targetObject = jQuery('#' + target);
			var $item;

			if(!item) {
				$item = $dialog.parent();
			} else {
				$item = jQuery('#' + item);
			}

			$item.removeClass('empty-item');
			$item.addClass('unsaved-item');

			if($targetObject.length) {
				var $requireds = $dialog.find('[data-meaning]');

				if($requireds.length) {
					$requireds.each(function(i) {
						var $required = jQuery(this);
						var requiredTarget = $required.attr('data-meaning');

						var requiredValue = $required.attr(requiredTarget);

						$targetObject.attr(requiredTarget, requiredValue);
					});
				}

				$targetObject.val($dialog.serialize());
			}

			jQuery(this).dialog('close');
		},

		'dialog_insert_button_click': function(e) {

			var $dialog = jQuery(this);
			var $button = jQuery(e.target);

			if(typeof send_to_editor !== 'function') {
				return false;
			}

			var $preloader = $dialog;

			if(!$dialog.hasClass('tpc-preloader')) {
				$preloader = $dialog.find('.tpc-preloader');
			}

			if($preloader && $preloader.length) {
				$preloader.removeClass('is-hidden').addClass('is-loading');
			}

			$button.prop('disabled', true);

			var data = {
				'action': 'generate_shortcode',
				'data': $dialog.serialize(),
			};

			jQuery.post(ajaxurl, data, function(response) {
				if(jQuery.trim(response)) {
					send_to_editor(response);
				}
				
				if($preloader && $preloader.length) {
					$preloader.removeClass('is-loading');
				}

				setTimeout(function() {
					//send_to_editor('Nie wieder stress mit');

					$preloader.addClass('is-hidden');
					
					$button.removeProp('disabled');

					$dialog.dialog('close');
				}, 500);
			});

			
		},

		'dialog_cancel_button_click': function() {
			jQuery(this).dialog('close');
		},

		'media_open_button_click': function() {
			var $media = TeaPageContent_API.storage.get('media');

			$media.open();
		},

		'media_delete_button_click': function() {
			var $this = jQuery(this);
			var $dialog = TeaPageContent_API.storage.get('dialog');

			var $mediaElement = $dialog.find('.tpc-modal-media-element');
			var $mediaStorage = jQuery('#' + $mediaElement.attr('data-storage'));

			$mediaElement.find('#' + $mediaElement.attr('data-preview-area')).slideUp(100, function() {
				jQuery(this).removeProp('style').css('background-image', '');
				$mediaElement.addClass('is-empty');
			});

			$mediaStorage.val('');
			$mediaStorage.attr('data-thumbnail-url', '');
		}
	},

	'callbacks': {
		'dialog_on_close': function(event, ui) {
			var $dialog = TeaPageContent_API.storage.get('dialog');

			var $mediaElement = $dialog.find('.tpc-modal-media-element');

			$dialog.removeAttr('data-target').removeAttr('data-item');

			$dialog.find('textarea').removeProp('style');
			$dialog.find('input[type=hidden].is-resetable').val('');
			
			$mediaElement.addClass('is-empty');
			$mediaElement.find('.tpc-modal-ui-preview').css('background-image', '');

			$dialog.dialog('option', 'title', '');

			$dialog.get(0).reset();
		},

		'media_on_select': function() {
			var $this = jQuery(this);
			var $media = TeaPageContent_API.storage.get('media');
			var $dialog = TeaPageContent_API.storage.get('dialog');

			var attachment = $media.state().get('selection').first().toJSON();

			var $mediaElement = $dialog.find('.tpc-modal-media-element');
			var $mediaStorage = jQuery('#' + $mediaElement.attr('data-storage'));

			$mediaElement.removeClass('is-empty');
			$mediaElement.find('#' + $mediaElement.attr('data-preview-area')).css('background-image', 'url(' + attachment.url + ')');

			$mediaStorage.val(attachment.id);
			$mediaStorage.attr('data-thumbnail-url', attachment.url);
		},
	}
};