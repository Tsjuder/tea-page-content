(function($) {
	"use strict";

	$(document).ready(function() {
		$('#tpc-deprecated-notice .notice-dismiss').one('click', function() {
			jQuery.post(ajaxurl, {
				'action': 'set_notice_seen',
				'version': '1.2'
			}, function(response) {});
		});
	});

})(jQuery);