<?php
/*
Plugin Name: Featured Posts
Description: Enables ability to add lists of posts based on either IDs or category
Version: 1.0
Author: Paul Lyons
*/


function enqueue_files() {
	wp_enqueue_style('admin-styles', '/wp-content/plugins/featured-posts/css/featured-posts.css');
	wp_enqueue_script('acf_script', '/wp-content/plugins/featured-posts/js/featured-posts.js');
}

add_action('admin_enqueue_scripts', 'featured_posts');


function display_posts($atts){

	extract( shortcode_atts( array(
		'type' => 'featured',
		'post_ids' => array(),
		'category' => ''

	), $atts ) );

	enqueue_files();

	//2876, 2601, 2686, 2675, 2516

	$type = $atts[type];

	$post_ids = $atts[post_ids];
	$post_ids = explode(',', $post_ids);

	$category = $atts[category];

	switch( $type ){
		case 'recent':

			$args = array(
				'numberposts' => 6,
				'order' => 'DESC'
			);

			break;

		case 'featured':
			$args = array(
				'post__in' => $post_ids,
				'numberposts' => 6
			);
			break;

		case 'category':
			$args = array(
				'numberposts' => 6,
				'order' => 'DESC',
				'category' => $category
			);
			break;

		default:
			$args = array(
				'numberposts' => 6,
				'order' => 'DESC',
			);
			break;
	}

	$posts = get_posts($args);
	$c = '';

	echo '<div class="container-featured-posts">';
	foreach ($posts as $p) :


		$post_date =  new DateTime($p->post_date);
		$post_date = $post_date->format('d F Y');

		$c = $c . '<article>';
		$c = $c . '<a href="' . get_permalink($p->ID) . '">';
		$c = $c . '<div class="container-article">';

		$c = $c . '<div class="post-image" style="background-image: url(' . get_the_post_thumbnail_url( $p->ID, 'full' ) . ');">';

		$c = $c . '<div class="post-title">' .$p->post_title  . '</div>';
		$c = $c . '<div class="post-date">' . $post_date . '</div>';
		$c = $c . '<div class="post-author">BY ' . get_the_author_meta('display_name') . '</div>';
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

