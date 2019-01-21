<?php
/*
 * Plugin Name: Demo plugin creator
 * Version: 5.0
 * Plugin URI: http://www.hughlashbrooke.com/
 * Description: This is your starter template for your next WordPress plugin.
 * Author: Chameleon
 * Author URI: http://www.hughlashbrooke.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: demo-creator
 * Domain Path: /languages/
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 */

if ( !defined( 'ABSPATH' ) ) exit;

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

use HaydenPierce\ClassFinder\ClassFinder;
class DeveloperHelper {

    static $plugin_dir;

    private static $basedir;
    private static $namespace = 'DeveloperHelper';

    public function __construct() {
	    self::$plugin_dir = plugin_dir_path( __FILE__ );
        self::$basedir = plugin_dir_path( __FILE__ ) . '/inc/classes/';

        self::cc_autoload();

    }

    private static function cc_autoload() {
        foreach (glob(self::$basedir . '*.*') as $file) {
            include_once ( self::$basedir . basename($file) );
        }

        $namespaces = self::getDefinedNamespaces();
        foreach ($namespaces as $namespace => $path) {
            $clear = str_replace('\\', '', $namespace);

	        ClassFinder::setAppRoot( self::$plugin_dir );
            $level = error_reporting(E_ERROR);
            $classes = ClassFinder::getClassesInNamespace( $clear );
            error_reporting($level);

            foreach ( $classes as $class ) {
                new $class();
            }
        }
    }

    private static function getDefinedNamespaces()
    {
        $composerJsonPath = dirname( __FILE__ ) . '/composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        //Apparently PHP doesn't like hyphens, so we use variable variables instead.
        $psr4 = "psr-4";
        return (array) $composerConfig->autoload->$psr4;
    }
}

new DeveloperHelper();


require './inc/plugin-update-checker/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/gerasart/developer-helper/',
	__FILE__,
	'developer-helper'
);

$myUpdateChecker->setAuthentication('a283aeca2b507dd9d43b8e5b0cf8f6a3e8be50ad');
$myUpdateChecker->setBranch('master');

$myUpdateChecker->getVcsApi()->enableReleaseAssets();

//#
//include_once dirname(__FILE__) . '/inc/classes/_autoload.php';