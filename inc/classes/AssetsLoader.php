<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 06.11.18
 * Time: 9:40
 */

namespace DeveloperHelper;

use DeveloperHelper\helpers\PostTypes;


class AssetsLoader {

	private static $rel_path = 'inc/classes/';

	static $plugin_dir;
	static $plugin_path;

	public function __construct() {
		self::$plugin_dir  = str_replace( self::$rel_path, '', plugin_dir_url( __FILE__ ) );
		self::$plugin_path = str_replace( self::$rel_path, '', plugin_dir_path( __FILE__ ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_front' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin' ) );
	}


	public function enqueue_admin() {
		$this->enqueue( 'admin' );
//		if ( $_SERVER['REQUEST_URI'] == '/wp-admin/admin.php?page=admin_pages') {
		$this->simpleRegister( 'vuescript', self::$plugin_dir . 'inc/js/bundle.js' );
//		}
	}

	public function enqueue_front() {
		$this->enqueue( 'front-*' );
	}

	public function enqueue( $mask = '*', $folder = '', $in_footer = false, $depends = array() ) {
		$styles_url  = self::$plugin_dir . 'inc/css/';
		$scripts_url = self::$plugin_dir . 'inc/js/';

		foreach ( glob( self::$plugin_path . 'inc/css/' . $folder . $mask . '.css' ) as $file ) {
			/* Enqueue CSS */
			$name = $this->get_filename( $file );
			if ( $name[1] !== 'map' ) {
				wp_register_style( $name[0], $styles_url . $folder . $name[1], $depends, '1', 'all' );
				wp_enqueue_style( $name[0] );
			}
		}

		foreach ( glob( self::$plugin_path . 'inc/js/' . $folder . $mask . '.js' ) as $file ) {
			/* Enqueue Scripts */
			$name = $this->get_filename( $file );
			// wp_register_script($name[0], $scripts_url . $folder . $name[1], array('jquery'), NULL, $in_footer);
			wp_register_script( $name[0], $scripts_url . $folder . $name[1], array( 'jquery' ), null, true );
			wp_enqueue_script( $name[0] );
		}
	}

	public function simpleRegister( $name, $path ) {
		wp_register_script( $name, $path, array( 'jquery' ), null, true );
//		wp_enqueue_script( $name );

//		wp_localize_script( 'jquery', 'demo_data',
//			array(
//				'post_types' => PostTypes::getPostTypes(),
//			)
//		);
	}

	public function get_filename( $file ) {
		$basename = basename( $file );
		$exp      = explode( '.', $basename );
		array_pop( $exp );

		$parts   = array();
		$parts[] = implode( '.', $exp );
		$parts[] = $basename;

		return $parts;
	}

}