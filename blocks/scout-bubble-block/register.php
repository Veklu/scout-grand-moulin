<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

add_filter( 'block_categories_all', function ( $categories ) {
    foreach ( $categories as $cat ) {
        if ( isset( $cat['slug'] ) && $cat['slug'] === 'scout-grandmoulin' ) {
            return $categories;
        }
    }
    array_unshift( $categories, [
        'slug'  => 'scout-grandmoulin',
        'title' => '⚜️ ' . __('Grand-Moulin', 'scout-gm'),
        'icon'  => null,
    ] );
    return $categories;
}, 10, 2 );

add_action( 'init', function () {

    $base_url = get_template_directory_uri() . '/blocks/scout-bubble-block/src';
    $base_dir = get_template_directory()     . '/blocks/scout-bubble-block/src';

    wp_register_script(
        'scout-gm-bubble-editor',
        $base_url . '/editor.js',
        [ 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ],
        filemtime( $base_dir . '/editor.js' ),
        true
    );

    wp_register_style(
        'scout-gm-bubble-editor-style',
        $base_url . '/editor.css',
        [ 'wp-edit-blocks' ],
        filemtime( $base_dir . '/editor.css' )
    );

    wp_register_style(
        'scout-gm-bubble-style',
        $base_url . '/style.css',
        [],
        filemtime( $base_dir . '/style.css' )
    );

    register_block_type( 'scout-gm/info-bubble', [
        'editor_script' => 'scout-gm-bubble-editor',
        'editor_style'  => 'scout-gm-bubble-editor-style',
        'style'         => 'scout-gm-bubble-style',
        'attributes'    => [
            'icon'        => [ 'type' => 'string', 'default' => '🏕️' ],
            'title'       => [ 'type' => 'string', 'default' => '' ],
            'body'        => [ 'type' => 'string', 'default' => '' ],
            'colorScheme' => [ 'type' => 'string', 'default' => 'green' ],
        ],
    ] );
} );
