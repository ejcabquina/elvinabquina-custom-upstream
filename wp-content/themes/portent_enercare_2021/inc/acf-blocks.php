<?php
add_action('acf/init', 'enercare_register_theme_blocks');

function enercare_register_theme_blocks() {
  if ( function_exists( 'acf_register_block_type' ) ) {
    $theme_data = wp_get_theme();
		$theme_version = $theme_data->get('Version');

    // Register a general card block.
    acf_register_block_type( array(
      'name'            => 'card',
      'title'           => __( 'Card' ),
      'description'     => __( 'A standard card frame with a customizable inner panel' ),
      'render_template' => 'partials/blocks/card/card.php',
      'category'        => 'layout',
      'mode'            => 'preview',
      'keywords'        => array('card', 'frame', 'enercare', 'portent'),
      'enqueue_assets' => function() {
	      wp_enqueue_style( 'block-card-style', get_template_directory_uri() . '/assets/css/block--card.css', array('ea-style'), false, 'screen');
      },

      'supports'        => array(
        'align' => true,
        'mode'  => false,
        'jsx'   => true,
        'color' => array(
          'background' => true,
          'text' => false
        )
      ),
    ));

    acf_register_block_type( array(
		  'name'            => 'ecm-card',
		  'title'           => __( 'EMC Profile Card' ),
		  'description'     => __( 'Card that displays a photo, and some information for an Enercare EMC. This card has an additional text area for supplying biographical content about the EMC that will open in a modal view' ),
		  'render_template' => 'partials/blocks/modal-card/block-modal-card.php',
		  'category'        => 'layout',
		  'mode'            => 'preview',
		  'keywords'        => array('card', 'emc', 'enercare', 'portent'),
		  'enqueue_assets' => function() {
		  	wp_enqueue_script('block-emc-card-script', get_template_directory_uri() . '/assets/js/block--emc-card.js', null, false, true);
			  wp_enqueue_style( 'block-emc-card-style', get_template_directory_uri() . '/assets/css/block--emc-card.css', array('ea-style'), false, 'screen');
		  },
		  'supports'        => array(
			  'align' => true,
			  'mode'  => false,
			  'color' => array(
				  'background' => true,
				  'text' => false
			  )
		  ),
	  ));

	  acf_register_block_type( array(
		  'name'            => 'offer-card',
		  'title'           => __( 'Offer Card' ),
		  'description'     => __( 'This card sets up a "campaign spot". Active campaigns will be queried based on the settings provided' ),
		  'render_template' => 'partials/blocks/offer-card/block-offer-card.php',
		  'category'        => 'layout',
		  'mode'            => 'preview',
		  'keywords'        => array('card', 'offer', 'campaign', 'deal', 'enercare', 'portent'),
		  'enqueue_assets' => function() {
			  wp_enqueue_script( 'micromodal-script');
			  wp_enqueue_script( 'block--offer-card-script');
			  wp_enqueue_style( 'block-offer-card-style');
		  },

		  'supports'        => array(
			  'align' => true,
			  'mode'  => false,
			  'color' => array(
				  'background' => true,
				  'text' => true
			  ),
			  'typography' => array(
				'fontSize' => true,
				'lineHeight' => false,
			  ),
		  ),
	  ));

    acf_register_block_type( array(
		  'name'            => 'comparison-card',
		  'title'           => __( 'Comparison Card' ),
		  'description'     => __( 'Card that builds a comparison chart/table of attributes.' ),
		  'render_template' => 'partials/blocks/comparison-card/block-comparison-card.php',
		  'category'        => 'layout',
		  'mode'            => 'preview',
		  'keywords'        => array('card', 'comparison', 'plan', 'table', 'enercare', 'portent'),
		  'enqueue_assets' => function() {
			  wp_enqueue_style( 'block-comparison-card-style', get_template_directory_uri() . '/assets/css/block--comparison-card.css', array('ea-style'), false, 'screen');
        wp_enqueue_script( 'block--comparison-card-script', get_template_directory_uri() . '/assets/js/block--comparison-card.js', null, null, true);
		  },

		  'supports'        => array(
			  'align' => true,
			  'mode'  => false,
			  'color' => array(
				  'background' => true,
				  'text' => false
			  )
		  ),
	  ));

	  acf_register_block_type( array(
		  'name'            => 'mega-button',
		  'title'           => __( 'Mega Button' ),
		  'description'     => __( 'This block setups a large clickable link that has color options, and a spot for a prominent icon' ),
		  'render_template' => 'partials/blocks/mega-button/block-mega-button.php',
		  'category'        => 'layout',
		  'mode'            => 'preview',
		  'keywords'        => array('card', 'emc', 'enercare', 'portent'),
		  'enqueue_assets' => function() {
			  wp_enqueue_style( 'block-mega-button-style', get_template_directory_uri() . '/assets/css/block--mega-button.css', array('ea-style'), false, 'screen');
		  },
		  'supports'        => array(
			  'align' => true,
			  'mode'  => false,
			  'color' => array(
				  'background' => true,
				  'text' => false
			  )
		  ),
	  ));

    // Register a email newsletter form block.
    acf_register_block_type( array(
      'name'            => 'email-signup',
      'title'           => __( 'Email Newsletter' ),
      'description'     => __( 'Form that allows a user to signup for an email newsletter' ),
      'render_template' => 'partials/blocks/newsletter-signup/block-newsletter-signup.php',
      'category'        => 'layout',
      'mode'            => 'preview',
      'keywords'        => array('email', 'signup', 'newsletter', 'form', 'enercare', 'portent'),
      'enqueue_assets' => function() {
	      wp_enqueue_style( 'block-newsletter-signup-style', get_template_directory_uri() . '/assets/css/block--email-signup.css', array('ea-style'), false, 'screen');
      },
      'supports'        => array(
        'mode'  => false,
        'jsx'   => true,
        'color' => array(
          'background' => true,
          'text' => false,
          'gradients' => false
        )
      ),
    ));

    acf_register_block_type( array(
      'name'            => 'glider-carousel',
      'title'           => __( 'Carousel (Glider.js)' ),
      'description'     => __( 'Block that sets up the carousel container for slides. This block uses the Glider.js library to render the carousel. Glider.js is very low weight in terms of styles and scripting, but lacks the ability to infinitely loop' ),
      'render_template' => 'partials/blocks/carousel-glider/block-carousel-glider.php',
      'category'        => 'layout',
      'mode'            => 'preview',
      'keywords'        => array('carousel', 'glider', 'slide', 'enercare', 'portent'),
      'supports'        => array(
        'mode'  => false,
        'align' => true,
        'jsx'   => true,
        'color' => array(
          'background' => true,
          'text' => false,
          'gradients' => false
        ),
      ),
      'enqueue_assets' => function() {
	      wp_enqueue_script( 'glider-js');
	      wp_enqueue_style( 'glider-css');
	      wp_enqueue_script( 'block--carousel-script', get_template_directory_uri() . '/assets/js/single/block--carousel.js', array('glider-js'), null, true);
      },
    ));

	  acf_register_block_type( array(
		  'name'            => 'glider-slide',
		  'title'           => __( 'Slide (Glider.js)' ),
		  'description'     => __( 'Block to setup individual slides within a Glider Carousel block' ),
		  'render_template' => 'partials/blocks/carousel-glider/slide-glider/block-slide-glider.php',
		  'category'        => 'layout',
		  'parent'          => array('acf/glider-carousel'),
		  'mode'            => 'preview',
		  'keywords'        => array('carousel', 'glider', 'slide', 'enercare', 'portent'),
		  'supports'        => array(
			  'mode'  => false,
			  'align' => true,
			  'jsx'   => true,
			  'color' => array(
				  'background' => true,
				  'text' => false,
				  'gradients' => true
			  ),
		  ),
	  ));

    acf_register_block_type( array(
      'name'            => 'location-finder',
      'title'           => __( 'Location Finder' ),
      'description'     => __( 'Location Finder Block' ),
      'render_template' => 'partials/blocks/location-finder/block-location-finder.php',
      'category'        => 'layout',
      'mode'            => 'preview',
      'keywords'        => array('location', 'finder', 'search', 'form', 'enercare', 'portent'),
      'enqueue_assets' => function() {
        wp_enqueue_style( 'block-location-finder-style', get_template_directory_uri() . '/assets/css/block--location-finder.css', array('ea-style'), false, 'screen');
      },
      'supports'        => array(
        'mode'  => false,
        'align' => false,
        'color' => array(
          'background' => true,
          'text' => false,
          'gradients' => false
        )
      ),
    ));

	  acf_register_block_type( array(
		  'name'            => 'locations-served',
		  'title'           => __( 'Location We Serve' ),
		  'description'     => __( 'This overly specific block is for the bottom of the footer. Use this block to display a list of links that will direct users to specific location pages.' ),
		  'render_template' => 'partials/blocks/locations-served/block-locations-served.php',
		  'category'        => 'layout',
		  'mode'            => 'preview',
		  'keywords'        => array('location', 'finder', 'search', 'form', 'enercare', 'portent'),
		  'enqueue_assets' => function() {
			  wp_enqueue_style( 'block-locations-served-style', get_template_directory_uri() . '/assets/css/block--locations-served.css', array('ea-style'), false, 'screen');
			  wp_enqueue_script( 'block-locations-served-script', get_template_directory_uri() . '/assets/js/block--locations-served.js', null, false, true );
		  },
		  'supports'        => array(
			  'mode'  => false,
			  'align' => false,
			  'color' => array(
				  'background' => true,
				  'text' => false,
				  'gradients' => false
			  )
		  ),
	  ));

    acf_register_block_type(array(
      'name'              => 'enercare-faqs',
      'title'             => __('FAQs', 'enercare'),
      'render_template'   => 'partials/blocks/block-faqs.php',
      'category'          => 'formatting',
      'icon'              => 'testimonial',
      'mode'              => 'auto',
      'supports'        => array(
	      'align' => array( 'center', 'wide' ),
      ),
      'keywords'          => array('faq', 'frequently', 'question', 'answer', 'enercare', 'portent'),
      'enqueue_assets' => function() {
        wp_enqueue_style( 'block-faqs-style', get_template_directory_uri() . '/assets/css/block--faqs.css', array('ea-style'), false, 'screen');
        wp_enqueue_script( 'block--faqs-script', get_template_directory_uri() . '/assets/js/block--faqs.js', null, null, true);
      }
    ));

    acf_register_block_type(array(
      'name'              => 'location-info',
      'title'             => __('Location Info', 'enercare'),
      'render_template'   => 'partials/blocks/block-location-info.php',
      'category'          => 'layout',
      'mode'              => 'auto',
      'keywords'          => array('location', 'info', 'enercare', 'portent'),
      'enqueue_assets' => function() {
        wp_enqueue_style( 'block-location-info-style', get_template_directory_uri() . '/assets/css/block--location-info.css', array('ea-style'), false, 'screen');
      }
    ));

    acf_register_block_type(array(
      'name'              => 'location-map',
      'title'             => __('Location Map', 'enercare'),
      'render_template'   => 'partials/blocks/block-location-map.php',
      'category'          => 'layout',
      'icon'              => 'admin-site',
      'mode'              => 'auto',
      'keywords'          => array('location', 'map', 'google', 'enercare', 'portent'),
      'enqueue_assets' => function() {
        //wp_enqueue_style( 'block-location-map-style', get_template_directory_uri() . '/assets/css/block--location-map.css', array('ea-style'), false, 'screen');
      }
    ));

	  acf_register_block_type(array(
		  'name'              => 'enercare-switchboard',
		  'title'             => __('Switchboard', 'enercare'),
		  'render_template'   => 'partials/blocks/switchboard/block-switchboard.php',
		  'category'          => 'layout',
		  'icon'              => 'grid-view',
		  'mode'              => 'preview',
		  'description'        => 'The switchboard is a highly specialized block that only accepts Mega Buttons inside of its innerblock content. This block addresses some specific use cases for button grids outlined in the original comps for the Enercare 3.0 project',
		  'supports'        => array(
			  'align' => array( 'center', 'wide' ),
			  'jsx'   => true,
		  ),
		  'keywords'          => array('accordion', 'panel', 'enercare', 'content', 'portent'),
		  'enqueue_assets' => function() {
			  //wp_enqueue_style( 'block-location-map-style', get_template_directory_uri() . '/assets/css/block--location-map.css', array('ea-style'), false, 'screen');
		  }
	  ));

	  acf_register_block_type(array(
		  'name' => 'enercare-youtube-lite',
		  'title' => __('Youtube (Lazy)', 'clientname'),
		  'render_template' => 'partials/blocks/youtube-lazy/youtube-lazy.php',
		  'category' => 'formatting',
		  'icon' => 'video-alt3',
		  'mode' => 'preview',
		  'keywords' => array('video', 'youtube', 'expedia', 'portent'),
		  'enqueue_assets' => function() {
			  wp_enqueue_script('lite-youtube-embed-script');
			  wp_enqueue_style('lite-youtube-embed-style');
		  },
	  ));

    acf_register_block_type( array(
	    'name'              => 'blog-posts',
	    'title'             => __('Display Posts', 'enercare'),
	    'render_template'   => 'partials/blocks/blog-posts/block-blog-posts.php',
	    'category'          => 'layout',
	    'icon'              => 'admin-site',
	    'mode'              => 'preview',
	    'keywords'          => array('blog', 'posts', 'pages', 'display', 'list', 'featured', 'enercare', 'portent'),
	    'enqueue_assets' => function() {
		    wp_enqueue_script('block-blog-posts-script', get_template_directory_uri() . '/assets/js/block--blog-posts.js', array('glider-js'), null, true);
	    },

    ));

	  acf_register_block_type( array(
		  'name'              => 'plyr-player',
		  'title'             => __('Youtube Embed (Plyr)', 'enercare'),
		  'render_template'   => 'partials/blocks/plyr-player/play-player.php',
		  'category'          => 'layout',
		  'icon'              => 'admin-site',
		  'mode'              => 'preview',
		  'keywords'          => array('video', 'youtube', 'embed', 'display', 'enercare', 'portent'),
		  'enqueue_assets' => function() {
			  wp_enqueue_script('plyr-script', 'https://cdn.plyr.io/3.7.3/plyr.js', null, '3.7.3', true);
			  wp_enqueue_script('plyr-setup', get_template_directory_uri() . '/assets/js/block--plyr.js', array('plyr-script'), '3.7.3', true);
			  wp_enqueue_style('plyr-styles', 'https://cdn.plyr.io/3.7.3/plyr.css', null, '3.7.3');
		  },

	  ));
  }
}
