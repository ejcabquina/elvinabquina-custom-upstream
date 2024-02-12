/**
 * Toggle Navigation Script
 */
let PortentToggleNav = function(){};
PortentToggleNav.prototype.menu = '';
PortentToggleNav.prototype.logo = null;
PortentToggleNav.prototype.cta = null;
//@todo let this get set externally.
PortentToggleNav.prototype.mobileWidth = 1023;
PortentToggleNav.prototype.keyModifierDown = false;
PortentToggleNav.prototype.init = function() {

	if(this.menu !== '') {
		/*
		Add a class for style tracking
		 */
		this.menu.classList.add('portent-toggle-nav');
		this.setupMobileToggle();
		this.setupStatusArea();
		this.setupBrandArea();
		this.navigationMenuToggle(this.menu);
		this.setupClickOutside();
	} else {
		console.log('Please set a menu property');
	}

	/**
	 * Global listener for the shift key as a modifier
	 */
	this.menu.addEventListener('keydown', function(event){
		if( event.keyCode === 16 ){
			this.keyModifierDown = true;
		}
	});

	this.menu.addEventListener('keyup', function(event){
		if( event.keyCode === 16 ){
			this.keyModifierDown = false;
		}
	});

};
PortentToggleNav.prototype.setupClickOutside = function() {
	/**
	 * Handle Closing the menu when a user clicks outside of the frame
	 */

		//THIS gets weird once we start to dive down into functions. To make sure we are calling the top level prototype when we ask for properties and functions we need to save top level this into a new variable.
	let _this = this;
	let navigationMenu = this.menu;
	document.addEventListener("click", function(event) {
		let isNavigationInClick = navigationMenu.contains(event.target);
		if(!isNavigationInClick) {
			_this.closeAllMenus(navigationMenu);
		}
	});
};

PortentToggleNav.prototype.setupBrandArea = function() {
	const _this = this;
	const brandAreaContainer = document.createElement('div');
	brandAreaContainer.classList.add('brand-area');

	if(this.logo) {
		const brandLogoElement = document.createElement('img');
		brandLogoElement.classList.add('brand-area__logo');
		brandLogoElement.src = this.logo;
		brandAreaContainer.appendChild(brandLogoElement);
	}

	if(this.cta) {
		const brandCTAElement = document.createElement('div');
		brandCTAElement.classList.add('brand-area__cta');
		brandCTAElement.innerHTML = this.cta;
		brandAreaContainer.appendChild(brandCTAElement);
	}

	this.menu.insertBefore(brandAreaContainer, this.menu.children[0]);
};

PortentToggleNav.prototype.statusButtonKeys = function(event){
	let el = this.menu.querySelector('[data-open=true]'),
		level = false;

	/**
	 * Check el if it is null then a sub menu is not open and we need to retarget the main menu body
	 */
	if(el === null) {
		el = this.menu.querySelector('ul');
		level = true;
	}

	if (event.keyCode === 38 || (event.keyCode === 9 && this.keyModifierDown)) {
		event.stopPropagation();
		event.preventDefault();
		this.findNextMenuLink(event, el, 'up',level);
	}

	if (event.keyCode === 40 || (event.keyCode === 9 && !this.keyModifierDown)) {
		event.stopPropagation();
		event.preventDefault();

		this.findNextMenuLink(event, el, 'down', level);
	}
};

PortentToggleNav.prototype.setupStatusArea = function() {
	const _this = this;
	const menuElement = this.menu;
	const statusAreaContainer = document.createElement('div');
	const statusAreaBackButton = document.createElement('button');
	const statusAreaCloseButton = document.createElement('button');

	statusAreaContainer.setAttribute('aria-hidden', "true");
	statusAreaContainer.setAttribute('data-interface', 'statusArea');

	statusAreaCloseButton.innerText = 'Close';
	statusAreaCloseButton.classList.add('mobile-close-btn');
	statusAreaBackButton.innerText = 'Back to main menu';
	statusAreaBackButton.classList.add('mobile-back-btn');

	statusAreaContainer.appendChild(statusAreaBackButton);
	statusAreaContainer.appendChild(statusAreaCloseButton);
	menuElement.insertBefore(statusAreaContainer, menuElement.children[0]);

	statusAreaBackButton.addEventListener("click", function(){_this.closeAllMenus(menuElement);});
	statusAreaCloseButton.addEventListener("click", function(){_this.closeMobileMenu(menuElement);});

	statusAreaBackButton.addEventListener('keydown', function(event) {
		_this.statusButtonKeys(event);
	});
	statusAreaCloseButton.addEventListener('keydown', function(event){
		_this.statusButtonKeys(event);
	});
};

