<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function sm_student_list_shortcode() {
    // Dùng Output Buffer để gom HTML lại thay vì in ra ngay lập tức
    ob_start();

    // Truy vấn lấy tất cả bài viết thuộc type 'sinh_vien'
    $args = array(
        'post_type'      => 'sinh_vien',
        'posts_per_page' => -1, // Lấy tất cả
        'post_status'    => 'publish'
    );
    $query = new WP_Query( $args );

    // Bắt đầu vẽ bảng
    echo '<table class="sm-student-table">';
    echo '<thead>';
    echo '<tr><th>STT</th><th>MSSV</th><th>Họ tên</th><th>Lớp</th><th>Ngày sinh</th></tr>';
    echo '</thead>';
    echo '<tbody>';

    if ( $query->have_posts() ) {
        $stt = 1;
        while ( $query->have_posts() ) {
            $query->the_post();
            $post_id = get_the_ID();
            
            // Lấy dữ liệu meta
            $mssv = get_post_meta( $post_id, '_sm_mssv', true );
            $lop = get_post_meta( $post_id, '_sm_lop', true );
            $ngay_sinh = get_post_meta( $post_id, '_sm_ngay_sinh', true );

            // Nếu ngày sinh có tồn tại, định dạng lại kiểu dd/mm/yyyy cho đẹp
            if ( !empty($ngay_sinh) ) {
                $ngay_sinh = date('d/m/Y', strtotime($ngay_sinh));
            }

            echo '<tr>';
            echo '<td>' . $stt . '</td>';
            echo '<td>' . esc_html( $mssv ) . '</td>';
            echo '<td>' . get_the_title() . '</td>';
            echo '<td>' . esc_html( $lop ) . '</td>';
            echo '<td>' . esc_html( $ngay_sinh ) . '</td>';
            echo '</tr>';

            $stt++;
        }
        wp_reset_postdata(); // Trả lại dữ liệu post gốc của WordPress
    } else {
        echo '<tr><td colspan="5">Chưa có sinh viên nào.</td></tr>';
    }

    echo '</tbody>';
    echo '</table>';

    // Trả về nội dung HTML
    return ob_get_clean();
}
// Đăng ký shortcode
add_shortcode( 'danh_sach_sinh_vien', 'sm_student_list_shortcode' );