// need auth
$('.need-auth').on('click', function() {
	if (+$('body').data('visitor'))
		return true; // logged in.

	location.href = '/auth/';
	return false;
});


// post idea
$('#modal_post').on('show', function() {
	$('.post-summary').val($('.navbar-input').val());
});

$('.idea-post').on('submit', function() {
	var $target = $(this)
		,url = $target.attr('action')
		,params = $target.serializeArray();

	$('.btn', $target).addClass('disabled');

	$.post(url, params, function(data) {
		if (data.status == 200)
			location.reload();
	}, 'json');
	return false;
});

// like idea
$('.idea-like').on('click', '.btn', function() {
	var $target = $(this)
		,$counter = $('cite', $target)
		,url = $target.attr('href');

	$target.addClass('disabled'); // disable button
	$counter.text(+$counter.text() + 1); // counter +1

	$.post(url, function(data) {
		if (data.status != 200)
			return $target.removeClass('disabled'); // enable button
		$counter.text(data.result);
	}, 'json');

	return false;
});