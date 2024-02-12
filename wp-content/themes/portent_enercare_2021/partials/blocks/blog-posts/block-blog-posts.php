<?php

/**
 * Blog Post Block
 * This block display a list of blog posts based on chose categories
 */
$classes = 'block-blog-posts';

$group_orientation = get_field( 'group_orientation' ) ? ' block-blog-posts--column' : ' block-blog-posts--row';
$block_alignment = get_field('block_orientation') ? ' block-blog-posts__post--horizontal' : ' block-blog-posts__post--vertical';
$blog_cats = get_field( 'categories' );
$content_hub_cats = get_field( 'content_hub_categories' );
$post_type = get_field( 'post_type' );
if (!$post_type || $post_type == "") {
  $post_type = "post";
}
$blog_num_posts = get_field('number_of_posts') ? get_field('number_of_posts') : 4;
$blog_include_posts = get_field( 'include_posts' ) ? get_field( 'include_posts' ) : array();
$post_display = get_field( 'post_display' );
$post_display_class = 'block-blog-posts__post--' . $post_display;
$post_image_size = get_field( 'image_size' ) ? get_field( 'image_size' ) : '2-3';
$hide_post_date = get_field( 'hide_post_date' );
$is_mobile_carousel = get_field('is_mobile_carousel') ? ' block-carousel is_mobile_carousel' : '';
$heading_tag = get_field('heading_tag') ? get_field('heading_tag') : 'h3' ;

if( !empty($block['className']) ) {
	$classes .= sprintf( ' %s', $block['className'] );
}
if( !empty($block['align']) ) {
	$classes .= sprintf( ' align%s', $block['align'] );
}

/*
 * Query Posts
 *
 * First we will query posts with matching categories and only retrieve their IDs.
 * We will adjust the post count based on the number of expected posts minus the amount of
 * explictly provided posts.
 *
 * We only want to do this is category is set. If it isnt we return an empty array. If we do this query when there
 * are no categories set we will just get back recent posts
 *
 * We also can't let the posts_per_page count drop below 0. If it does the query gets set to infinite
 */
if( $blog_cats && $blog_num_posts > count( $blog_include_posts ) ) {
	$category_query_args = array(
    'post_status'    => 'publish',
		'category__in'   => $blog_cats,
		'posts_per_page' => $blog_num_posts - count($blog_include_posts),
		'post__not_in'   => $blog_include_posts,
		'fields'         => 'ids'
	);

	/**
	 * We have to do this with get_posts, if we use wp_query we will get an object instead of an array
	 */
	$category_query = get_posts( $category_query_args );
  
} elseif ($post_type == "page" && $content_hub_cats) {
  
  /*
   * Create a special query for Content Hub Categories, if 'page' is selected and Content Hub Categories are defined.
   */
  $category_query_args = array(
		'post_type'   => 'page',
    'post_status' => 'publish',
		'fields'      => 'ids',
    'tax_query' => array(
      array(
        'taxonomy' => 'content-hub-categories',
        'terms' => $content_hub_cats,
        'operator' => 'IN'
      )
    )
	);

	/**
	 * We have to do this with get_posts, if we use wp_query we will get an object instead of an array
	 */
	$category_query = get_posts( $category_query_args );
  
} else {
	$category_query = array();
}

/**
 * After we get IDs for posts within categories we merge the two arrays and then do a single query only pulling the IDs
 * we have collected
 */
$merged_posts_ids = array_merge( $blog_include_posts, $category_query );

$blog_post_args = array(
  'post_status'         => 'publish',
  'post_type'           => $post_type,
	'posts_per_page'      => (int)$blog_num_posts,
  'post__in'            => $merged_posts_ids,
  'ignore_sticky_posts' => true
);
$blog_posts = new WP_Query( $blog_post_args );
?>
	<?php
		if( $blog_posts->have_posts()) {
			echo '<div class="block-blog-posts__wrapper">';

			echo '<div class="block-blog-posts ' . $group_orientation . '' . $is_mobile_carousel . '">';
			while ( $blog_posts->have_posts() ) {
				$blog_posts->the_post();
				$excerpt = get_the_excerpt() ? get_the_excerpt() : get_the_content();
				echo '<div class="block-blog-posts__post ' . $block_alignment . ' ' . $post_display_class . '">';
				echo '<a class="block-blog-posts__post__link" href="' . get_the_permalink() . '">';

				if ( has_post_thumbnail() && $post_display !== 'compact' ) {
					the_post_thumbnail( $post_image_size, array(
							'class' => 'block-blog-posts__post__image block-blog-posts__post__image--' . $post_image_size . '',
							'alt'   => ''
					) );
				} elseif ( ! has_post_thumbnail() && $post_display !== 'compact' ) {
					$default_image = get_field( 'enercare_default_image', 'options' );
					echo wp_get_attachment_image( $default_image, $post_image_size, false, array(
							'class' => 'block-blog-posts__post__image block-blog-posts__post__image--' . $post_image_size . '',
							'alt'   => ''
					) );
				}

				echo '<div class="block-blog-posts__post__summary">';

				if( $post_display === 'full') {
					/* 10/28/2021: disabling the author byline until the blog is properly ready.
          $id = get_the_author_meta( 'ID' );
					echo '<div class="block-blog-posts__post__post-meta">';
						echo get_avatar( $id, 40 );
							echo '<div class="block-blog-posts__post__author-meta">';
								echo '<div class="block-blog-posts__post__author-meta__name">' . get_the_author() . '</div>';
								echo '<div class="block-blog-posts__post__author-meta__date">'.get_the_date('F j').'</div>';
								echo do_shortcode( '[rt_reading_time label="" postfix="min read"]');
						echo '</div>';
					echo '</div>';*/
          if (!$hide_post_date) {
            echo '<p class="publish-date single-post__date">' . get_the_date('F j, Y') . '</p>';
          }
				}

				echo  '<p class="block-blog-posts__post__title">' . get_the_title() . '</p>';

				if ( $post_display === 'full' ) {
					echo '<p class="block-blog-posts__post__except">' . wp_trim_words( $excerpt, 25 ) . '</p>';
				}
				echo '</div>';
				echo '</div>';
			}
			echo '</a>';
			//End main block container
			echo '</div>';

			if( $is_mobile_carousel ) {
				echo '<div class="block-carousel__controls">';
				echo '<button aria-label="Previous" class="glider-prev block-carousel__prev block-blog-posts__glider-prev"><img height="20" width="20" alt="Previous" src="'. get_template_directory_uri() . '/assets/icons/utility/arrow_back.svg" /></button>';
				echo '<div role="tablist" class="dots block-carousel__dots block-blog-posts__glider-dots"></div>';
				echo '<button aria-label="Next" class="glider-next block-carousel__next block-blog-posts__glider-next"><img height="20" width="20" alt="Next" src="'.get_template_directory_uri() . '/assets/icons/utility/arrow_forward.svg" /></button>';
				echo '</div>';
			}

			//End Wrapper container
			echo '</div>';

			if ( $blog_cats && get_field('show_category_link') ) {
				echo '<div class="block-blog-posts__archive-links">';
				echo '<a class="wp-block-button__link has-red-background-color has-background block-blog-posts__archive-link" href="' . get_category_link( $blog_cats[0] ) . '">View all ' . get_cat_name( $blog_cats[0] ) . ' posts</a>';
				echo '</div>';
			}
		}
?>
