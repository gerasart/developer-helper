<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 06.11.18
 * Time: 14:49
 */

namespace DeveloperHelper;


use DeveloperHelper\helpers\AjaxHelper;
use DeveloperHelper\helpers\DomCreator;
use DeveloperHelper\helpers\MultiLanguages;
use DeveloperHelper\helpers\PostTypes;

class CloneMetaBox extends AjaxHelper {

    private static $post_types = [];

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'registerMetaBox'));

        self::declaration_ajax();
    }

    public function registerMetaBox() {
//        $post_type = (!empty(self::$post_types)) ? self::$post_types : PostTypes::getPostTypes();

        add_meta_box(
            'post-clone',
            'Clone',
            array($this, 'buildForm'),
            self::$post_types,
            'side',
            'low');
    }

    public function buildForm() {
        $dom_creator = new DomCreator();

        $main = $dom_creator::dom_create('div', false, '', [
            'style' => 'display: flex; justify-content: space-around;'
        ]);

        $dom_creator::dom_create('input', $main, '', [
            'type' => 'number',
            'min' => '0',
            'max' => '20',
            'data-id' => 'clone_count',
            'style' => 'max-width: 60%;',
        ]);

        $dom_creator::dom_create('span', $main, 'Create', [
            'class' => 'button',
            'id' => 'clone-post',
            'data-ajax' => 'ClonePost',
            'data-input' => 'clone_count',
            'data-fields' => 'hidden-fields',
//            'value' => 'Create',
        ]);

        $hidden = $dom_creator::dom_create('div', false, '', [
            'data-id' => 'hidden-fields'
        ]);

        $dom_creator::dom_create('input', $hidden, '', [
            'type' => 'hidden',
            'name' => 'current-post',
            'value' => self::getGetVar('post')
        ]);

        $dom_creator::dom_create('input', $hidden, '', [
            'type' => 'hidden',
            'name' => 'new-status',
            'value' => 'publish'
        ]);


        echo $dom_creator::saveHtml();
    }
    
    public static function ajax_ClonePost() {
        $count = intval( self::getPostVar('input') );
        $fields_input = self::getPostVar( 'fields' );
        parse_str( $fields_input, $fields );

        $new_post_status = $fields['new-status'];

        $current_id = intval( $fields['current-post'] );
        $post = get_post($current_id);

        $new_post_author = wp_get_current_user();
        $new_post_author_id = $new_post_author->ID;

//        $new_title = MultiLanguages::addToTitle($post->post_title, ' 1');

        for($i=0 ; $i < $count ; $i++ ) {
            $title =  MultiLanguages::addToTitle($post->post_title, ' ' . ($i+1));
            $new_post = array(
                'post_author'    => $new_post_author_id,
                'post_content'   => $post->post_content,
                'post_excerpt'   => $post->post_excerpt,
                'post_mime_type' => $post->post_mime_type,
                'post_status'    => $new_post_status,
                'post_title'     => $title,
                'post_type'      => $post->post_type,
            );

            $new_post_id = wp_insert_post(wp_slash($new_post));

            PostTypes::copyMetaFields($current_id, $new_post_id);

            add_post_meta($new_post_id, '_post_subtype', 'clone');
        }

        wp_die();
    }

}