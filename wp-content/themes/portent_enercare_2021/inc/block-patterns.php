<?php
function enercare_block_patterns() {

	register_block_pattern_category(
		'enercare',
		array( 'label' => __( 'Enercare', 'portent_enercare' ) )
	);
  register_block_pattern_category(
		'locations',
		array( 'label' => __( 'Locations', 'portent_enercare' ) )
	);
  register_block_pattern_category(
		'tabs',
		array( 'label' => __( 'Tabs', 'portent_enercare' ) )
	);
  register_block_pattern_category(
		'carousels',
		array( 'label' => __( 'Carousels', 'portent_enercare' ) )
	);

	/**
	 * Our Services 3 Column Cards
	 */
	register_block_pattern( 'portent_enercare/our-services-three-column-cards',
		array(
			'title' => __( 'Our Services 3 Column Cards', 'portent_enercare'),
			'description' => _x('3 column cards describing services scaffold', 'portent_enercare'),
			'categories' => array('enercare','columns'),
			'content' => "
<!-- wp:group {\"align\":\"full\"} -->
<div class=\"wp-block-group alignfull\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":1} -->
<h1 class=\"has-text-align-center\"><strong>Our Home Heating Services</strong></h1>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class=\"wp-block-columns\"><!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/card {\"id\":\"block_6141246acb720\",\"name\":\"acf/card\",\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-card is-style-shadowed has-white-background-color has-background\",\"className\":\"is-style-shadowed\",\"backgroundColor\":\"white\"} -->
<!-- wp:image {\"align\":\"center\",\"id\":3302,\"width\":150,\"height\":150,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/protection-ribbon-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3302\" width=\"150\" height=\"150\"/></figure></div>
<!-- /wp:image -->

<!-- wp:heading {\"textAlign\":\"center\"} -->
<h2 class=\"has-text-align-center\"><strong>Maintain and Protect</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {\"align\":\"center\"} -->
<p class=\"has-text-align-center\">Get peace of mind with plans that offer annual inspections, unlimited service calls, same-day repairs and more.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {\"contentJustification\":\"center\"} -->
<div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"className\":\"is-style-hero\"} -->
<div class=\"wp-block-button is-style-hero\"><a class=\"wp-block-button__link\">Protect Furnace</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:acf/card --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/card {\"id\":\"block_61412554cb73a\",\"name\":\"acf/card\",\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-card is-style-shadowed has-white-background-color has-background\",\"className\":\"is-style-shadowed\",\"backgroundColor\":\"white\"} -->
<!-- wp:image {\"align\":\"center\",\"id\":3302,\"width\":150,\"height\":150,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/protection-ribbon-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3302\" width=\"150\" height=\"150\"/></figure></div>
<!-- /wp:image -->

<!-- wp:heading {\"textAlign\":\"center\"} -->
<h2 class=\"has-text-align-center\"><strong><strong>Repair</strong></strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {\"align\":\"center\"} -->
<p class=\"has-text-align-center\">Problems with your furnace? Get heating service right away from our licensed technicians for any make or model.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {\"contentJustification\":\"center\"} -->
<div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"className\":\"is-style-hero\"} -->
<div class=\"wp-block-button is-style-hero\"><a class=\"wp-block-button__link\" href=\"https://www.enercare.ca/heating/furnace-repair/\" target=\"_blank\" rel=\"noreferrer noopener\">Repair Furnace</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:acf/card --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/card {\"id\":\"block_61412559cb73e\",\"name\":\"acf/card\",\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-card is-style-shadowed has-white-background-color has-background\",\"className\":\"is-style-shadowed\",\"backgroundColor\":\"white\"} -->
<!-- wp:image {\"align\":\"center\",\"id\":3302,\"width\":150,\"height\":150,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/protection-ribbon-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3302\" width=\"150\" height=\"150\"/></figure></div>
<!-- /wp:image -->

<!-- wp:heading {\"textAlign\":\"center\"} -->
<h2 class=\"has-text-align-center\"><strong><strong>Replace</strong></strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {\"align\":\"center\"} -->
<p class=\"has-text-align-center\">If you need to buy or rent a brand-new high-efficiency furnace, we can help you find the right heating system for your home.&nbsp;</p>
<!-- /wp:paragraph -->

<!-- wp:buttons {\"contentJustification\":\"center\"} -->
<div class=\"wp-block-buttons is-content-justification-center\"><!-- wp:button {\"className\":\"is-style-hero\"} -->
<div class=\"wp-block-button is-style-hero\"><a class=\"wp-block-button__link\" href=\"https://www.enercare.ca/heating/new-furnace\" target=\"_blank\" rel=\"noreferrer noopener\">Replace Furnace</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->
<!-- /wp:acf/card --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
",
		)
	);

  
  /**
	 * Mega Buttons with Icons - Locations
	 */
	register_block_pattern( 'portent_enercare/mega-buttons-icons-locations',
		array(
			'title' => __( 'Mega Buttons with Icons - Locations', 'portent_enercare'),
			'description' => _x('Mega buttons with icons for location pages', 'portent_enercare'),
			'categories' => array('enercare', 'locations', 'buttons'),
			'content' => "
<!-- wp:group {\"align\":\"full\",\"backgroundColor\":\"grey-10\"} -->
<div class=\"wp-block-group alignfull has-grey-10-background-color has-background\"><!-- wp:acf/mega-button {\"id\":\"block_6144d9f8d771f\",\"name\":\"acf/mega-button\",\"data\":{\"image\":3302,\"_image\":\"field_612e949ed0c97\",\"link\":{\"title\":\"Offers\",\"url\":\"/special-offers/\",\"target\":\"\"},\"_link\":\"field_612e948ad0c96\"},\"align\":\"left\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-mega-button alignleft Test\",\"className\":\"Test\"} /-->

<!-- wp:acf/mega-button {\"id\":\"block_6144e28ed7724\",\"name\":\"acf/mega-button\",\"data\":{\"image\":3297,\"_image\":\"field_612e949ed0c97\",\"link\":{\"title\":\"Boiler \u0026 Furnace\",\"url\":\"/heating\",\"target\":\"\"},\"_link\":\"field_612e948ad0c96\"},\"align\":\"left\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-mega-button alignleft Test\",\"className\":\"Test\"} /-->

<!-- wp:acf/mega-button {\"id\":\"block_6144e2c2d7725\",\"name\":\"acf/mega-button\",\"data\":{\"image\":3313,\"_image\":\"field_612e949ed0c97\",\"link\":{\"title\":\"Air Conditioner\",\"url\":\"/cooling\",\"target\":\"\"},\"_link\":\"field_612e948ad0c96\"},\"align\":\"left\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-mega-button alignleft Test\",\"className\":\"Test\"} /-->

<!-- wp:acf/mega-button {\"id\":\"block_6144e2c4d7726\",\"name\":\"acf/mega-button\",\"data\":{\"image\":3308,\"_image\":\"field_612e949ed0c97\",\"link\":{\"title\":\"Water Heater\",\"url\":\"/water/water-heating\",\"target\":\"\"},\"_link\":\"field_612e948ad0c96\"},\"align\":\"left\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-mega-button alignleft Test\",\"className\":\"Test\"} /-->

<!-- wp:acf/mega-button {\"id\":\"block_6144e2c7d7727\",\"name\":\"acf/mega-button\",\"data\":{\"image\":3301,\"_image\":\"field_612e949ed0c97\",\"link\":{\"title\":\"Plumbing\",\"url\":\"/plumbing-electrical/plumbing-repair\",\"target\":\"\"},\"_link\":\"field_612e948ad0c96\"},\"align\":\"left\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-mega-button alignleft Test\",\"className\":\"Test\"} /-->

<!-- wp:acf/mega-button {\"id\":\"block_6144e2ccd7728\",\"name\":\"acf/mega-button\",\"data\":{\"image\":3303,\"_image\":\"field_612e949ed0c97\",\"link\":{\"title\":\"Location Info\",\"url\":\"/locations/barrie/#location\",\"target\":\"\"},\"_link\":\"field_612e948ad0c96\"},\"align\":\"left\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-mega-button alignleft Test\",\"className\":\"Test\"} /-->

<!-- wp:paragraph -->
<p></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
",
		)
	);
  
  /**
	 * ECM Bio Carousel - Locations
	 */
	register_block_pattern( 'portent_enercare/ecm-bio-carousel-locations',
		array(
			'title' => __( 'ECM Bios Carousel - Locations', 'portent_enercare'),
			'description' => _x('ECM Bios in a carousel for location pages', 'portent_enercare'),
			'categories' => array('enercare', 'locations', 'carousels'),
			'content' => "
<!-- wp:group -->
<div class=\"wp-block-group\"><!-- wp:heading {\"textAlign\":\"center\"} -->
<h2 class=\"has-text-align-center\">Meet Your Local Energy Management Consultants</h2>
<!-- /wp:heading -->

<!-- wp:acf/glider-carousel {\"id\":\"block_6144eef35a28d\",\"name\":\"acf/glider-carousel\",\"data\":{\"slides_to_show\":\"1\",\"_slides_to_show\":\"field_612d7a6eeafcd\",\"slides_to_advance\":\"1\",\"_slides_to_advance\":\"field_612d7a7beafce\",\"rewind\":\"1\",\"_rewind\":\"field_612d7a8eeafcf\",\"arrows\":\"1\",\"_arrows\":\"field_612d7aa5eafd0\",\"dots\":\"1\",\"_dots\":\"field_612d7abceafd1\",\"breakpoints_0_breakpoint\":\"775\",\"_breakpoints_0_breakpoint\":\"field_61438d901b8f3\",\"breakpoints_0_slides_to_show\":\"3\",\"_breakpoints_0_slides_to_show\":\"field_61438d671b8f1\",\"breakpoints_0_slides_to_advance\":\"1\",\"_breakpoints_0_slides_to_advance\":\"field_61438d7b1b8f2\",\"breakpoints_0_item_width\":\"272\",\"_breakpoints_0_item_width\":\"field_612d7d07b9cae\",\"breakpoints_0_duration\":\"0.5\",\"_breakpoints_0_duration\":\"field_612d7cfbb9cad\",\"breakpoints\":1,\"_breakpoints\":\"field_612d7b2cb9ca7\"},\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-glider-carousel\"} -->
<!-- wp:acf/ecm-card {\"id\":\"block_6144eb215a287\",\"name\":\"acf/ecm-card\",\"data\":{\"image\":\"\",\"_image\":\"field_612d778659c6e\",\"name\":\"\",\"_name\":\"field_612d77a859c6f\",\"title\":\"\",\"_title\":\"field_612d794259c70\",\"bio\":\"\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Proin venenatis nisl nec purus porta volutpat. Suspendisse varius ligula sit amet dolor suscipit, et auctor massa molestie. Curabitur rhoncus efficitur blandit. Donec ac ultrices orci. Vestibulum at massa sed sem pulvinar scelerisque.\",\"_bio\":\"field_612d796b59c71\",\"cta_text\":\"Read Bio\",\"_cta_text\":\"field_612d798f59c72\"},\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-ecm-card\"} /-->

<!-- wp:acf/ecm-card {\"id\":\"block_6144eb335a288\",\"name\":\"acf/ecm-card\",\"data\":{\"image\":\"\",\"_image\":\"field_612d778659c6e\",\"name\":\"\",\"_name\":\"field_612d77a859c6f\",\"title\":\"\",\"_title\":\"field_612d794259c70\",\"bio\":\"\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Proin venenatis nisl nec purus porta volutpat. Suspendisse varius ligula sit amet dolor suscipit, et auctor massa molestie. Curabitur rhoncus efficitur blandit. Donec ac ultrices orci. Vestibulum at massa sed sem pulvinar scelerisque.\",\"_bio\":\"field_612d796b59c71\",\"cta_text\":\"Read Bio\",\"_cta_text\":\"field_612d798f59c72\"},\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-ecm-card\"} /-->

<!-- wp:acf/ecm-card {\"id\":\"block_6144eb445a289\",\"name\":\"acf/ecm-card\",\"data\":{\"image\":\"\",\"_image\":\"field_612d778659c6e\",\"name\":\"\",\"_name\":\"field_612d77a859c6f\",\"title\":\"\",\"_title\":\"field_612d794259c70\",\"bio\":\"\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Proin venenatis nisl nec purus porta volutpat. Suspendisse varius ligula sit amet dolor suscipit, et auctor massa molestie. Curabitur rhoncus efficitur blandit. Donec ac ultrices orci. Vestibulum at massa sed sem pulvinar scelerisque.\",\"_bio\":\"field_612d796b59c71\",\"cta_text\":\"Read Bio\",\"_cta_text\":\"field_612d798f59c72\"},\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-ecm-card\"} /-->

<!-- wp:acf/ecm-card {\"id\":\"block_6144eb475a28a\",\"name\":\"acf/ecm-card\",\"data\":{\"image\":\"\",\"_image\":\"field_612d778659c6e\",\"name\":\"\",\"_name\":\"field_612d77a859c6f\",\"title\":\"\",\"_title\":\"field_612d794259c70\",\"bio\":\"\r\n\r\nLorem ipsum dolor sit amet, consectetur adipiscing elit. Proin venenatis nisl nec purus porta volutpat. Suspendisse varius ligula sit amet dolor suscipit, et auctor massa molestie. Curabitur rhoncus efficitur blandit. Donec ac ultrices orci. Vestibulum at massa sed sem pulvinar scelerisque.\",\"_bio\":\"field_612d796b59c71\",\"cta_text\":\"Read Bio\",\"_cta_text\":\"field_612d798f59c72\"},\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-ecm-card\"} /-->
<!-- /wp:acf/glider-carousel --></div>
<!-- /wp:group -->
",
		)
	);
  
  /**
	 * Location About Info
	 */
	register_block_pattern( 'portent_enercare/why-enercare-five-column-cards',
		array(
			'title' => __( 'Why Enercare? 5 Column Cards', 'portent_enercare'),
			'description' => _x('Why choose Enercare? 5 Column Cards with icons.', 'portent_enercare'),
			'categories' => array('enercare', 'cards', 'text'),
			'content' => "
<!-- wp:group -->
<div class=\"wp-block-group\"><!-- wp:image {\"align\":\"center\",\"id\":3310,\"width\":130,\"height\":130,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/billing-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3310\" width=\"130\" height=\"130\"/></figure></div>
<!-- /wp:image -->

<!-- wp:heading {\"textAlign\":\"center\"} -->
<h2 class=\"has-text-align-center\">Why Choose Enercare?</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {\"align\":\"center\",\"fontSize\":\"normal\"} -->
<p class=\"has-text-align-center has-normal-font-size\">When you choose Enercare, you get expert advice and quality work from one of our friendly, professional and fully licensed electricians along with:</p>
<!-- /wp:paragraph -->

<!-- wp:columns -->
<div class=\"wp-block-columns\"><!-- wp:column {\"width\":\"100%\"} -->
<div class=\"wp-block-column\" style=\"flex-basis:100%\"><!-- wp:columns -->
<div class=\"wp-block-columns\"><!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/card {\"id\":\"block_6141246acb720\",\"name\":\"acf/card\",\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-card is-style-shadowed has-white-background-color has-background\",\"className\":\"is-style-shadowed\",\"backgroundColor\":\"white\"} -->
<!-- wp:image {\"align\":\"center\",\"id\":3310,\"width\":150,\"height\":150,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/billing-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3310\" width=\"150\" height=\"150\"/></figure></div>
<!-- /wp:image -->

<!-- wp:paragraph {\"fontSize\":\"normal\"} -->
<p class=\"has-normal-font-size\">A fixed price quote upfront, including all parts and labour — no hidden charges</p>
<!-- /wp:paragraph -->
<!-- /wp:acf/card --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/card {\"id\":\"block_61412554cb73a\",\"name\":\"acf/card\",\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-card is-style-shadowed has-white-background-color has-background\",\"className\":\"is-style-shadowed\",\"backgroundColor\":\"white\"} -->
<!-- wp:image {\"align\":\"center\",\"id\":3299,\"width\":150,\"height\":150,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/maintenance-plans-tools-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3299\" width=\"150\" height=\"150\"/></figure></div>
<!-- /wp:image -->

<!-- wp:paragraph {\"align\":\"center\",\"fontSize\":\"normal\"} -->
<p class=\"has-text-align-center has-normal-font-size\">1-year parts and a 90-day labour guarantee for all repair work completed<sup>†</sup></p>
<!-- /wp:paragraph -->
<!-- /wp:acf/card --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/card {\"id\":\"block_61412559cb73e\",\"name\":\"acf/card\",\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-card is-style-shadowed has-white-background-color has-background\",\"className\":\"is-style-shadowed\",\"backgroundColor\":\"white\"} -->
<!-- wp:image {\"align\":\"center\",\"id\":3305,\"width\":150,\"height\":150,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/smart-home-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3305\" width=\"150\" height=\"150\"/></figure></div>
<!-- /wp:image -->

<!-- wp:paragraph {\"align\":\"center\",\"fontSize\":\"normal\"} -->
<p class=\"has-text-align-center has-normal-font-size\">Service and repairs for a wide range of devices and brands, often on the same day<sup>1</sup></p>
<!-- /wp:paragraph -->
<!-- /wp:acf/card --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/card {\"id\":\"block_61491d5a50d4d\",\"name\":\"acf/card\",\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-card is-style-shadowed has-white-background-color has-background\",\"className\":\"is-style-shadowed\",\"backgroundColor\":\"white\"} -->
<!-- wp:image {\"align\":\"center\",\"id\":3307,\"width\":150,\"height\":150,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/van-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3307\" width=\"150\" height=\"150\"/></figure></div>
<!-- /wp:image -->

<!-- wp:paragraph {\"align\":\"center\",\"fontSize\":\"normal\"} -->
<p class=\"has-text-align-center has-normal-font-size\">An expedited approval process for any required permits</p>
<!-- /wp:paragraph -->
<!-- /wp:acf/card --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/card {\"id\":\"block_61491d5c50d4e\",\"name\":\"acf/card\",\"align\":\"\",\"mode\":\"preview\",\"wpClassName\":\"wp-block-acf-card is-style-shadowed has-white-background-color has-background\",\"className\":\"is-style-shadowed\",\"backgroundColor\":\"white\"} -->
<!-- wp:image {\"align\":\"center\",\"id\":3302,\"width\":150,\"height\":150,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/protection-ribbon-picto-dc1c28.svg\" alt=\"\" class=\"wp-image-3302\" width=\"150\" height=\"150\"/></figure></div>
<!-- /wp:image -->

<!-- wp:paragraph {\"align\":\"center\",\"fontSize\":\"normal\"} -->
<p class=\"has-text-align-center has-normal-font-size\">Service from a team of licensed electricians with a 4.8 score on Google and 93% 5-star reviews, our customers agree that we work hard to deliver the best service<sup>2</sup></p>
<!-- /wp:paragraph -->
<!-- /wp:acf/card --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
",
		)
	);
  
  /**
	 * Location About Info
	 */
	register_block_pattern( 'portent_enercare/location-about-info',
		array(
			'title' => __( 'Location About Info', 'portent_enercare'),
			'description' => _x('Location about info for location pages', 'portent_enercare'),
			'categories' => array('enercare', 'locations', 'text'),
			'content' => "
<!-- wp:group {\"align\":\"full\",\"backgroundColor\":\"grey-10\"} -->
<div class=\"wp-block-group alignfull has-grey-10-background-color has-background\"><!-- wp:heading {\"textAlign\":\"center\",\"align\":\"wide\",\"textColor\":\"grey\"} -->
<h2 class=\"alignwide has-text-align-center has-grey-color has-text-color\">How We Contribute to the Community:</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"grey\"} -->
<p class=\"has-text-align-center has-grey-color has-text-color\">Enercare is proud to provide our Enercare Fresh Start program to the Barrie community. This is a program that is conducted in partnership with local organizations in the communities that we serve. The mandate of the program is to give those families who are residing in shelters the opportunity to change their living conditions with a customized fresh start package.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {\"align\":\"center\",\"textColor\":\"grey\"} -->
<p class=\"has-text-align-center has-grey-color has-text-color\">This is a program that brings great satisfaction to the regions we service, and to us as a company that takes great pride in being an active part of the community.</p>
<!-- /wp:paragraph -->

<!-- wp:spacer {\"height\":30} -->
<div style=\"height:30px\" aria-hidden=\"true\" class=\"wp-block-spacer\"></div>
<!-- /wp:spacer -->

<!-- wp:image {\"align\":\"center\",\"id\":3887,\"width\":768,\"height\":512,\"sizeSlug\":\"large\",\"linkDestination\":\"none\"} -->
<div class=\"wp-block-image\"><figure class=\"aligncenter size-large is-resized\"><img src=\"https://dev-enercare.pantheonsite.io/wp-content/uploads/2021/09/Enercare-117-1024x683.jpg\" alt=\"\" class=\"wp-image-3887\" width=\"768\" height=\"512\"/></figure></div>
<!-- /wp:image --></div>
<!-- /wp:group -->
",
		)
	);
  
  /**
	 * Location Info
	 */
	register_block_pattern( 'portent_enercare/location-info',
		array(
			'title' => __( 'Location 2 Column Info', 'portent_enercare'),
			'description' => _x('Location info for location pages', 'portent_enercare'),
			'categories' => array('enercare', 'locations', 'text'),
			'content' => "
<!-- wp:group {\"align\":\"full\",\"backgroundColor\":\"grey-10\"} -->
<div class=\"wp-block-group alignfull has-grey-10-background-color has-background\" id=\"location\"><!-- wp:heading {\"textAlign\":\"center\"} -->
<h2 class=\"has-text-align-center\">Location Info</h2>
<!-- /wp:heading -->

<!-- wp:columns -->
<div class=\"wp-block-columns\"><!-- wp:column {\"backgroundColor\":\"white\"} -->
<div class=\"wp-block-column has-white-background-color has-background\"><!-- wp:acf/location-info {\"id\":\"block_613faea58c938\",\"name\":\"acf/location-info\",\"align\":\"\",\"mode\":\"auto\",\"wpClassName\":\"wp-block-acf-location-info\"} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class=\"wp-block-column\"><!-- wp:acf/location-map {\"id\":\"block_613faeb18c939\",\"name\":\"acf/location-map\",\"align\":\"\",\"mode\":\"auto\",\"wpClassName\":\"wp-block-acf-location-map\"} /--></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
",
		)
	);

	register_block_pattern('portent_enercare/mega-button-2-by-2-grid-block',
		array(
			'title' => __( '2x2 Mega Button Block', '2-by-2-mega-button-block' ),
			'description' => _x( '2x2 Mega Button using the Switchboard', 'Block pattern description', '2-by-2-mega-button-block' ),
			'categories' => array('enercare', 'locations'),
			'content' => "
			<!-- wp:group {\"align\":\"full\",\"backgroundColor\":\"grey-10\"} -->
				<div class=\"wp-block-group alignfull has-grey-10-background-color has-background\">
					<!-- wp:acf/enercare-switchboard {\"id\":\"block_62fff8d840711\",\"name\":\"acf/enercare-switchboard\",\"data\":{\"field_61805b36bd417\":\"2\",\"field_61805b7d3685b\":\"1\"},\"align\":\"\",\"mode\":\"preview\"} -->
					<!-- wp:acf/mega-button {\"id\":\"block_62fff8e240712\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->

					<!-- wp:acf/mega-button {\"id\":\"block_62fff91f40715\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->

					<!-- wp:acf/mega-button {\"id\":\"block_62fff99540719\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->

					<!-- wp:acf/mega-button {\"id\":\"block_62fff9a84071a\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->
				<!-- /wp:acf/enercare-switchboard --></div>
			<!-- /wp:group -->
			",
		)
	);

	register_block_pattern('portent_enercare/mega-button-3-by-2-grid-block',
		array(
			'title' => __( '3x2 Mega Button Block', '3-by-2-mega-button-block' ),
			'description' => _x( '3x2 Mega Button using the Switchboard', 'Block pattern description', '3-by-2-mega-button-block' ),
			'categories' => array('enercare', 'locations'),
			'content' => "
			<!-- wp:group {\"align\":\"full\",\"backgroundColor\":\"grey-10\"} -->
			<div class=\"wp-block-group alignfull has-grey-10-background-color has-background\">
			<!-- wp:acf/enercare-switchboard {\"id\":\"block_62fff73540705\",\"name\":\"acf/enercare-switchboard\",\"data\":{\"field_61805b36bd417\":\"3\",\"field_61805b7d3685b\":\"1\"},\"align\":\"\",\"mode\":\"preview\"} -->
				<!-- wp:acf/mega-button {\"id\":\"block_62fff75840706\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->

				<!-- wp:acf/mega-button {\"id\":\"block_62fff78e40709\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->

				<!-- wp:acf/mega-button {\"id\":\"block_62fff85f4070d\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->

				<!-- wp:acf/mega-button {\"id\":\"block_62fff94240716\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->

				<!-- wp:acf/mega-button {\"id\":\"block_62fff95940717\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->

				<!-- wp:acf/mega-button {\"id\":\"block_62fff96b40718\",\"name\":\"acf/mega-button\",\"data\":{\"field_612e949ed0c97\":\"\",\"field_612e948ad0c96\":{\"title\":\"Title Placeholder\",\"url\":\"/\",\"target\":\"\"},\"field_616f2bd334d20\":\"auto\",\"field_616f2c6c34d21\":\"top\"},\"align\":\"\",\"mode\":\"preview\"} /-->
			<!-- /wp:acf/enercare-switchboard --></div>
			<!-- /wp:group -->
			",
		)
	);  
}

add_action('acf/init', 'enercare_block_patterns' );