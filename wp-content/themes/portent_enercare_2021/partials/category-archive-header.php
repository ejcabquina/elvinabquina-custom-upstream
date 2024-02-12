<?php
/**
 * This partial is used to DRY out the header code used for category and taxonomy templates.
 * This template relies on variables setup within archive templates and helper functions, such as breadcrumbs
 */
$title = $subtitle = $description = false;

if( is_archive() ) {
	$title = get_the_archive_title();
	if( ! get_query_var( 'paged' ) )
		$description = get_the_archive_description();
}

if( empty( $title ) && empty( $description ) )
	return;

$classes = [ 'archive-description', 'archive-description--category' ];
if( is_author() )
	$classes[] = 'author-archive-description';

echo '<header class="' . join( ' ', $classes ) . '">';
	do_action ('enercare_archive_header_before' );

	echo '<div class="category__description__wrapper">';
			echo '<div class="category__description__inner">';
			if( ! empty( $title ) )
				echo '<h1 class="archive-title">' . $title . '</h1>';
			if( !empty( $subtitle ) )
				echo '<h4>' . $subtitle . '</h4>';

			echo '<div class="category__description is-style-subhead1">'.apply_filters( 'enercare_the_content', $description ).'</div>';
		echo '</div>';
	echo '</div>';

	echo '<div class="archive-header__after">';
	do_action ('enercare_archive_header_after' );
	echo '</div>';
echo '</header>';