PortentToggleNav.prototype.setupMobileToggle = function() {
	const _this = this;
	const menuElement = this.menu;

	let mobileMenuToggle;
	if(this.toggleButton) {
		mobileMenuToggle = this.toggleButton;
	} else {
		mobileMenuToggle.innerText = 'Menu';
		mobileMenuToggle = document.createElement('button');
		menuElement.parentElement.insertBefore(mobileMenuToggle, menuElement);
	}

	mobileMenuToggle.addEventListener("click", function(){_this.openMobileMenu(_this.menu);});
	mobileMenuToggle.setAttribute('aria-expanded', 'false');
};

/**
 * This is likely better handled through a dispatched event listener
 * @param menuState
 */
PortentToggleNav.prototype.setSubMenuStatus = function(menuState= false) {
	if(menuState) {
		this.menu.setAttribute('data-menu', 'open');
	} else {
		this.menu.removeAttribute('data-menu');
	}
};

PortentToggleNav.prototype.expandTopLevelItem = function(event){
	event.preventDefault();

	/**
	 * @todo This is a duplication of the opening menu logic. Condense this.
	 */
	if (event.target.parentNode.dataset.open === 'true') {
		/**
		 * If the same menu button we are clicking on is already visible then toggle it closed
		 * @type {string}
		 */
		event.target.dataset.open = 'false';
		event.target.setAttribute('aria-expanded', "false");
		this.setSubMenuStatus(false);
	} else {
		/**
		 * If the button we are clicking on is not open, reset all open menus before displaying the current option
		 */
		this.closeAllMenus(this.menu);
		event.target.parentNode.dataset.open = 'true';
		event.target.setAttribute('aria-expanded', "true");
		this.setSubMenuStatus(true);

		/**
		 * Focus The First Item
		 */
		this.focusFirstOption(event.target.parentNode);
	}
}

