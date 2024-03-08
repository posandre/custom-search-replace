jQuery(document).ready(function ($) {
	'use strict';

	$('.custom-search-replace-button').on('click', function (event) {
		const button = $(this);
		const keyword = $('.custom-search-replace-input--search');
		const replaceText = button.prev('.custom-search-replace-input--replace');

		const formData = {
			action: button.data('action'),
			keyword: keyword.val(),
			replace_text: replaceText ? replaceText.val() : false
		};

		jQuery.ajax({
			method: 'POST',
			dataType: 'json',
			url: ajaxurl,
			data: formData,
			beforeSend: function () {
				button.after( '<img class="custom-search-replace-spinner" src="/wp-admin/images/loading.gif" />' );
			},
			complete: function () {
				$('.custom-search-replace-spinner').remove();
			},
			error: function (xhr) {
				const errorMessage = xhr.responseJSON.message;

				errorMessageObject.text(errorMessage);
			},
			success: function (responseData) {
				if (responseData.success) {
					$('#search-results').html(responseData.data);

					$('.custom-search-replace-input--replace').val('');
				}
			}
		});

		event.preventDefault();
	});
});
