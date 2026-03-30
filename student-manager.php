<?php
/**
 * Plugin Name: Student Manager
 * Description: Plugin quản lý sinh viên với Custom Post Type và Shortcode hiển thị danh sách.
 * Version: 1.0
 * Author: Tên của bạn
 */

// Ngăn chặn truy cập trực tiếp vào file
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Nhúng các file xử lý logic từ thư mục includes
require_once plugin_dir_path( __FILE__ ) . 'includes/cpt-metabox.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/shortcode.php';

// Load file CSS để làm đẹp cho bảng hiển thị
function sm_enqueue_assets() {
    wp_enqueue_style( 'sm-style', plugin_dir_url( __FILE__ ) . 'assets/style.css' );
}
add_action( 'wp_enqueue_scripts', 'sm_enqueue_assets' );