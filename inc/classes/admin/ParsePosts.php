<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 14.12.18
 * Time: 17:21
 */

namespace DeveloperHelper\admin;


use DeveloperHelper\AdminPages;
use DeveloperHelper\helpers\AjaxHelper;
use DeveloperHelper\helpers\Names;
use DeveloperHelper\helpers\PostCreator;
use DeveloperHelper\helpers\PostTypes;
use DeveloperHelper\helpers\TemplateLoader;

class ParsePosts extends AjaxHelper {
	static $page_title;
	static $page_slug;
	static $true_name;
	static $namespace;
	static $post_type;
	static $posts_ids = [];

	public function __construct() {
		self::$page_title = AdminPages::createPageTitle( get_class() );
		self::$page_slug  = AdminPages::createPageSlug( get_class() );
		self::$true_name  = AdminPages::getTrueName( get_class() );

		add_action( 'admin_menu', array( $this, 'addSubpage' ) );

		self::declaration_ajax();
	}


	public function addSubpage() {
		add_submenu_page(
			AdminPages::$page_slug,
			self::$page_title,
			self::$page_title,
			'edit_posts',
			self::$page_slug,
//			self::$page_slug . '#/' . self::$true_name,
//			AdminPages::$page_slug . '#/' . self::$true_name,
			array( $this, 'pageInner' ) );
	}

	public function pageInner() {
		$namespace = Names::getMainNamespace( __NAMESPACE__ );

		add_action( 'admin_footer', array( "{$namespace}\\AdminPages", 'adminBrowserSync' ) );
		wp_enqueue_script( 'vuescript' );

		TemplateLoader::localizeArgs(['post_types' => PostTypes::getPostTypes()]);

		$template = Names::getTemplateName( __CLASS__ );
		AdminPages::renderView( $template, [
			'types' => PostTypes::getPostTypes()
		] );
	}

	public function ajax_ImportPost() {
		$fields_input = self::getPostVar( 'fields' );
		parse_str( $fields_input, $fields );

		$response = self::sendApiRequest( $fields["site_url"], $fields["route"], $fields["post_id"] );

		$post_id = PostCreator::createPost( $fields["post_type"], $response );

		if ( $post_id ) {
			wp_send_json_success( [ 'message' => "Create post with ID: {$post_id}" ] );
		} else {
			wp_send_json_error( [ 'message' => 'Post not created!' ] );
		}

		wp_die();
	}

	public static function sendApiRequest( $base, $route, $id = false ) {
		$path = $base . "wp-json/route/{$route}/";

		if ( $id ) {
			$path .= $id;
		}

		$response = wp_remote_request( $path );

		return json_decode( wp_remote_retrieve_body( $response ) );
	}
}