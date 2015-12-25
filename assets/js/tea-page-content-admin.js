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
	});

})(jQuery);