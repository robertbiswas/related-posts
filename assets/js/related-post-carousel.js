;document.addEventListener('DOMContentLoaded', function () {
	var elms = document.getElementsByClassName('related-posts-carousel');
	for (var i = 0; i < elms.length; i++) {
		new Splide(elms[i], {
			type: 'loop',
			pagination: true,
			arrows: false,
			perPage: 3,
			gap: '2em',
			autoplay: true,
			pauseOnHover: true,
			// focus: 'center',
			breakpoints: {
				968: {
					perPage: 2,
				},
				814: {
					perPage: 1,
				},
			}
		}).mount();
	}
});