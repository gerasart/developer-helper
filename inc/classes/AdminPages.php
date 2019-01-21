<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 14.09.18
 * Time: 15:06
 */

namespace DeveloperHelper;

use DeveloperHelper\helpers\Names;
use DeveloperHelper\helpers\TemplateLoader;
use HaydenPierce\ClassFinder\ClassFinder;

class AdminPages {

    private static $page_title;
    private static $page_subtitle;

    private static $rel_path = 'inc/classes/';
    private static $template_path = 'templates/creator/admin/';

    static $page_slug;

    public function __construct() {
        self::$page_title    = __( 'Demo creator');
        self::$page_subtitle = __( 'Demo creator');
//        self::$page_slug = self::createPageSlug(get_class());
        self::$page_slug = 'demo-creator';

        add_action('admin_menu', array(__CLASS__, 'addMainPage'));

        self::registerSubpage();
    }

    public static function getTrueName($class) {
        return Names::getTrueClassName($class);
    }

    public static function createPageTitle($class) {
        $true_name = Names::getTrueClassName($class);
        $lower = ucwords(preg_replace('/[A-Z]/', ' $0', $true_name));

        return substr($lower, 1);
    }

    public static function createPageSlug($class) {
        $true_name = Names::getTrueClassName($class);
//        var_dump($true_name);
        $lower = strtolower(preg_replace('/[A-Z]/', '_$0', $true_name));
//	    var_dump($lower);

        return substr($lower, 1);
    }

    public static function addMainPage() {
        add_menu_page(self::$page_title, self::$page_title, 'edit_posts', self::$page_slug, null, 'dashicons-forms');
    }

    public static function registerSubpage() {
    	// Fix for multiple instance, path to composer.json
	    ClassFinder::setAppRoot(\DeveloperHelper::$plugin_dir);

        $level = error_reporting(E_ERROR);
        $classes = ClassFinder::getClassesInNamespace(__NAMESPACE__ . '\admin');
        error_reporting($level);

        foreach($classes as $class) {
            new $class();
        }
    }

    public static function renderView( $file, $args = array() ) {
        $fullpath = self::$template_path . $file;

        TemplateLoader::render_template_part($fullpath, null, $args, true, self::$rel_path);
    }

    public static function adminBrowserSync() {
        self::renderView( 'browser_sync.php' );
    }

}
