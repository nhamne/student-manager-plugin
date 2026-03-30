<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 1. Đăng ký Custom Post Type "Sinh viên"
function sm_register_student_cpt() {
    $args = array(
        'labels'      => array(
            'name'          => 'Sinh viên',
            'singular_name' => 'Sinh viên',
            'add_new'       => 'Thêm Sinh viên',
            'add_new_item'  => 'Thêm Sinh viên mới',
            'edit_item'     => 'Sửa thông tin',
        ),
        'public'      => true,
        'has_archive' => true,
        'menu_icon'   => 'dashicons-welcome-learn-more',
        'supports'    => array( 'title', 'editor' ), // Hỗ trợ Tiêu đề (Họ tên) và Editor (Tiểu sử/Ghi chú)
    );
    register_post_type( 'sinh_vien', $args );
}
add_action( 'init', 'sm_register_student_cpt' );

// 2. Tạo Custom Meta Box
function sm_add_student_metabox() {
    add_meta_box(
        'sm_student_info',      // ID của metabox
        'Thông tin chi tiết Sinh viên', // Tiêu đề
        'sm_render_student_metabox', // Hàm hiển thị HTML
        'sinh_vien',            // Hiển thị ở post type 'sinh_vien'
        'normal',               // Vị trí
        'high'                  // Độ ưu tiên
    );
}
add_action( 'add_meta_boxes', 'sm_add_student_metabox' );

// 3. Hiển thị form nhập liệu trong Meta Box
function sm_render_student_metabox( $post ) {
    // Tạo Nonce để bảo mật
    wp_nonce_field( 'sm_save_student_data', 'sm_student_nonce' );

    // Lấy dữ liệu cũ (nếu có) để hiển thị lại vào ô input
    $mssv = get_post_meta( $post->ID, '_sm_mssv', true );
    $lop = get_post_meta( $post->ID, '_sm_lop', true );
    $ngay_sinh = get_post_meta( $post->ID, '_sm_ngay_sinh', true );

    ?>
    <p>
        <label for="sm_mssv"><strong>Mã số sinh viên (MSSV):</strong></label><br>
        <input type="text" id="sm_mssv" name="sm_mssv" value="<?php echo esc_attr( $mssv ); ?>" style="width: 100%;" />
    </p>
    <p>
        <label for="sm_lop"><strong>Lớp/Chuyên ngành:</strong></label><br>
        <select id="sm_lop" name="sm_lop" style="width: 100%;">
            <option value="CNTT" <?php selected( $lop, 'CNTT' ); ?>>Công nghệ thông tin</option>
            <option value="Kinh tế" <?php selected( $lop, 'Kinh tế' ); ?>>Kinh tế</option>
            <option value="Marketing" <?php selected( $lop, 'Marketing' ); ?>>Marketing</option>
        </select>
    </p>
    <p>
        <label for="sm_ngay_sinh"><strong>Ngày sinh:</strong></label><br>
        <input type="date" id="sm_ngay_sinh" name="sm_ngay_sinh" value="<?php echo esc_attr( $ngay_sinh ); ?>" style="width: 100%;" />
    </p>
    <?php
}

// 4. Lưu dữ liệu an toàn (Sanitize)
function sm_save_student_metabox( $post_id ) {
    // Kiểm tra nonce có hợp lệ không
    if ( ! isset( $_POST['sm_student_nonce'] ) || ! wp_verify_nonce( $_POST['sm_student_nonce'], 'sm_save_student_data' ) ) {
        return;
    }
    // Ngăn chặn việc lưu tự động (autosave)
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    // Kiểm tra quyền của user
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    // Sanitize và lưu MSSV
    if ( isset( $_POST['sm_mssv'] ) ) {
        update_post_meta( $post_id, '_sm_mssv', sanitize_text_field( $_POST['sm_mssv'] ) );
    }
    // Sanitize và lưu Lớp
    if ( isset( $_POST['sm_lop'] ) ) {
        update_post_meta( $post_id, '_sm_lop', sanitize_text_field( $_POST['sm_lop'] ) );
    }
    // Sanitize và lưu Ngày sinh
    if ( isset( $_POST['sm_ngay_sinh'] ) ) {
        update_post_meta( $post_id, '_sm_ngay_sinh', sanitize_text_field( $_POST['sm_ngay_sinh'] ) );
    }
}
add_action( 'save_post', 'sm_save_student_metabox' );