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

	$parent_id = get_the_ID();

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
				'order' => 'DESC',
				'exclude' => $parent_id
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
	$str = '';

	$str .=  '<div class="container-featured-posts">';
	foreach ($posts as $p) :
		$post_date =  new DateTime($p->post_date);
		$post_date = $post_date->format('F j, Y');

		$categories_post = wp_get_post_categories( $p->ID );
		$cat_str = '';


		foreach($categories_post as $c){
			$cat = get_category( $c );
			//get the name of the category
			$cat_id = get_cat_ID( $cat->name );
			if($cat_id !== 3){
				$cat_str .= '<a href="'.get_category_link($cat_id).'">'.$cat->name.'</a>, ';
			}
		}

		$cat_str = substr_replace($cat_str,"",-2);


		$str .=   '<article class="blog-item">';
		$str .=   '<div class="container-blog-item" style="background-image: url(' . get_the_post_thumbnail_url( $p->ID, 'thumbnail' ) . ');">';
		$str .=   '<h2 class="blog-title"><a href="' . get_permalink($p->ID) . '">' .$p->post_title  . '</a>';
		$str .=   '<a href="' . get_permalink($p->ID) . '" class="read-more">Read More</a>';
		$str .=   '</h2>';
		$str .=   '</div>';
		$str .=   '<div class="container-meta">';
		$str .=   '<ul class="blog-item-info blog-item-info-archive">';
		$str .=   '<li><span class="blog-date">' . $post_date . '</span></li>';
		$str .=   '<li class="clear-left"><span class="blog-categories">' . $cat_str . '</span></li>';
		$str .=   '</ul>';
		$str .=   '</div>';
		$str .=   '</article>';

	endforeach;

	$str .=   '</div>';

	return $str;

}
add_shortcode('show_posts', 'display_posts');
?>