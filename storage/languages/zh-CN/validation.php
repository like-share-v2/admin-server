<?php
/**
 * @copyright zunea/hyperf-admin
 * @version   1.0.0
 * @link      https://github.com/Zunea/hyperf-admin
 */
$max             = '不能超过:max个字符';
$required_input  = '此处不能为空';
$required_select = '请选择';

return [
    'Auth'              => [
        'LoginRequest' => [
            'username' => ['required' => '请输入登陆账号', 'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '账号格式错误[:between长度]'],
            'password' => ['required' => '请输入登陆密码', 'alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '密码格式错误[:between长度]']
        ]
    ],
    'User'              => [
        'AddRequest'    => [
            'username'     => ['required' => '请输入用户账号', 'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '账号格式错误[:between长度]'],
            'password'     => ['required' => '请输入登陆密码', 'alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '密码格式错误[:between长度]'],
            'nickname'     => ['max' => '用户昵称长度不能超过:max个字符'],
            'phone'        => ['required' => '请输入手机号码', 'max' => '手机号码长度不能超过:max个字符'],
            'email'        => ['required' => '请输入邮箱账号', 'email' => '邮箱账号格式错误', 'max' => '邮箱账号长度不能超过:max个字符'],
            'country_code' => ['required' => '请选择国家区号'],
        ],
        'UpdateRequest' => [
            'username'     => ['required' => '请输入用户账号', 'alpha_dash' => '账号格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '账号格式错误[:between长度]'],
            'password'     => ['alpha_dash' => '密码格式错误[仅支持字母和数字，以及破折号和下划线]', 'between' => '密码格式错误[:between长度]'],
            'nickname'     => ['max' => '用户昵称不能超过:max字符'],
            'phone'        => ['required' => '请输入手机号码', 'max' => '手机号码长度不能超过:max个字符'],
            'email'        => ['email' => '邮箱账号格式错误', 'max' => '邮箱账号长度不能超过:max个字符'],
            'status'       => ['required' => '请选择用户状态', 'in' => '所选用户状态不可用'],
            'country_code' => ['required' => '请选择国家区号'],
        ]
    ],
    'UserGroup'         => [
        'AddRequest'    => [
            'name'   => ['required' => '请输入用户组名称', 'max' => '用户组名称不能超过:max个字符'],
            'remark' => ['max' => '备注不能超过:max个字符']
        ],
        'UpdateRequest' => [
            'name'   => ['required' => '请输入用户组名称', 'max' => '用户组名称不能超过:max个字符'],
            'remark' => ['max' => '备注不能超过:max个字符'],
            'status' => ['required' => '请选择用户组状态', 'in' => '所选用状态不可用']
        ]
    ],
    'Resource'          => [
        'ResourceRequest' => [
            'name'   => ['required' => '请输入资源名称', 'max' => '资源名称不能超过:max个字符'],
            'path'   => ['required' => '请输入资源路径', 'max' => '资源路径不能超过:max个字符'],
            'method' => ['required' => '请选择资源类型', 'in' => '资源类型只接受[:in]']
        ]
    ],
    'Menu'              => [
        'AddRequest'    => [
            'name'      => ['required_if' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => '选择错误，请重新选择'],
            'icon'      => ['alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => '选择错误，请重新选择']
        ],
        'UpdateRequest' => [
            'name'      => ['required' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => '选择错误，请重新选择'],
            'icon'      => ['alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => '选择错误，请重新选择'],
            'status'    => ['required' => $required_select, 'in' => '选择错误，请重新选择']
        ]
    ],
    'Config'            => [
        'ConfigRequest' => [
            'name'   => ['required' => $required_input, 'max' => $max],
            'title'  => ['required' => $required_input, 'max' => $max],
            'group'  => ['required' => $required_select, 'max' => $max],
            'type'   => ['required' => $required_select, 'max' => $max],
            'tips'   => ['max' => $max],
            'format' => ['max' => $max],
        ]
    ],
    'Account'           => [
        'ChangePasswordRequest' => [
            'old_password'              => ['required' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'between' => '密码格式错误[:between长度]'],
            'new_password'              => ['required' => $required_input, 'alpha_dash' => '只允许字母和数字，以及破折号和下划线', 'between' => '密码格式错误[:between长度]', 'confirmed' => '新密码和确认密码必须一致'],
            'new_password_confirmation' => ['required' => $required_input],
        ]
    ],
    'Member'            => [
        'RechargeRequest' => [
            'id'    => ['required' => '请选择充值用户'],
            'level' => ['required' => '请选择充值会员等级', 'gte' => '会员等级充值有误，请重新选择']
        ],
        'BalanceRequest'  => [
            'type'   => ['required' => '请选择变动类型'],
            'id'     => ['required' => '请选择用户'],
            'amount' => ['required' => '请输入变动资产金额', 'numeric' => '变动资产金额必须是数字']
        ]
    ],
    'UserLevel'         => [
        'UserLevelRequest' => [
            'level'                   => ['required' => '请输入会员等级'],
            'name'                    => ['required' => '请输入会员名称'],
            'icon'                    => ['required' => '请上传会员图标', 'max' => '会员图标地址限制为255个字符串长度'],
            'price'                   => ['required' => '请输入会员价格', 'gt' => '会员价格必须大于0'],
            'task_num'                => ['required' => '请输入会员每日任务数量', 'gt' => '会员每日任务数量必须大于0'],
            'recharge_p_one_rebate'   => ['required' => '请输入会员充值一级返利金额', 'numeric' => '会员充值一级返利金额必须是数字'],
            'recharge_p_two_rebate'   => ['required' => '请输入会员充值二级返利金额', 'numeric' => '会员充值二级返利金额必须是数字'],
            'recharge_p_three_rebate' => ['required' => '请输入会员充值三级返利金额', 'numeric' => '会员充值三级返利金额必须是数字'],
            'task_p_one_rebate'       => ['required' => '请输入会员完成任务一级奖励比例', 'numeric' => '会员完成任务一级奖励比例必须是数字', 'between' => '奖励比例必须在0~100', 'integer' => '奖励比例必须是整数'],
            'task_p_two_rebate'       => ['required' => '请输入会员完成任务一级奖励比例', 'numeric' => '会员完成任务二级奖励比例必须是数字', 'between' => '奖励比例必须在0~100', 'integer' => '奖励比例必须是整数'],
            'task_p_three_rebate'     => ['required' => '请输入会员完成任务一级奖励比例', 'numeric' => '会员完成任务三级奖励比例必须是数字', 'between' => '奖励比例必须在0~100', 'integer' => '奖励比例必须是整数'],
            'day'                     => ['required' => '请输入天数'],
            'hour'                    => ['required' => '请输入小时'],
            'minute'                  => ['required' => '请输入分钟'],
            'type'                    => ['required' => '请选择会员类型']
        ]
    ],
    'Task'              => [
        'CategoryRequest'        => [
            'name'         => ['required' => '请输入分类名'],
            'icon'         => ['required' => '请上传分类图标'],
            'banner'       => ['required' => '请上传分类大图'],
            'lowest_price' => ['required' => '请输入分类最低价格', 'gte' => '分类最低必须大于等于0'],
            'sort'         => ['required' => '请输入分类排序', 'between' => '分类排序必须在0~999999之间'],
            'status'       => ['required' => '请选择分类状态', 'in' => '分类状态选择有误'],
            'job_step'     => ['required' => '请添加任务步骤'],
            'audit_sample' => ['required' => '请添加审核案例']
        ],
        'CategoryContentRequest' => [
            'id'   => ['required' => '请选择提交的分类'],
            'type' => ['required' => '提交类型出错', 'in' => '提交类型出错']
        ],
        'TaskRequest'            => [
            'category_id' => ['required' => '请选择任务分类'],
            'level'       => ['required' => '请选择任务会员等级'],
            'title'       => ['required' => '请输入任务标题', 'max' => '标题长度限制为100个字符串长度'],
            'description' => ['required' => '请输入任务需求'],
            'url'         => ['required' => '请输入任务地址', 'max' => '任务地址长度限制为255个字符串长度'],
            'amount'      => ['required' => '请输入任务金额', 'numeric' => '任务金额必须是数值', 'gt' => '任务金额必须大于0'],
            'num'         => ['required' => '请输入任务发放数量', 'between' => '任务发放数量限制为0~99999999'],
            'sort'        => ['required' => '排序不能为空', 'numeric' => '排序必须是数字', 'between' => '排序数字限制为0~9999999'],
            'status'      => ['required' => '请选择任务状态', 'in' => '任务状态选择有误，请重新选择']
        ]
    ],
    'UserTask'          => [
        'AuditRequest' => [
            'id'     => ['required' => '请选择要审核的任务记录'],
            'status' => ['required' => '请选择审核结果', 'in' => '审核结果选择有误，请重新选择']
        ]
    ],
    'Help'              => [
        'HelpRequest'        => [
            'title'   => ['required' => '请输入标题'],
            'content' => ['required' => '请输入内容'],
            'status'  => ['required' => '请选择状态', 'in' => '状态选择有误，请重新选择'],
            'sort'    => ['required' => '请输入排序', 'integer' => '排序必须是大于0的整数', 'gte' => '排序必须是大于0的整数']
        ],
        'HelpContentRequest' => [
            'help_id' => ['required' => '请选择帮助手册'],
            'locale'  => ['required' => '请选择语言'],
            'content' => ['required' => '请输入内容']
        ]
    ],
    'UserNotify'        => [
        'UserNotifyRequest' => [
            'title'   => ['required' => '标题不能为空'],
            'content' => ['required' => '内容不能为空'],
            'sort'    => ['required' => '请输入排序', 'integer' => '排序必须是大于0的整数', 'gte' => '排序必须是大于0的整数']
        ]
    ],
    'UserRecharge'      => [
        'AuditManualRequest' => [
            'id'     => ['required' => '请选择审核的充值记录'],
            'status' => ['required' => '请选择审核结果', 'in' => '审核结果有误，请重新选择'],
            'remark' => ['max' => '备注最长255个字符串长度']
        ]
    ],
    'UserWithdrawal'    => [
        'AuditRequest' => [
            'id'     => ['required' => '请选择要审核的提现记录'],
            'status' => ['required' => '请选择审核结果', 'in' => '审核结果选择有误，请重新选择']
        ]
    ],
    'RechargeQrCode'    => [
        'QrCodeRequest' => [
            'image'  => ['required' => '请上传收款二维码'],
            'status' => ['required' => '请选择收款二维码状态', 'in' => '收款二维码状态选择有误，请重新选择']
        ]
    ],
    'Country'           => [
        'CountryRequest' => [
            'code'          => ['required' => '请输入语言(文化)代码'],
            'name'          => ['required' => '请输入国家名称'],
            'lang'          => ['required' => '请输入语言名称'],
            'image'         => ['required' => '请上传国家国旗图片'],
            'exchange_rate' => ['required' => '请输入货币汇率', 'gt' => '汇率必须大于0']
        ]
    ],
    'Language'          => [
        'LanguageRequest' => [
            'key'   => ['required' => '请输入KEY'],
            'local' => ['required' => '请选择语言'],
            'value' => ['required' => '请输入对应文本']
        ]
    ],
    'CountryCode'       => [
        'CountryCodeRequest' => [
            'name' => ['required' => '请输入国家名称'],
            'code' => ['required' => '请输入国家区号']
        ]
    ],
    'UserNotifyContent' => [
        'UserNotifyContentRequest' => [
            'notify_id' => ['required' => '请选择新闻'],
            'locale'    => ['required' => '请选择语言'],
            'content'   => ['required' => '请输入内容']
        ]
    ],
    'Banner'            => [
        'BannerRequest' => [
            'image' => ['required' => '请上传轮播图'],
            'sort'  => ['required' => '请输入排序'],
            'url'   => ['required' => '请输入链接地址']
        ]
    ],
    'TaskAudit'         => [
        'TaskAuditRequest' => [
            'id'     => ['required' => '请选择要审核任务'],
            'status' => ['required' => '请选择审核结果', 'in' => '审核结果选择有误，请重新选择']
        ]
    ],
    'CountryBank'       => [
        'CountryBankRequest' => [
            'country_id'   => ['required' => '请选择国家'],
            'name'         => ['required' => '请输入持卡人名'],
            'bank_name'    => ['required' => '请输入银行名'],
            'bank_address' => ['required' => '请输入开户行地址'],
            'bank_account' => ['required' => '请输入银行账号'],
            'address'      => ['required' => '请输入收款地址']
        ]
    ],
    'Video'             => [
        'VideoRequest' => [
            'video' => ['required' => '请上传视频'],
            'sort'  => ['required' => '请输入排序']
        ]
    ],
    'Invitation'        => [
        'InvitationRequest' => [
            'image'  => ['required' => '请上传图片'],
            'locale' => ['required' => '请选择语言']
        ]
    ],
    'Defray'            => [
        'DefrayRequest' => [
            'country_id'    => ['required' => '请选择国家'],
            'amount'        => ['required' => '请输入提现金额', 'gt' => '提现金额必须大于0'],
            'name'          => ['required' => '请输入收款人姓名'],
            'bank_code'     => ['required' => '请输入银行编码'],
            'bank_name'     => ['required' => '请输入银行名称'],
            'bank_account'  => ['required' => '请输入银行卡号'],
            'open_province' => ['required' => '请输入开户省'],
            'open_city'     => ['required' => '请输入与开户市'],
            'user_mobile'   => ['required' => '请输入收款人手机'],
            'user_email'    => ['required' => '请输入收款人邮箱'],
            'address'       => ['required' => '请输入地址'],
        ]
    ]
];