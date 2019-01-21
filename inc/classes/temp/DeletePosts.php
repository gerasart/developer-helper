<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 30.10.18
 * Time: 13:01
 */

namespace DeveloperHelper\admin;

use DeveloperHelper\helpers\AjaxHelper;

class DeletePosts extends AjaxHelper {

	static $post_type;
	static $posts_ids = [];

	public function __construct() {
		self::declaration_ajax();
	}

	public function ajax_DeletePosts() {

		self::$post_type = self::getPostVar( 'current_type' );

		$args = array(
			'numberposts' => -1,
			'order'       => 'DESC',
			'meta_key'    => '_post_subtype',
			'meta_value'  =>'clone',
			'post_type'   => self::$post_type,
		);
		$posts = get_posts($args);

		foreach ($posts as $post) {
			self::$posts_ids[] = $post->ID;
		}
		print_r(self::$posts_ids);
		foreach ( self::$posts_ids as $id ) {
			wp_delete_post( $id );
		}
		wp_reset_postdata();

		wp_send_json_success( [ 'delete' => self::$posts_ids ] );

		exit();
	}

}
