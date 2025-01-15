<?php
/**
 * Program Post Type
 *
 * @package GusviraDigital
 * @version 1.0.0
 */

namespace GDP\Post_Types;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Program Post Type Class
 */
class Program {
    /**
     * Instance of this class.
     *
     * @var object
     */
    private static $instance;

    /**
     * Get instance of this class
     */
    public static function get_instance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_post_type' ] );
        add_action( 'init', [ $this, 'register_taxonomies' ] );
    }

    /**
     * Register post type
     */
    public function register_post_type() {
        $labels = [
            'name'               => _x( 'Program Donasi', 'post type general name', 'gusviradigital' ),
            'singular_name'      => _x( 'Program Donasi', 'post type singular name', 'gusviradigital' ),
            'menu_name'          => _x( 'Program Donasi', 'admin menu', 'gusviradigital' ),
            'name_admin_bar'     => _x( 'Program Donasi', 'add new on admin bar', 'gusviradigital' ),
            'add_new'            => _x( 'Tambah Baru', 'program', 'gusviradigital' ),
            'add_new_item'       => __( 'Tambah Program Baru', 'gusviradigital' ),
            'new_item'           => __( 'Program Baru', 'gusviradigital' ),
            'edit_item'          => __( 'Edit Program', 'gusviradigital' ),
            'view_item'          => __( 'Lihat Program', 'gusviradigital' ),
            'all_items'          => __( 'Semua Program', 'gusviradigital' ),
            'search_items'       => __( 'Cari Program', 'gusviradigital' ),
            'parent_item_colon'  => __( 'Program Induk:', 'gusviradigital' ),
            'not_found'          => __( 'Tidak ada program ditemukan.', 'gusviradigital' ),
            'not_found_in_trash' => __( 'Tidak ada program di tempat sampah.', 'gusviradigital' )
        ];

        $args = [
            'labels'             => $labels,
            'description'        => __( 'Program donasi description.', 'gusviradigital' ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'      => true,
            'query_var'          => true,
            'rewrite'           => [ 'slug' => 'program' ],
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-heart',
            'supports'           => [ 
                'title', 
                'editor', 
                'author', 
                'thumbnail', 
                'excerpt', 
                'comments',
                'custom-fields'
            ],
            'show_in_rest'      => true,
        ];

        register_post_type( 'program', $args );
    }

    /**
     * Register taxonomies
     */
    public function register_taxonomies() {
        // Kategori Program
        $category_labels = [
            'name'              => _x( 'Kategori Program', 'taxonomy general name', 'gusviradigital' ),
            'singular_name'     => _x( 'Kategori Program', 'taxonomy singular name', 'gusviradigital' ),
            'search_items'      => __( 'Cari Kategori', 'gusviradigital' ),
            'all_items'         => __( 'Semua Kategori', 'gusviradigital' ),
            'parent_item'       => __( 'Kategori Induk', 'gusviradigital' ),
            'parent_item_colon' => __( 'Kategori Induk:', 'gusviradigital' ),
            'edit_item'         => __( 'Edit Kategori', 'gusviradigital' ),
            'update_item'       => __( 'Update Kategori', 'gusviradigital' ),
            'add_new_item'      => __( 'Tambah Kategori Baru', 'gusviradigital' ),
            'new_item_name'     => __( 'Nama Kategori Baru', 'gusviradigital' ),
            'menu_name'         => __( 'Kategori', 'gusviradigital' ),
        ];

        $category_args = [
            'hierarchical'      => true,
            'labels'            => $category_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'kategori-program' ],
            'show_in_rest'      => true,
        ];

        register_taxonomy( 'program_category', [ 'program' ], $category_args );

        // Tag Program
        $tag_labels = [
            'name'              => _x( 'Tag Program', 'taxonomy general name', 'gusviradigital' ),
            'singular_name'     => _x( 'Tag Program', 'taxonomy singular name', 'gusviradigital' ),
            'search_items'      => __( 'Cari Tag', 'gusviradigital' ),
            'all_items'         => __( 'Semua Tag', 'gusviradigital' ),
            'edit_item'         => __( 'Edit Tag', 'gusviradigital' ),
            'update_item'       => __( 'Update Tag', 'gusviradigital' ),
            'add_new_item'      => __( 'Tambah Tag Baru', 'gusviradigital' ),
            'new_item_name'     => __( 'Nama Tag Baru', 'gusviradigital' ),
            'menu_name'         => __( 'Tag', 'gusviradigital' ),
        ];

        $tag_args = [
            'hierarchical'      => false,
            'labels'            => $tag_labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => [ 'slug' => 'tag-program' ],
            'show_in_rest'      => true,
        ];

        register_taxonomy( 'program_tag', [ 'program' ], $tag_args );
    }
}

// Initialize Program Post Type
Program::get_instance(); 