<?php
/*
Plugin Name: Featured Posts
Description: Enables ability to add lists of posts based on either IDs or category
Version: 1.0
Author: Paul Lyons
*/



function enqueue_files() {
	wp_enqueue_style('featured-posts-styles', '/wp-content/plugins/featured-posts/css/featured-posts.css');
	wp_enqueue_script('featured-posts-js', '/wp-content/plugins/featured-posts/js/featured-posts.js', '', '', true);
}

add_action('wp_enqueue_scripts', 'enqueue_files');



function display_posts($atts){

	$args = shortcode_atts(
		array(
			'type' => 'featured',
			'post_ids' => array(),
			'category' => '',
			'numberposts' => 6
		),
		$atts
	);

	//2876, 2601, 2686, 2675, 2516

	$type = esc_attr( $args['type'] );
	$numberposts = (int) $args['numberposts'];

	$post_ids = esc_attr($args['post_ids']);
	$post_ids = explode(',', $post_ids);

	$category =  esc_attr( $args['category'] );


	switch( $type ){
		case 'recent':

			$args = array(
				'numberposts' => $numberposts,
				'order' => 'DESC'
			);

			break;

		case 'featured':
			$args = array(
				'numberposts' => $numberposts,
				'order' => 'DESC',
				'post__in' => $post_ids
			);
			break;

		case 'category':
			$args = array(
				'numberposts' => $numberposts,
				'order' => 'DESC',
				'category' => $category
			);
			break;

		default:
			$args = array(
				'numberposts' => $numberposts,
				'order' => 'DESC',
			);
			break;
	}

	$posts = get_posts($args);
	$c = '';

	$c = $c . '<div class="container-featured-posts">';
	foreach ($posts as $p) :
		$post_date =  new DateTime($p->post_date);
		$post_date = $post_date->format('d F Y');

		$c = $c . '<article>';
		$c = $c . '<a href="' . get_permalink($p->ID) . '">';
		$c = $c . '<div class="container-article">';

		$c = $c . '<div class="post-image" style="background-image: url(' . get_the_post_thumbnail_url( $p->ID, 'large' ) . ');">';
		$c = $c . '<img class="img-mobile" src="' .  get_the_post_thumbnail_url( $p->ID, 'large' ) . '"  />';
		$c = $c . '<div class="container-article-details">';
		$c = $c . '<div class="post-title">' .$p->post_title  . '</div>';
		$c = $c . '<div class="post-date">' . $post_date . '</div>';
		$c = $c . '<div class="post-author">BY ' . get_the_author_meta('display_name') . '</div>';
		$c = $c . '</div>';

		$c = $c . '</div>';

		$c = $c . '</div>';
		$c = $c . '</a>';
		$c = $c . '</article>';

	endforeach;

	$c = $c . '</div>';

	return $c;

}
add_shortcode('show_posts', 'display_posts');
?>