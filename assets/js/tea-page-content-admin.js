(function($) {
	"use strict";

	var API = TeaPageContent_API;

	var _buttons = {
		'ok': {
			'title': 'OK'
		},
		'insert': {
			'title': 'Insert'
		}
	};

	$(document).ready(function() {

		var $document = $(this);
		var $widgets_area = $('#widgets-right');

		// Set to API some system objects
		API.storage.set('document', $document);
		API.storage.set('widgets-area', $widgets_area);

		// tpc-call-shortcode-modal
		// tpc-call-item-options-modal

		//$('.tpc-call-modal-button[data-modal]').each();
// 
		$document.on('click', '.tpc-call-modal-button[data-modal]', function(e) {
			var $this = $(this);
			var modal = $this.attr('data-modal');

			var modal_button = $this.attr('data-button');
			
			if(!modal_button) {
				modal_button = 'ok';
			}

			var $dialog = modal ? $('#' + modal) : null;

			if($dialog && $dialog.length) {
				var params = {
					'autoOpen': false,
					'draggable': false,
					'hide': 250,
					'show': 250,
					'modal': true,
					'resizable': false,
					'closeText': '',
					'buttons': [
						{
							text: _buttons[modal_button].title,
							class:'button button-primary',
							click: API.listeners['dialog_' + modal_button + '_button_click']
						}, {
							text: 'Cancel',
							class:'button',
							click: API.listeners.dialog_cancel_button_click
						}
					],
					'close': API.callbacks.dialog_on_close
				};

				API.storage.set('dialog-' + modal, $dialog);

				$dialog.dialog(params);

				TeaPageContent_API.storage.set('dialog', $dialog);

				if(modal in TeaPageContent_API.handlers.modals) {
					TeaPageContent_API.handlers.modals[modal](e, $this);
				}
			}

			e.preventDefault();
		});

		// Exit if widgets area is empty
		if(!$widgets_area || !$document) {
			return false;
		}

		// Now, set up the listeners for events
		// Listeners for accordeons and widget UI
		$document.on('click', '.tpc-accordeon-top', API.listeners.accordeon_click);
		$document.on('change', '.tpc-template-list', API.listeners.template_list_change);

		// Listeners for modal window UI (outer part)
		// $widgets_area.on('click', '.tpc-call-item-options-modal', API.listeners.call_page_variables_dialog_click);
		$document.on('click', '.ui-widget-overlay', API.listeners.modal_overlay_click);

		// Listeners for media UI
		if (typeof wp !== 'undefined' && wp.media && wp.media.editor) {
			API.storage.set('media', wp.media({
				multiple: false
			}));

			var $media = API.storage.get('media');

			// On select attachment callback
			$media.on('select', API.callbacks.media_on_select);

			// Open media box
			$document.on('click', 'button[data-target="media-open"]', API.listeners.media_open_button_click);

			// Delete attachment from dialog
			$document.on('click', 'button[data-target="media-delete"]', API.listeners.media_delete_button_click);
		}
		
	});

})(jQuery);