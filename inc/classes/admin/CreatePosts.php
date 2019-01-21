<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 30.10.18
 * Time: 13:01
 */

namespace DeveloperHelper\admin;

use DeveloperHelper\AdminPages;
use DeveloperHelper\helpers\AjaxHelper;
use DeveloperHelper\helpers\Names;
use DeveloperHelper\helpers\PostTypes;

class CreatePosts extends AjaxHelper {

	static $page_title;
	static $page_slug;
	static $namespace;
	static $post_type;
	static $posts_ids = [];

	public function __construct() {
		self::$page_title = AdminPages::createPageTitle( get_class() );
		self::$page_slug  = AdminPages::createPageSlug( get_class() );

		add_action( 'admin_menu', array( $this, 'addSubpage' ) );

		self::declaration_ajax();
	}


	public function addSubpage() {
		add_submenu_page(
			AdminPages::$page_slug,
			self::$page_title,
			self::$page_title,
			'edit_posts',
			AdminPages::$page_slug,
			array( $this, 'pageInner' ) );
	}

	public function pageInner() {
		$namespace = Names::getMainNamespace( __NAMESPACE__ );

		add_action( 'admin_footer', array( "{$namespace}\\AdminPages", 'adminBrowserSync' ) );
		wp_enqueue_script( 'vuescript' );

		$template = Names::getTemplateName( __CLASS__ );
		AdminPages::renderView( $template, [
			'post_types' => PostTypes::getPostTypes()
		] );
	}

	public static function createMultiText( $texts ) {
		$langs = array_keys( wpm_get_lang_option() );
		$text  = '';
		foreach ( $texts as $lang => $lang_text ) {
			$lang = ( is_string( $lang ) ) ? $lang : $langs[ $lang ];

			if ( empty( $lang_text ) ) {
				continue;
			}

			$text .= '[:' . $lang . ']' . $lang_text;
		}
		if ( ! empty( $text ) ) {
			$text .= '[:]';
		}

		return $text;
	}

	public function ajax_CreatePosts() {
		$ids             = [];
		self::$post_type = self::getPostVar( 'current_type' );
		$list_input      = self::getPostVar( 'posts' );
		$list            = explode( "\n", $list_input );

		foreach ( $list as $row ) {
			$items = explode( ' | ', $row );
			$title = $items[0];
//            $title = self::createMultiText($items);

			$post_args = array(
				'post_title'  => $title,
				'post_name'   => apply_filters( 'sanitize_title', $title ),
				'post_type'   => self::$post_type,
				'post_status' => 'publish',
				'meta_input' => array(
					'_post_subtype' => 'clone'
				)
			);
			$ids[]     = wp_insert_post( $post_args );
		}

		wp_send_json_success( [ 'ids' => $ids ] );

		exit();
	}

	public static function ajax_UpdateField() {
		$list_input = self::getPostVar( 'input' );
		$list       = explode( "\n", $list_input );
		$repeater   = [];
		$field_name = 'services';
		$post_id    = 26;

		foreach ( $list as $row ) {
			$repeater[]['title'] = $row;
		}

		$services         = get_field( $field_name, $post_id );
		$services['list'] = $repeater;

		update_field( $field_name, $services, $post_id );

		wp_die();
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
