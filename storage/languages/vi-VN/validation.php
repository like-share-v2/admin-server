<?php
/**
 * @copyright zunea/hyperf-admin
 * @version 1.0.0
 * @link https://github.com/Zunea/hyperf-admin
 */
$max             = '不能超过:max个字符';
$required_input  = '此处不能为空';
$required_select = '请选择';

return [
    'Auth'           => [
        'LoginRequest' => [
            'username' => ['required' => 'Nhập tài khoản đăng nhập', 'alpha_dash' => 'Lỗi định dạng tài khoản (chỉ có các chữ cái hỗ trợ, các con số, đường gạch gạch và ô gạch được)', 'between' => 'Lỗi định dạng tài khoản'],
            'password' => ['required' => 'Nhập mật khẩu đăng nhập', 'alpha_dash' => 'Định dạng mật khẩu sai (chỉ có chữ cái và số, cũng như đường gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng mật khẩu ']
        ]
    ],
    'User'           => [
        'AddRequest'    => [
            'username' => ['required' => 'Hãy nhập tài khoản người dùng', 'alpha_dash' => 'Lỗi định dạng tài khoản (chỉ có các chữ cái hỗ trợ, các con số, đường gạch gạch và ô gạch được)', 'between' => 'Lỗi định dạng tài khoản'],
            'password' => ['required' => 'Nhập mật khẩu đăng nhập', 'alpha_dash' => 'Định dạng mật khẩu sai (chỉ có chữ cái và số, cũng như đường gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng mật khẩu '],
            'nickname' => ['max' => 'Độ dài của nickname của người dùng không thể vượt qua các ký hiệu :max'],
            'phone'    => ['required' => 'Hãy nhập số điện thoại di động', 'max' => 'Số điện thoại di động không thể vượt qua khoảng cách :max'],
            'email'    => ['required' => 'Nhập tài khoản email', 'email' => 'Lỗi định dạng tài khoản thư', 'max' => 'Độ dài của tài khoản email không thể vượt qua các ký tự :max.'],
            'country_code' => ['required' => 'Hãy chọn quốc gia'],
        ],
        'UpdateRequest' => [
            'username' => ['required' => 'Hãy nhập tài khoản người dùng', 'alpha_dash' => 'Lỗi định dạng tài khoản (chỉ có các chữ cái hỗ trợ, các con số, đường gạch gạch và ô gạch được)', 'between' => 'Lỗi định dạng tài khoản'],
            'password' => ['alpha_dash' => 'Định dạng mật khẩu sai (chỉ có chữ cái và số, cũng như đường gạch và ô gạch được hỗ trợ)', 'between' => 'Lỗi định dạng mật khẩu '],
            'nickname' => ['max' => 'Biệt danh người dùng không thể vượt qua :max'],
            'phone'    => ['required' => 'Hãy nhập số điện thoại di động', 'max' => 'Số điện thoại di động không thể vượt qua các ký tự :max.'],
            'email'    => ['email' => 'Lỗi định dạng tài khoản thư', 'max' => 'Độ dài của tài khoản email không thể vượt qua định nghĩa :max'],
            'status'   => ['required' => 'Hãy chọn vị trí người dùng', 'in' => 'Không có vị trí người dùng đã chọn'],
            'country_code' => ['required' => 'Hãy chọn quốc gia'],
            ]
    ],
    'UserGroup'      => [
        'AddRequest'    => [
            'name'   => ['required' => 'Hãy nhập tên nhóm người dùng', 'max' => 'Tên nhóm người dùng không thể vượt qua các ký tự :max'],
            'remark' => ['max' => 'Ghi chú không thể vượt qua :max']
        ],
        'UpdateRequest' => [
            'name'   => ['required' => 'Hãy nhập tên nhóm người dùng', 'max' => 'Tên nhóm người dùng không thể vượt qua các ký tự :max'],
            'remark' => ['max' => 'Ghi chú không thể vượt qua :max'],
            'status' => ['required' => 'Vui lòng chọn nhóm người dùng', 'in' => 'Không có trạng thái đã chọn']
        ]
    ],
    'Resource'       => [
        'ResourceRequest' => [
            'name'   => ['required' => 'Hãy nhập tên tài nguyên', 'max' => 'Tên tài nguyên không thể vượt qua các ký tự :max'],
            'path'   => ['required' => 'Hãy nhập đường dẫn nguồn', 'max' => 'Đường dẫn không thể vượt qua các ký tự :max'],
            'method' => ['required' => 'Hãy chọn kiểu tài nguyên', 'in' => 'Chỉ chấp nhận kiểu tài nguyên (:in)']
        ]
    ],
    'Menu'           => [
        'AddRequest'    => [
            'name'      => ['required_if' => $required_input, 'alpha_dash' => 'Chỉ có chữ cái và số, cũng như đường gạch và ô gạch được cho phép', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => 'Chọn sai, xin chọn lại lần nữa'],
            'icon'      => ['alpha_dash' => 'Chỉ có chữ cái và số, cũng như đường gạch và ô gạch được cho phép', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => 'Chỉ có chữ cái và số, cũng như đường gạch và ô gạch được cho phép', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => 'Chọn sai, xin chọn lại lần nữa']
        ],
        'UpdateRequest' => [
            'name'      => ['required' => $required_input, 'alpha_dash' => 'Chỉ có chữ cái và số, cũng như đường gạch và ô gạch được cho phép', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => 'Chọn sai, xin chọn lại lần nữa'],
            'icon'      => ['alpha_dash' => 'Chỉ có chữ cái và số, cũng như đường gạch và ô gạch được cho phép', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => 'Chỉ có chữ cái và số, cũng như đường gạch và ô gạch được cho phép', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => 'Chọn sai, xin chọn lại lần nữa'],
            'status'    => ['required' => $required_select, 'in' => 'Chọn sai, xin chọn lại lần nữa']
        ]
    ],
    'Config'         => [
        'ConfigRequest' => [
            'name'   => ['required' => $required_input, 'max' => $max],
            'title'  => ['required' => $required_input, 'max' => $max],
            'group'  => ['required' => $required_select, 'max' => $max],
            'type'   => ['required' => $required_select, 'max' => $max],
            'tips'   => ['max' => $max],
            'format' => ['max' => $max],
        ]
    ],
    'Account'        => [
        'ChangePasswordRequest' => [
            'old_password'              => ['required' => $required_input, 'alpha_dash' => 'Chỉ có chữ cái và số, cũng như đường gạch và ô gạch được cho phép', 'between' => 'Lỗi định dạng mật khẩu'],
            'new_password'              => ['required' => $required_input, 'alpha_dash' => 'Chỉ có chữ cái và số, cũng như đường gạch và ô gạch được cho phép', 'between' => 'Lỗi định dạng mật khẩu', 'confirmed' => 'Mật khẩu mới và mật khẩu xác nhận phải giống nhau.'],
            'new_password_confirmation' => ['required' => $required_input],
        ]
    ],
    'Member'         => [
        'RechargeRequest' => [
            'id'    => ['required' => 'Hãy chọn bộ nạp'],
            'level' => ['required' => 'Hãy chọn cấp thẻ gia nhập', 'gte' => 'Lỗi nạp bộ của tập đoàn, xin nhập tên']
        ],
        'BalanceRequest'  => [
            'type'   => ['required' => 'Hãy chọn kiểu thay đổi'],
            'id'     => ['required' => 'Hãy chọn người dùng'],
            'amount' => ['required' => 'Hãy nhập vào số tài sản có biến', 'numeric' => 'Giá trị tài sản thay đổi phải là một số']
        ]
    ],
    'UserLevel'      => [
        'UserLevelRequest' => [
            'level'                   => ['required' => 'Xin hãy nhập cấp thành viên'],
            'name'                    => ['required' => 'Nhập tên thành viên'],
            'icon'                    => ['required' => 'Gửi biểu tượng thành viên', 'max' => 'Địa chỉ biểu tượng của hội viên là chỉ cỡ 255'],
            'price'                   => ['required' => 'Nhập giá thành viên.', 'gt' => 'Giá của thành viên phải cao hơn 0'],
            'task_num'                => ['required' => 'Nhập số các nhiệm vụ hàng ngày cho thành viên', 'gt' => 'Số nhiệm vụ hàng ngày của thành viên phải lớn hơn 0'],
            'recharge_p_one_rebate'   => ['required' => 'Nhập mức phụ phí bồi thường cho thành viên', 'numeric' => 'Mức bao phí bồi thường viên hàng đầu phải là một số'],
            'recharge_p_two_rebate'   => ['required' => 'Nhập mức phụ phí bồi thường cho thành viên', 'numeric' => 'Lượng phụ phí nạp động viên phải là một số'],
            'recharge_p_three_rebate' => ['required' => 'Nhập mức độ 3 bao nhiêu bồi thường viên', 'numeric' => 'Số giảm giá nạp phí cấp 3 của ủy viên phải là một số'],
            'task_p_one_rebate'       => ['required' => 'Nhập tỷ lệ tiền thưởng cấp đầu cho thành viên để hoàn thành nhiệm vụ', 'numeric' => 'Phần thưởng cấp cao cho thành viên trong việc hoàn thành nhiệm vụ phải là một số', 'between' => 'Tỷ lệ thưởng phải nằm giữa 0 và 100', 'integer' => 'Tỷ lệ thưởng phải là một số nguyên'],
            'task_p_two_rebate'       => ['required' => 'Nhập tỷ lệ tiền thưởng cấp đầu cho thành viên để hoàn thành nhiệm vụ', 'numeric' => 'Phần thưởng phụ cho thành viên hoàn thành nhiệm vụ phải là một số', 'between' => 'Tỷ lệ thưởng phải nằm giữa 0 và 100', 'integer' => 'Tỷ lệ thưởng phải là một số nguyên'],
            'task_p_three_rebate'     => ['required' => 'Nhập tỷ lệ tiền thưởng cấp đầu cho thành viên để hoàn thành nhiệm vụ', 'numeric' => 'Tỷ lệ giải thưởng ba cấp cho thành viên trong việc hoàn thành nhiệm vụ phải là một số', 'between' => 'Tỷ lệ thưởng phải nằm giữa 0 và 100', 'integer' => 'Tỷ lệ thưởng phải là một số nguyên'],
            'day'                     => ['required' => 'Hãy nhập số ngày'],
            'hour'                    => ['required' => 'Hãy vào giờ.'],
            'minute'                  => ['required' => 'Hãy vào phút'],
            'type'                    => ['required' => 'Hãy chọn kiểu thành viên']
        ]
    ],
    'Task'           => [
        'CategoryRequest'        => [
            'name'         => ['required' => 'Hãy nhập tên phân loại'],
            'icon'         => ['required' => 'Nạp biểu tượng phân loại'],
            'banner'       => ['required' => 'Tải bản đồ phân hạng lên'],
            'lowest_price' => ['required' => 'Hãy nhập giá thấp nhất theo hạng.', 'gte' => 'Mức độ phân loại tối thiểu phải lớn hơn hay bằng với 0'],
            'sort'         => ['required' => 'Nhập hạng tự động', 'between' => 'Phải phân giữa 0 và 9999999999'],
            'status'       => ['required' => 'Hãy chọn trạng thái phân hạng', 'in' => 'Chọn sai trạng thái phân hạng'],
            'job_step'     => ['required' => 'Hãy thêm bước công việc'],
            'audit_sample' => ['required' => 'Thêm vụ kiểm tra']
        ],
        'CategoryContentRequest' => [
            'id'   => ['required' => 'Hãy chọn loại cần gửi'],
            'type' => ['required' => 'Lỗi trong kiểu đệ trình', 'in' => 'Lỗi trong kiểu đệ trình']
        ],
        'TaskRequest'            => [
            'category_id' => ['required' => 'Hãy chọn phân loại tác vụ'],
            'level'       => ['required' => 'Hãy chọn cấp thành viên nhiệm vụ'],
            'title'       => ['required' => 'Hãy nhập tên tác vụ', 'max' => 'Tiêu đề chỉ còn có 100 sợi'],
            'description' => ['required' => 'Hãy nhập yêu cầu nhiệm vụ'],
            'url'         => ['required' => 'Hãy nhập địa chỉ nhiệm vụ', 'max' => 'Độ dài của địa chỉ nhiệm vụ chỉ với sợi 255'],
            'amount'      => ['required' => 'Hãy nhập số lượng nhiệm vụ', 'numeric' => 'Hợp đồng nhiệm vụ phải là giá trị số', 'gt' => 'Hợp đồng phải lớn hơn 0'],
            'num'         => ['required' => 'Nhập số lượng phân phối nhiệm vụ', 'between' => 'Số nhiệm vụ được thực hiện chỉ ra 0~999999999999'],
            'sort'        => ['required' => 'Sắp xếp không thể rỗng', 'numeric' => 'Sắp xếp phải có số', 'between' => 'Số phân loại chỉ có 0~9999999999'],
            'status'      => ['required' => 'Hãy chọn trạng thái tác vụ', 'in' => 'Lỗi khi chọn trạng thái nhiệm vụ, hãy chọn lại']
        ]
    ],
    'UserTask'       => [
        'AuditRequest' => [
            'id'     => ['required' => 'Hãy chọn ghi chú nhiệm vụ cần kiểm to án'],
            'status' => ['required' => 'Hãy chọn kết quả kiểm toán', 'in' => 'Kết quả kiểm toán sai, xin chọn lại lần nữa']
        ]
    ],
    'Help'           => [
        'HelpRequest' => [
            'title'   => ['required' => 'Hãy nhập một tựa đề'],
            'content' => ['required' => 'Hãy nhập nội dung'],
            'status'  => ['required' => 'Hãy chọn trạng thái', 'in' => 'Lỗi trong vùng chọn trạng thái, hãy chọn lại'],
            'sort'    => ['required' => 'Nhập vào chuỗi', 'integer' => 'Sắp xếp phải là một số nguyên hơn 0', 'gte' => 'Sắp xếp phải là một số nguyên hơn 0']
        ],
        'HelpContentRequest' => [
            'help_id' => ['required' => 'Hãy chọn hướng dẫn trợ giúp'],
            'locale'  => ['required' => 'Hãy chọn ngôn ngữ'],
            'content' => ['required' => 'Hãy nhập nội dung']
        ]
    ],
    'UserNotify'     => [
        'UserNotifyRequest' => [
            'title'   => ['required' => 'Tiêu đề không thể rỗng'],
            'content' => ['required' => 'Chất lượng không thể rỗng'],
            'sort'    => ['required' => 'Nhập vào chuỗi', 'integer' => 'Sắp xếp phải là một số nguyên hơn 0', 'gte' => 'Sắp xếp phải là một số nguyên hơn 0']
        ]
    ],
    'UserRecharge'   => [
        'AuditManualRequest' => [
            'id'     => ['required' => 'Hãy chọn kỷ lục hồi phục đã duyệt.'],
            'status' => ['required' => 'Hãy chọn kết quả kiểm toán', 'in' => 'Kết quả kiểm toán sai, xin chọn lại lần nữa'],
            'remark' => ['max' => 'Ghi chú tới độ dài 255']
        ]
    ],
    'UserWithdrawal' => [
        'AuditRequest' => [
            'id'     => ['required' => 'Hãy chọn dữ liệu rút lui cần phê chuẩn'],
            'status' => ['required' => 'Hãy chọn kết quả kiểm toán', 'in' => 'Kết quả kiểm toán sai, xin chọn lại lần nữa']
        ]
    ],
    'RechargeQrCode' => [
        'QrCodeRequest' => [
            'image'  => ['required' => 'Vui lòng tải đoạn mã QR cho bộ sưu tập'],
            'status' => ['required' => 'Hãy chọn trạng thái của bộ sưu tập mã QR', 'in' => 'Bộ sưu tập lỗi chọn mật mã QR, xin chọn lại']
        ]
    ],
    'Country' => [
        'CountryRequest' => [
            'code' => ['required' => 'Hãy nhập mật mã ngôn ngữ (Văn hóa)'],
            'name' => ['required' => 'Hãy nhập tên quốc gia.'],
            'lang' => ['required' => 'Hãy nhập tên ngôn ngữ'],
            'image' => ['required' => 'Vui lòng tải lên hình quốc gia'],
            'exchange_rate' => ['required' => 'Xin hãy nhập vào tiền tệ.', 'gt' => 'Tỷ lệ trao đổi phải lớn hơn 0']
        ]
    ],
    'Language' => [
        'LanguageRequest' => [
            'key' => ['required' => 'Hãy nhập vào phím'],
            'local' => ['required' => 'Hãy chọn ngôn ngữ'],
            'value' => ['required' => 'Hãy nhập đoạn tương ứng.']
        ]
    ],
    'CountryCode' => [
        'CountryCodeRequest' => [
            'name' => ['required' => 'Hãy nhập tên quốc gia.'],
            'code' => ['required' => 'Hãy nhập mã quốc gia.']
        ]
    ],
    'UserNotifyContent' => [
        'UserNotifyContentRequest' => [
            'notify_id' => ['required' => 'Hãy chọn tin'],
            'locale' => ['required' => 'Hãy chọn ngôn ngữ'],
            'content' => ['required' => 'Hãy nhập nội dung']
        ]
    ],
    'Banner' => [
        'BannerRequest' => [
            'image' => ['required' => 'Vui lòng tải bản đồ'],
            'sort' => ['required' => 'Nhập vào chuỗi'],
            'url' => ['required' => 'Hãy nhập địa chỉ liên kết']
        ]
    ],
    'TaskAudit' => [
        'TaskAuditRequest' => [
            'id' => ['required' => 'Hãy chọn việc cần kiểm tra'],
            'status' => ['required' => 'Hãy chọn kết quả kiểm toán', 'in' => 'Kết quả kiểm toán sai, xin chọn lại lần nữa']
        ]
    ],
    'CountryBank' => [
        'CountryBankRequest' => [
            'country_id' => ['required' => 'Hãy chọn một quốc gia'],
            'name' => ['required' => 'Hãy nhập tên của chủ thẻ.'],
            'bank_name' => ['required' => 'Hãy nhập tên ngân hàng'],
            'bank_address' => ['required' => 'Hãy nhập địa chỉ ngân hàng.'],
            'bank_account' => ['required' => 'Nhập số tài khoản ngân hàng'],
            'address' => ['required' => 'Hãy nhập địa chỉ tập hợp']
        ]
    ],
    'Video' => [
        'VideoRequest' => [
            'video' => ['required' => 'Xin hãy tải đoạn video lên.'],
            'sort' => ['required' => 'Nhập vào chuỗi']
        ]
    ],
    'Invitation' => [
        'InvitationRequest' => [
            'image' => ['required' => 'Vui lòng tải ảnh lên'],
            'locale' => ['required' => 'Hãy chọn ngôn ngữ']
        ]
    ]
];