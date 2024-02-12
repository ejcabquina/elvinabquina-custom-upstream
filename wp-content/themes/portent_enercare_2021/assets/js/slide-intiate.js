"use strict";

document.addEventListener("DOMContentLoaded", () => {
	const menu = new MmenuLight(
		document.querySelector("#slider-menu"),
		"(max-width: 1023px)"
	);

	const body = document.querySelector("body");

	const navigator = menu.navigation();
	const drawer = menu.offcanvas();

	const openerFunction = () => {

		if (body.classList.contains("mm-ocd-opened")) {
			jQuery('.menu-toggle, .nav-menu').removeClass('active');
			drawer.close();
		} else {
			/**
			 * LoveDeep be raging clickin on the menu
			 * Putting in a limited so that he can't do that
			 */
			jQuery('.search-toggle, .header-search').removeClass('active');
			jQuery('.menu-toggle, .nav-menu').addClass('active');
			drawer.open();
		}
	};

	document
		.querySelector("#slider-menu-toggle")
		.addEventListener("click", openerFunction );
});