PortentToggleNav.prototype.navigationMenuToggle = function(navigationContainer) {
	let menuItems = navigationContainer.querySelectorAll('[data-children=true]');

	//THIS gets weird once we start to dive down into functions. To make sure we are calling the top level prototype when we ask for properties and functions we need to save top level this into a new variable.
	let _this = this;
	menuItems.forEach.call(menuItems, function(el, i){
		el.querySelector('button').addEventListener("click",  function(event){
			if (this.parentNode.dataset.open === 'true') {
				/**
				 * If the same menu button we are clicking on is already visible then toggle it closed
				 * @type {string}
				 */
				this.parentNode.dataset.open = 'false';
				this.setAttribute('aria-expanded', "false");
				_this.setSubMenuStatus();
			} else {
				/**
				 * If the button we are clicking on is not open, reset all open menus before displaying the current option
				 */
				_this.closeAllMenus(navigationContainer);
				this.parentNode.dataset.open = 'true';
				this.setAttribute('aria-expanded', "true");
				_this.setSubMenuStatus(true);
			}

			/**
			 * Focus The First Item
			 */
			_this.focusFirstOption(this.parentNode);

			return false;
		});

		el.querySelector('button').addEventListener("keydown", function(event) {

			if( window.outerWidth <= _this.mobileWidth ){
				if(event.keyCode ===  38 || (event.keyCode === 9 && this.keyModifierDown) ) {
					event.preventDefault();
					_this.findNextMenuLink(event, this.parentNode.parentNode, 'up', true);
				}

				if(event.keyCode ===  40 || (event.keyCode === 9 && !this.keyModifierDown) ) {
					event.preventDefault();
					_this.findNextMenuLink(event, this.parentNode.parentNode, 'down', true);
				}

				if(event.keyCode === 39 ) {
					_this.expandTopLevelItem(event);
				}
			} else {
				if([40,39].includes(event.keyCode)) {
					_this.expandTopLevelItem(event);
				}

				if([38].includes(event.keyCode)) {
					_this.closeAllMenus(navigationContainer);
				}
			}

			return false;
		});

		/**
		 * Event Listeners for ALL link elements within the current top level list item
		 * We are tracking all links within the top level container so that when links are buried in containers
		 * and out of sequence we can still locate the next logical link in the list.
		 */
		el.querySelectorAll('a').forEach( function(elem) {
			elem.addEventListener('keydown', function(event) {
				if( window.outerWidth <= _this.mobileWidth ) {
					if (event.keyCode === 38 || (event.keyCode === 9 && this.keyModifierDown)) {
						event.stopPropagation();
						event.preventDefault();
						_this.findNextMenuLink(event, el, 'up');
					}

					if (event.keyCode === 40 || (event.keyCode === 9 && !this.keyModifierDown)) {
						event.stopPropagation();
						event.preventDefault();
						_this.findNextMenuLink(event, el, 'down');
					}
				} else {
					if (event.keyCode === 38) {
						event.stopPropagation();
						event.preventDefault();
						_this.findNextMenuLink(event, el, 'up');
					}

					if (event.keyCode === 40) {
						event.stopPropagation();
						event.preventDefault();
						_this.findNextMenuLink(event, el, 'down');
					}
				}
			});

			elem.addEventListener('keyup', function(event) {
				//Close menu and focus on closest button when Escape is pressed on a menu item
				if(event.keyCode === 27 ){
					_this.closeAllMenus(navigationContainer);
					el.querySelector('button').focus();
				}
			});
		});
	});
};

/*
This function essentially serves as a keyboard trap for the current menu.
I don't love this at the moment. It feels like it is doing more work that it should
 */
PortentToggleNav.prototype.findNextMenuLink = function(event, topLevelParent, direction, topLevel) {
	let allLinks;

	if(!direction || typeof direction == 'undefined') {
		direction = 'down';
	}

	/*
	don't love this part but we need to know if the menu is at its highest level or not
	 */
	if(!topLevel) {
		topLevel = false;
	}

	if(window.outerWidth <= this.mobileWidth) {
		let mobileElements;

		/**
		 * Available elements are different depending on the level
		 */
		if (topLevel) {
			mobileElements = Array.from(this.menu.querySelectorAll('.mobile-close-btn'));
			allLinks = Array.from(topLevelParent.querySelectorAll(':scope > li > button, :scope > li > a'));
		} else {
			mobileElements = Array.from(this.menu.querySelectorAll('.mobile-back-btn, .mobile-close-btn'));
			allLinks = Array.from(topLevelParent.querySelectorAll('a'));
		}
		allLinks = [...mobileElements, ...allLinks];
	} else {
		allLinks = Array.from(topLevelParent.querySelectorAll('a'));
	}

	console.log(allLinks);
	let firstFocusableLink = allLinks[0];
	let lastFocusableLink = allLinks[allLinks.length - 1];
	let currentElement = document.activeElement;

	/**
	 * If the user has pressed up on the final focusable in the set, shift focus to the last
	 * It is important that we return if this statement is true so that we do not duplicate the action.
	 */
	if(currentElement === firstFocusableLink && direction === 'up' ) {
		lastFocusableLink.focus();
		return;
	}

	/**
	 * If the user has pressed down on the final focusable item in the list shift focus to the first item in the set
	 * It is important that we return if this statement is true so that we do not duplicate the action.
	 */
	if(currentElement === lastFocusableLink && direction === 'down' ) {
		firstFocusableLink.focus();
		return;
	}

	/**
	 * If a user has pressed the up or down arrow while a menu item is focused and neither is the first or last element
	 * then loop through the list until
	 */
	allLinks.forEach(function(link, index) {
		if(currentElement === link ) {
			if(direction ===  'down' ) {
				allLinks[index+1].focus();
			} else if(direction === 'up' ){
				allLinks[index-1].focus();
			}
		}
	});

	console.log(document.activeElement);
};

