<?php

return [
    'title' => 'Cài đặt',
    'general' =>
        [
            'theme' => 'Giao diện',
            'description' => 'Cấu hình những thông tin cài đặt website.',
            'title' => 'Thông tin chung',
            'general_block' => 'Thông tin chung',
            'site_title' => 'Tên trang',
            'admin_email' => 'Email quản trị viên',
            'seo_block' => 'Cấu hình SEO',
            'seo_title' => 'Tiêu đề SEO',
            'seo_description' => 'Mô tả SEO',
            'seo_keywords' => 'Từ khoá SEO',
            'webmaster_tools_block' => 'Google Webmaster Tools',
            'google_site_verification' => 'Google site verification',
            'enable_captcha' => 'Sử dụng Captcha?',
            'contact_block' => 'Thông tin liên hệ',
            'show_admin_bar' => 'Hiển thị thanh quản trị (Khi quản trị viên đã đăng nhập, thanh quản trị luôn hiển thị trên website)?',
            'placeholder' =>
                [
                    'site_title' => 'Tên trang (tối đa 120 kí tự)',
                    'admin_email' => 'Admin Email',
                    'google_analytics' => 'Ví dụ: UA-42767940-2',
                    'google_site_verification' => 'Mã xác nhận trang web dùng cho Google Webmaster Tool',
                    'seo_title' => 'Tiêu đề SEO (tối đa 120 kí tự)',
                    'seo_description' => 'Mô tả SEO (tối đa 120 kí tự)',
                    'seo_keywords' => 'Từ khoá SEO (tối đa 60 kí tự, phân cách từ khóa bằng dấu phẩy)',
                ],
            'enable_change_admin_theme' => 'Cho phép thay đổi giao diện quản trị?',
            'enable_multi_language_in_admin' => 'Cho phép thay đổi ngôn ngữ trang quản trị?',
            'enable' => 'Bật',
            'disable' => 'Tắt',
            'enable_cache' => 'Bật bộ nhớ đệm?',
            'cache_time' => 'Thời gian lưu bộ nhớ đệm',
            'cache_time_site_map' => 'Thời gian lưu sơ đồ trang trong bộ nhớ đệm',
            'admin_logo' => 'Logo trang quản trị',
            'admin_title' => 'Tiêu đề trang quản trị',
            'admin_title_placeholder' => 'Tiêu đề hiển thị trên thẻ trình duyệt',
            'rich_editor' => 'Bộ soạn thảo văn bản',
            'cache_block' => 'Bộ nhớ đệm',
            'yes' => 'Bật',
            'no' => 'Tắt',
        ],
    'email' => [
        'subject' => 'Tiêu đề',
        'content' => 'Nội dung',
        'title' => 'Cấu hình email template',
        'description' => 'Cấu trúc file template sử dụng HTML và các biến trong hệ thống.',
        'reset_to_default' => 'Khôi phục về mặc định',
        'back' => 'Trở lại trang cài đặt',
        'reset_success' => 'Khôi phục mặc định thành công',
        'confirm_reset' => 'Xác nhận khôi phục mặc định?',
        'confirm_message' => 'Bạn có chắc chắn muốn khôi phục về bản mặc định?',
        'continue' => 'Tiếp tục',
        'sender_name' => 'Tên người gửi',
        'sender_name_placeholder' => 'Tên',
        'sender_email' => 'Email của người gửi',
        'driver' => 'Loại',
        'port' => 'Cổng',
        'port_placeholder' => 'Ví dụ: 587',
        'host' => 'Máy chủ',
        'host_placeholder' => 'Ví dụ: smtp.gmail.com',
        'username' => 'Tên đăng nhập',
        'username_placeholder' => 'Tên đăng nhập vào máy chủ mail',
        'password' => 'Mật khẩu',
        'password_placeholder' => 'Mật khẩu đăng nhập vào máy chủ mail',
        'encryption' => 'Mã hoá',
        'mail_gun_domain' => 'Tên miền',
        'mail_gun_domain_placeholder' => 'Tên miền',
        'mail_gun_secret' => 'Chuỗi bí mật',
        'mail_gun_secret_placeholder' => 'Chuỗi bí mật',
        'template_title' => 'Mẫu giao diện email',
        'template_description' => 'Giao diện mặc định cho tất cả email',
        'template_header' => 'Mẫu cho phần trên của email',
        'template_header_description' => 'Phần trên của tất cả email',
        'template_footer' => 'Mẫu cho phần dưới của email',
        'template_footer_description' => 'Phần dưới của tất cả email',
    ],
    'save_settings' => 'Lưu cài đặt',
    'template' => 'Mẫu',
    'description' => 'Mô tả',
    'enable' => 'Bật',
    'test_send_mail' => 'Gửi thử nghiệm',
];