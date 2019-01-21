<?php
/**
 * Created by PhpStorm.
 * User: skipin
 * Date: 14.12.18
 * Time: 18:35
 */

namespace DeveloperHelper\helpers;


class PostCreator {
    public static function createPost($post_type, $post_data) {
//        $short_title = apply_filters('translate_text', $post_data->title);
        $post_args = array(
            'post_title' => $post_data->title,
            'post_name' => apply_filters( 'sanitize_title', $post_data->title),
            'post_content' => $post_data->content,
            'post_type' => $post_type,
//            'post_status' => 'draft'
        );

        $id = wp_insert_post($post_args);
        $post_id = (!is_wp_error($id)) ? $id : false;

        if ( !$post_id ) {
            return false;
        }

        if ( isset($post_data->thumbnail) ) {
            $attachment_id = ImageUploader::upload($post_data->thumbnail, $post_id);

            if ( $attachment_id ) {
                set_post_thumbnail( $post_id, $attachment_id );
            }
        }

        if (isset($post_data->taxonomies) && !empty($post_data->taxonomies)) {
            foreach ( $post_data->taxonomies as $taxonomy => $terms ) {
                foreach ($terms as $term_slug) {
                    $term_id = self::createTerm($term_slug, $taxonomy);

                    if ( $term_id ) {
                        wp_set_object_terms($post_id, $term_id, $taxonomy, true);
                    }
                }
            }
        }

        if (isset($post_data->fields) && !empty($post_data->fields)) {
            foreach ($post_data->fields as $field => $value) {
                update_field($field, $value, $post_id);
            }
        }

        return $post_id;
    }

    public static function createTerm($term_slug, $taxonomy) {
        $term = get_term_by('slug', $term_slug, $taxonomy);

        if ($term) {
            $term_id = $term->term_id;
        } else {
            $data = wp_insert_term($term_slug, $taxonomy);
            $term_id = (!is_wp_error($data)) ? $data['term_id'] : false;
        }

        return $term_id;
    }
}