PortentToggleNav.prototype.closeAllMenus = function(navigationContainer) {
	let openMenus = navigationContainer.querySelectorAll('button[aria-expanded=true]');

	openMenus.forEach(function(elem) {
		elem.parentNode.dataset.open = 'false';
		elem.setAttribute('aria-expanded', "false");
	});

	if(openMenus.length > 0) {
		openMenus.item(0).focus();
	}
	this.setSubMenuStatus(false);
};

PortentToggleNav.prototype.setDocumentAttribute = function() {
	const bodyElement = document.querySelector('body');
	bodyElement.setAttribute('data-menu', 'open');
}

PortentToggleNav.prototype.removeDocumentAttribute = function() {
	let bodyElement = document.querySelector('body');
	bodyElement.removeAttribute('data-menu');
}

PortentToggleNav.prototype.openMobileMenu = function(menuElement) {

	menuElement.setAttribute('data-mobile', 'open');
	this.setDocumentAttribute();
	const firstMenuItem = this.menu.querySelector('.mobile-close-btn');
	firstMenuItem.focus();
};

PortentToggleNav.prototype.closeMobileMenu = function() {
	this.removeDocumentAttribute();
	this.closeAllMenus(this.menu);

	if(this.menu.getAttribute('data-mobile') === 'open' ) {
		this.menu.removeAttribute('data-mobile');
	}

	this.toggleButton.focus();

	this.setSubMenuStatus(false);
};

PortentToggleNav.prototype.focusFirstOption = function(topLevelItem) {
	let firstLink = topLevelItem.querySelector('.sub-menu a');
	firstLink.focus({preventScroll:true});
};

/**
 * Execute a function given a delay time
 *
 * @param {type} cb
 * @param {type} wait
 * @param {type} immediate
 * @returns {Function}
 */
PortentToggleNav.prototype.debouceKeys = function (cb, wait, immediate) {
	var timeout;
	return function() {
		var context = this, args = arguments;
		var later = function() {
			timeout = null;
			if (!immediate) cb.apply(context, args);
		};
		var callNow = immediate && !timeout;
		clearTimeout(timeout);
		timeout = setTimeout(later, wait);
		if (callNow) cb.apply(context, args);
	};
};

/**
 * Setup and init the toggle menu
 */
function setupToggleNav() {
	const primaryNavigation = new PortentToggleNav;
	primaryNavigation.menu = document.getElementById('slider-menu');
	primaryNavigation.toggleButton = document.getElementById('slider-menu-toggle');
	primaryNavigation.logo = 'https://www.enercare.ca/wp-content/uploads/2021/11/EC_LOGO_H_P_4C.svg';
	primaryNavigation.cta = "<div class=\"site-header__header-phone header-phone\"><span class=\"header-phone__cta\"><strong>Speak with an expert</strong></span><a class=\"header-phone__link cl-phone\" href=\"tel:1-855-642-8607\"><span class=\"screen-reader-text\">Click to call Enercare1-855-642-8607</span><svg xmlns=\"http://www.w3.org/2000/svg\" height=\"24px\" viewBox=\"0 0 24 24\" width=\"24px\" fill=\"#000000\"><path d=\"M0 0h24v24H0V0z\" fill=\"none\"></path><path d=\"M19.23 15.26l-2.54-.29c-.61-.07-1.21.14-1.64.57l-1.84 1.84c-2.83-1.44-5.15-3.75-6.59-6.59l1.85-1.85c.43-.43.64-1.03.57-1.64l-.29-2.52c-.12-1.01-.97-1.77-1.99-1.77H5.03c-1.13 0-2.07.94-2 2.07.53 8.54 7.36 15.36 15.89 15.89 1.13.07 2.07-.87 2.07-2v-1.73c.01-1.01-.75-1.86-1.76-1.98z\"></path></svg><strong class=\"header-phone__number\">1-855-642-8607</strong></a></div>";
	primaryNavigation.extraMobileElements = [ '.addsearch-searchfield input' ];
	primaryNavigation.init();
}
window.addEventListener('load', setupToggleNav);
