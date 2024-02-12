window.addEventListener("load", portentFrontEndTabInit );
function portentFrontEndTabInit(event) {

	const CheckTabHash = () => {
		let hashedTab = false;
		let urlHash = window.location.hash.substr(1);
		if( urlHash !== '' ) {
			let tabGrab = document.querySelector('[data-anchor='+urlHash+']');
			console.log(tabGrab);

			if(typeof tabGrab != 'undefined' ) {
				hashedTab = tabGrab
			}
		}
		return hashedTab;
	}

	/*
	Click Tab if it is in the hash
	 */
	const OpenHashedTag = (tab) => {
		console.log(tab);
		handleTabClick(tab);
	}

	const handleTabClick = (tab) => {
		let parent = tab.parentElement;
		let children = Array.prototype.slice.call(parent.children);
		children.forEach( child => {
			child.classList.remove('active');
		});

		parent.classList.remove('init');
		let topLevelParent =  tab.parentElement.parentElement;
		let tabPanelContainer = topLevelParent.querySelector('.block-tabbed-content__tab-panels');
		let panels = topLevelParent.querySelectorAll('.block-tabbed-content__tab-content');
		let targetPanel = topLevelParent.querySelector(`[data-tab="${tab.dataset.tab}"].block-tabbed-content__tab-content`);
		panels.forEach( panel => {
			panel.classList.remove('active');
		});

		tabPanelContainer.classList.remove('init');
		targetPanel.classList.add('active');
		tab.classList.add('active');
	}

	/**
	 * Desktop
	 */
	const tabs = document.querySelectorAll('.block-tabbed-content__tab');

	tabs.forEach( tab => {
		tab.addEventListener('click', (event) => {
			handleTabClick(event.target);
		});
	});

	let checkHashedTab = CheckTabHash();
	if(checkHashedTab) {
		OpenHashedTag(checkHashedTab);
	}

	/**
	 * Mobile
	 */
	const toggles = document.querySelectorAll('.block-tabbed-content__panel__toggle');
	toggles.forEach( toggle => {
		toggle.addEventListener('click', (event) => {
			const eventTarget = event.target.classList.contains('block-tabbed-content__panel__toggle') ? event.target : event.target.parentElement;
			const toggleTab = toggle.dataset.tab;
			const targetTab = document.querySelector('[data-tab="' + toggleTab + '"].block-tabbed-content__tab-content');
			const siblingTab = document.querySelector('[data-tab="' + toggleTab + '"].block-tabbed-content__tab');
			const toggleParent = event.target.closest('.block-tabbed-content__tab-panels');
			const blockParent = toggleParent.closest('.wp-block-portent-block-tabbed-content');
			const tabParent = siblingTab.parentElement;

			//Remove Init classes that are used to track state on desktop
			toggleParent.classList.remove('init');
			tabParent.classList.remove('init');

			//Perform Resets
			resetTabs(blockParent);
			resetToggles(blockParent);

			if( targetTab.classList.contains('active') ) {
				eventTarget.classList.remove('active');
				eventTarget.setAttribute('aria-expanded', 'false');
				targetTab.classList.remove('active');
				siblingTab.classList.remove('active');

				//Wait for resets until we have performed the update. This helps maintain continuity between desktop and mobile
				resetPanels(blockParent);
			} else {
				eventTarget.classList.add('active');
				eventTarget.setAttribute('aria-expanded', 'true');
				targetTab.classList.add('active');
				siblingTab.classList.add('active');
			}
		});
	});

	/**
	 * Reset Tabs
	 */
	const resetTabs = (tabBlock) => {
		const tabs = tabBlock.querySelectorAll('.block-tabbed-content__tab');
		tabs.forEach( tab => {
			tab.classList.remove('active');
		});
	}

	/*
	Reset Panels
	 */
	const resetPanels = (tabBlock) => {
		const panels = tabBlock.querySelectorAll('.block-tabbed-content__tab-content');
		const panelContainer = tabBlock.querySelector('.block-tabbed-content__tab-panels');
		const tabContainer = tabBlock.querySelector('.block-tabbed-content__tabs');
		let allCollapsed = true;
		panels.forEach( panel => {
			if(panel.classList.contains('active') ){
				panel.classList.remove('active');
				allCollapsed = false;
			} else {
				allCollapsed = true;
			}

		});

		//If all panels are collapsed then restore init state to maintain contiunity with desktop experience
		if(allCollapsed){
			panelContainer.classList.add('init--dirty');
			tabContainer.classList.add('init--dirty');
		}
	}

	/*
	Reset Toggles
	 */
	const resetToggles = (tabBlock) => {
		const toggles = tabBlock.querySelectorAll('.block-tabbed-content__panel__toggle');
		toggles.forEach( toggle => {
			toggle.classList.remove( 'active' );
			toggle.setAttribute('aria-expanded', 'false');
		})
	}
}
