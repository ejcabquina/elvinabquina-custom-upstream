<?php
$author_id = get_the_author_meta('ID');

echo '<section class="author-bio">';
echo get_author_headshot(false, 'author-bio__image');
echo '<div class="author-bio__bio-container">';
echo '<h2 class="author-bio__name">' . get_the_author_meta('display_name') . '</h2>';
echo '<h3 class="author-bio__title">' . get_field( 'job_title', 'user_'.$author_id ). '</h3>';
echo '<p class="author-bio__description">' . get_the_author_meta( 'description' ). '</p>';
if(!is_author()) {
	echo '<a class="author-bio__link" href="' . get_author_posts_url($author_id) . '">Read More Posts by ' . get_the_author_meta('first_name') . '</a>';
}

if(have_rows( 'social_networks', 'user_'.$author_id) ) {
	echo '<div class="author-bio__networks">';
	while( have_rows( 'social_networks', 'user_'.$author_id) ) {
		the_row();
		echo '<a class="networks__link dashicons dashicons-'.strtolower(get_sub_field('social_network')).'" target="_blank" href="'.get_sub_field('url').'"><span class="screen-reader-text">'.get_sub_field('social_network').'</span></a>';
	}
	echo '</div>';
}
echo '</div>';
echo '</section>';
