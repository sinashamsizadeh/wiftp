<?php
/**
 * Plugin Name:     WP Import from third party WordPress site
 * Plugin URI:      https://awecodebox.org/plugins/wiftp/
 * Description:     WP Import from third party site help to import other WordPress site post, page, products, and any other post types on your WordPress site.
 * Version:         1.0.0
 * Author:          AweCodeBox
 * Author URI:      https://awecodebox.org/
 * License:         GUN-2.0+
 * License URI:     https://www.gnu.org/licenses/gpl-3.0.en.html
 * Text Domain:     wiftp
 * Domain Path:     /languages
 *
 * The plugin bootstrap file
 *
 * @author  AweCodeBox
 * @package wiftp
 * @since   1.0.0
 */

add_action('wp_ajax_insert_products', 'insert_products');
add_action('wp_ajax_nopriv_insert_products', 'insert_products');
add_action( 'wp_footer', function() {
echo '<script>';
	echo "jQuery.ajax({
		url: wc_add_to_cart_params.ajax_url,
		type: 'POST',
		data: {
		action: 'insert_products',
		},
		success: function (data) {
		console.log(data);
		},
	});";
echo '</script>';
}, 999 );

function insert_products() {
	
	for ( $i = 1; $i < 9 ; $i++ ) { 
		$ch = curl_init( 'https://example.com/wp-json/wp/v2/pages/?page=' . $i );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
		$output = curl_exec( $ch );
		$result = json_decode( $output, true );

		foreach ( $result as $post ) {

			$post_id = get_page_by_title( $post_title, ARRAY_A, $post_type = 'product' );

			if ( ! $post_id ) {

				if ( strpos( $post['link'], 'wireless-communication-system' ) ) {
					
					$args = [
						'post_content' => $post['content']['rendered'],
						'post_title' => $post['title']['rendered'],
						'post_excerpt' => $post['excerpt']['rendered'],
						'post_status' => 'publish',
						'post_type' => 'product',
						'post_modified' => $post["modified"],
						'post_modified_gmt' => $post["modified_gmt"],
					]; 
					wp_insert_post( $args );
				}
			}
		}
	}
	wp_die();
}