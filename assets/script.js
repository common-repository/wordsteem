$( document ).ready(function() {

	updateWordSteemTagPreview();
	updateWordSteemTitlePreview();
	updateWordSteemLinkPreview();
	updateWordSteemSteemitIconPreview();
	updateCategoryPreview();

  $('#title').on('input', updateWordSteemTitlePreview);

  $('#wordsteem_tags').on('input', updateWordSteemTagPreview);

	$('#wordsteem-permalink').on('input', updateWordSteemLinkPreview);

	$('#wordsteem_reward').change(updateWordSteemSteemitIconPreview);

	function updateCategoryPreview() {
		var category = $('#wordsteem_tags').val().replace(/ .*/,'');

		if (category && category.trim() !== '') {
			$('#wordsteem-live-preview-category').html(category);
		} else {
			$('#wordsteem-live-preview-category').html('wordsteem');
		}
	}
	
	function updateWordSteemSteemitIconPreview() {
		var value = $('#wordsteem_reward').val();

		if (parseInt(value) !== 100) {
			$('.wordsteem-live-preview__title-icon').hide();
		} else {
			$('.wordsteem-live-preview__title-icon').show();
		}
	}

  function updateWordSteemTitlePreview() {
		if ($('#title').val().trim() === '') {
			$('#wordsteem-live-preview-title').html('<span class="wordsteem-text-greyed-out">Enter post title</span>');
		} else {
			$('#wordsteem-live-preview-title').html($('#title').val());
		}

  	$('#wordsteem-permalink').val(getSlugified($('#title').val()));
  	updateWordSteemLinkPreview();
  }

  function updateWordSteemTagPreview() {
		var tagsValue = $('#wordsteem_tags').val();

  	tagsValue = tagsValue.split(' ');

  	var tagsHtml = '';

  	for (var tagIndex = 0; tagIndex < tagsValue.length; tagIndex++) {
  		if (tagsValue[tagIndex].trim() !== '') {
  			tagsHtml += '<div class="wordsteem-live-preview__tag">' + tagsValue[tagIndex] + '</div>';
  		}
		}
	
		updateCategoryPreview();
  	$('#wordsteem-live-preview-tags').html(tagsHtml);
	}

	function updateWordSteemLinkPreview() {
		var permalink = getSlugified($('#wordsteem-permalink').val());

		var steemitLink = 'https://steemit.com/@' 
			+ $('#wordsteem-username-hidden').text() + '/' 
			+ permalink;

		$('#wordsteem-live-preview-link').html(steemitLink);
	}

	function getSlugified(text) {
		var slugifiedText = text.replace(/\s+/g, '-').toLowerCase();

		if (slugifiedText.substring(slugifiedText.length - 1) === '-') {
			slugifiedText = slugifiedText.substring(0, slugifiedText.length - 1);
		}

		return slugifiedText;
	}
});