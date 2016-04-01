(function($) {
	"use strict";

	$(document).ready(function() {
		$('#widgets-right').on('click', '.tpc-accordeon-top', function() {
			var $this = $(this);
			var $target = $this.next();
			
			if($this.hasClass('opened')) {
				$this.removeClass('opened');
			} else {
				$this.addClass('opened');
			}

			$target.slideToggle('fast');
		});

		$('#widgets-right').on('change', '.tpc-template-list', function() {
			var $this = $(this);
			var $variablesArea = $('#' + $this.attr('data-variables-area'));
			var $preloader = $this.closest('.tpc-preloader');

			$preloader.removeClass('is-hidden').addClass('is-loading');

			if($.trim($variablesArea.html())) {
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
				if($.trim(response)) {
					$variablesArea.html(response).slideDown('fast');	
				}
				
				$preloader.removeClass('is-loading');

				setTimeout(function() {
					$preloader.addClass('is-hidden');
				}, 500);
			});
		});
	});

})(jQuery);