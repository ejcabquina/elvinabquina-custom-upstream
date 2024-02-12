<?php
/**
 * Template Tags
 *
 * @package      EAStarter
 * @author       Bill Erickson
 * @since        1.0.0
 * @license      GPL-2.0+
**/

/**
 * Entry Category
 *
 */
function enercare_entry_category() {
	$term = enercare_first_term();
	if( !empty( $term ) && ! is_wp_error( $term ) )
		echo '<p class="entry-category"><a href="' . get_term_link( $term, 'category' ) . '">' . $term->name . '</a></p>';
}

/**
 * Entry Categories
 *
 */
function enercare_entry_categories() {
  $terms = get_the_terms( get_the_id(), 'category' );
  if ($terms && sizeof($terms) > 0) {
    echo '<p class="entry-category">';
    foreach ($terms as $term) {
      echo '<a href="' . get_term_link( $term, 'category' ) . '" class="button">' . $term->name . '</a>';
    }
    echo '</p>';
  }
}

/**
 * Post Summary Title
 *
 */
function enercare_post_summary_title($tag = 'h2') {
	global $wp_query;
	$tag = ( is_singular() || -1 === $wp_query->current_post ) ? 'h3' : $tag;
  $the_title = get_the_title();
  echo '<a href="' . get_permalink() . '">';
  if (get_field('display_title'))
    $the_title = get_field('display_title');
	echo '<' . $tag . ' class="post-summary__title">' . $the_title . '</' . $tag . '>';
	echo '</a>';
}

/**
 * Post Summary
 *
 */
function enercare_post_summary() {
  the_excerpt();
  echo '</a>';
}

/**
 * Post Summary Image
 *
 */
function enercare_post_summary_image( $size = 'thumbnail_medium' ) {
  echo wp_get_attachment_image( enercare_entry_image_id(), $size, null, array("class" => "post-summary__image") );
}


/**
 * Entry Image ID
 *
 */
function enercare_entry_image_id() {
	return has_post_thumbnail() ? get_post_thumbnail_id() : get_option( 'options_enercare_default_image' );
}

/**
 * Entry Author
 *
 */
function enercare_entry_author() {
	$id = get_the_author_meta( 'ID' );
	echo '<p class="entry-author"><a href="' . get_author_posts_url( $id ) . '" aria-hidden="true" tabindex="-1">' . get_avatar( $id, 40 ) . '</a><em>by</em> <a href="' . get_author_posts_url( $id ) . '">' . get_the_author() . '</a></p>';
}

/**
 * Post Date
 *
 */
function enercare_post_date() {
  echo '<p class="publish-date single-post__date">' . get_the_date( 'F j, Y' ) . '</p>';
}

/**
 * Entry Byline
 *
 */
function enercare_entry_byline() {
  $id = get_the_author_meta( 'ID' );
  $url = get_author_posts_url( $id );
  echo '<div class="post-meta">';
    echo get_avatar( $id, 40 );
      echo '<div class="post-meta__author-meta">';
        echo '<div class="post-meta__author-meta__name"><a href="' . $url . '">' . get_the_author() . '</a></div>';
        echo '<div class="post-meta__author-meta__date">'.get_the_date('F j').'</div>';
        echo do_shortcode( '[rt_reading_time label="" postfix="min read"]');
    echo '</div>';
    enercare_share_buttons();
  echo '</div>';
}

/**
 * Share
 */
function enercare_share_buttons() {
	$post_url = get_the_permalink();
	$post_title = rawurlencode( get_the_title() );
	echo '<ul class="single-post__share-menu">';
		//echo '<a class="share-menu__link dashicons dashicons-twitter" href="https://twitter.com/intent/tweet?text='.$post_title.'&url='.$post_url.'"><span class="screen-reader-text">Share '. get_the_title() .' on Twitter</span></a>';
    echo '<li class="share-menu__list-item"><a class="share-menu__link dashicons dashicons-facebook-alt" href="https://www.facebook.com/sharer.php?u='.$post_url.'"><span class="screen-reader-text">Share '. get_the_title() .' on Facebook</span></a></li>';
		echo '<li class="share-menu__list-item"><a class="share-menu__link dashicons dashicons-linkedin" href="https://www.linkedin.com/shareArticle?mini=true&url='.$post_url.'&title='.$post_title.'"><span class="screen-reader-text">Share '. get_the_title() .' on LinkedIn</span></a></li>';
	echo '</ul>';
}

/**
 * Related Posts
 *
 */
function enercare_related_posts($blog_num_posts = 4) {
  $terms = get_the_terms( get_the_id(), 'category' );
  $blog_cats = array();
  foreach($terms as $term) {
    $blog_cats[] = $term->term_id;
  }

  if (!$blog_num_posts) {
    $blog_num_posts = 4;
  }

  /*
   * Query Posts
   */
  $blog_post_args = array(
    'category__in' => $blog_cats,
    'posts_per_page' =>  $blog_num_posts
  );
  $blog_posts = new WP_Query( $blog_post_args );

  if( $blog_posts->have_posts()) {
    echo '<section class="wp-block-group alignfull"><div class="wp-block-group__inner-container">';
    echo '<h2 class="has-text-align-center">Related Articles</h2>';

    echo '<div class="block-blog-posts__wrapper">';
    echo '<div class="block-carousel block-blog-posts block-blog-posts--row">';
    while ( $blog_posts->have_posts() ) {
      $blog_posts->the_post();
      $excerpt = get_the_excerpt() ? get_the_excerpt() : get_the_content();
      echo '<div class="block-blog-posts__post block-blog-posts__post--vertical block-blog-posts__post--full">';
      echo '<a class="block-blog-posts__post__link" href="' . get_the_permalink() . '">';
      if ( has_post_thumbnail() ) {
        the_post_thumbnail( $post_image_size, array(
            'class' => 'block-blog-posts__post__image block-blog-posts__post__image--' . $post_image_size . '',
            'alt'   => ''
        ) );
      } elseif ( ! has_post_thumbnail() ) {
        $default_image = get_field( 'enercare_default_image', 'options' );
        echo wp_get_attachment_image( $default_image, $post_image_size, false, array(
            'class' => 'block-blog-posts__post__image block-blog-posts__post__image--' . $post_image_size . '',
            'alt'   => ''
        ) );
      }

      //Summary Container
      echo '<div class="block-blog-posts__post__summary">';
        echo '<div class="block-blog-posts__post__author-meta__date">'.get_the_date('F j, Y').'</div>';
            echo '<h3 class="block-blog-posts__post__title">' . get_the_title() . '</h3>';
            echo '<p class="block-blog-posts__post__except">' . wp_trim_words( $excerpt, 25 ) . '</p>';
        echo '</div>';
      echo '</div>';
    }
    echo '</a>';

    //End block-blog-posts__post container
    echo '</div>';

    echo '<div class="block-carousel__controls">';
	    echo '<button aria-label="Previous" class="glider-prev block-carousel__prev related-posts__glider-prev"><img alt="Previous" width="20" height="20" src="'. get_template_directory_uri() . '/assets/icons/utility/arrow_back.svg" /></button>';
	    echo '<div role="tablist" class="dots block-carousel__dots related-posts__glider-dots"></div>';
	    echo '<button aria-label="Next" class="glider-next block-carousel__next related-posts__glider-next"><img alt="Next" width="20" height="20" src="'.get_template_directory_uri() . '/assets/icons/utility/arrow_forward.svg" /></button>';
    echo '</div>';

    //End block-blog-posts__wrapper
    echo '</div>';

    echo '</div></section>';
  }

}
