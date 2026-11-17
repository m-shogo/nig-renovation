(function () {
	var root = document.querySelector(".top_banner");
	if (!root || typeof Swiper === "undefined") {
		return;
	}
	var count = root.querySelectorAll(".swiper-slide").length;
	if (!count) {
		return;
	}
	new Swiper(root, {
		slidesPerView: "auto",
		centeredSlides: true,
		spaceBetween: 16,
		speed: 600,
		loop: count > 1,
		autoplay: {
			delay: 8000,
			disableOnInteraction: false
		},
		pagination: {
			el: root.querySelector(".swiper-pagination"),
			clickable: true
		},
		breakpoints: {
			1100: {
				spaceBetween: 40
			}
		}
	});
})();
