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
            'username' => ['required' => 'Please input login account', 'alpha_dash' => 'Wrong account format [only letters and numbers, dashes and underscores are supported]', 'between' => 'Account format error [: between length]'],
            'password' => ['required' => 'Please input the login password', 'alpha_dash' => 'Wrong password format [only letters and numbers, as well as dashes and underscores are supported]', 'between' => 'Password format error [: between length]']
        ]
    ],
    'User'           => [
        'AddRequest'    => [
            'username' => ['required' => 'Please enter user account', 'alpha_dash' => 'Wrong account format [only letters and numbers, dashes and underscores are supported]', 'between' => 'Account format error [: between length]'],
            'password' => ['required' => 'Please input the login password', 'alpha_dash' => 'Wrong password format [only letters and numbers, as well as dashes and underscores are supported]', 'between' => 'Password format error [: between length]'],
            'nickname' => ['max' => 'The length of user nickname cannot exceed: max characters'],
            'phone'    => ['required' => 'Please enter your mobile phone number', 'max' => 'The length of mobile phone number cannot exceed: max characters'],
            'email'    => ['required' => 'Please input email account', 'email' => 'Email account format error', 'max' => 'Email account length cannot exceed: max characters'],
            'country_code' => ['required' => 'Please select the country code'],
        ],
        'UpdateRequest' => [
            'username' => ['required' => 'Please enter user account', 'alpha_dash' => 'Wrong account format [only letters and numbers, dashes and underscores are supported]', 'between' => 'Account format error [: between length]'],
            'password' => ['alpha_dash' => 'Wrong password format [only letters and numbers, as well as dashes and underscores are supported]', 'between' => 'Password format error [: between length]'],
            'nickname' => ['max' => 'User nickname cannot exceed: max characters'],
            'phone'    => ['required' => 'Please enter your mobile phone number', 'max' => 'The length of mobile phone number cannot exceed: max characters'],
            'email'    => ['email' => 'Email account format error', 'max' => 'Email account length cannot exceed: max characters'],
            'status'   => ['required' => 'Please select user status', 'in' => 'The selected user status is not available'],
            'country_code' => ['required' => 'Please select the country code'],
            ]
    ],
    'UserGroup'      => [
        'AddRequest'    => [
            'name'   => ['required' => 'Please enter user group name', 'max' => 'The user group name cannot exceed: max characters'],
            'remark' => ['max' => 'Remarks cannot exceed: max characters']
        ],
        'UpdateRequest' => [
            'name'   => ['required' => 'Please enter user group name', 'max' => 'The user group name cannot exceed: max characters'],
            'remark' => ['max' => 'Remarks cannot exceed: max characters'],
            'status' => ['required' => 'Please select user group status', 'in' => 'The selected state is not available']
        ]
    ],
    'Resource'       => [
        'ResourceRequest' => [
            'name'   => ['required' => 'Please enter resource name', 'max' => 'Resource name cannot exceed: max characters'],
            'path'   => ['required' => 'Please enter resource path', 'max' => 'Resource path cannot exceed: max characters'],
            'method' => ['required' => 'Please select resource type', 'in' => 'Resource type only accepts [: in]']
        ]
    ],
    'Menu'           => [
        'AddRequest'    => [
            'name'      => ['required_if' => $required_input, 'alpha_dash' => 'Only letters and numbers, as well as dashes and underscores, are allowed', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => 'Wrong selection, please choose again'],
            'icon'      => ['alpha_dash' => 'Only letters and numbers, as well as dashes and underscores, are allowed', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => 'Only letters and numbers, as well as dashes and underscores, are allowed', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => 'Wrong selection, please choose again']
        ],
        'UpdateRequest' => [
            'name'      => ['required' => $required_input, 'alpha_dash' => 'Only letters and numbers, as well as dashes and underscores, are allowed', 'max' => $max],
            'type'      => ['required' => $required_select, 'in' => 'Wrong selection, please choose again'],
            'icon'      => ['alpha_dash' => 'Only letters and numbers, as well as dashes and underscores, are allowed', 'max' => $max],
            'path'      => ['required' => $required_input, 'alpha_dash' => 'Only letters and numbers, as well as dashes and underscores, are allowed', 'max' => $max],
            'component' => ['max' => $max],
            'target'    => ['required' => $required_select, 'in' => 'Wrong selection, please choose again'],
            'status'    => ['required' => $required_select, 'in' => 'Wrong selection, please choose again']
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
            'old_password'              => ['required' => $required_input, 'alpha_dash' => 'Only letters and numbers, as well as dashes and underscores, are allowed', 'between' => 'Password format error [: between length]'],
            'new_password'              => ['required' => $required_input, 'alpha_dash' => 'Only letters and numbers, as well as dashes and underscores, are allowed', 'between' => 'Password format error [: between length]', 'confirmed' => 'The new password and the confirmation password must be the same'],
            'new_password_confirmation' => ['required' => $required_input],
        ]
    ],
    'Member'         => [
        'RechargeRequest' => [
            'id'    => ['required' => 'Please select recharge user'],
            'level' => ['required' => 'Please select recharge member level', 'gte' => 'Member level recharge error, please re select']
        ],
        'BalanceRequest'  => [
            'type'   => ['required' => 'Please select change type'],
            'id'     => ['required' => 'Please select user'],
            'amount' => ['required' => 'Please enter the amount of variable assets', 'numeric' => 'Variable asset amount must be a number']
        ]
    ],
    'UserLevel'      => [
        'UserLevelRequest' => [
            'level'                   => ['required' => 'Please enter the membership level'],
            'name'                    => ['required' => 'Please enter member name'],
            'icon'                    => ['required' => 'Please upload member Icon', 'max' => 'Membership icon address is limited to 255 string length'],
            'price'                   => ['required' => 'Please enter member price', 'gt' => 'Member price must be greater than 0'],
            'task_num'                => ['required' => 'Please input the number of daily tasks for members', 'gt' => "Member's daily task number must be greater than 0"],
            'recharge_p_one_rebate'   => ['required' => 'Please input the first level rebate amount of member recharge', 'numeric' => 'The first level rebate amount of member recharge must be a number'],
            'recharge_p_two_rebate'   => ['required' => 'Please input the secondary rebate amount of member recharge', 'numeric' => 'The amount of secondary rebate for member recharge must be a number'],
            'recharge_p_three_rebate' => ['required' => 'Please input the level 3 rebate amount of member recharge', 'numeric' => 'Member recharge Level 3 rebate amount must be a number'],
            'task_p_one_rebate'       => ['required' => 'Please input the first level reward proportion for members to complete the task', 'numeric' => 'The proportion of first level reward for members to complete tasks must be a number', 'between' => 'The reward ratio must be between 0 and 100', 'integer' => 'The reward ratio must be an integer'],
            'task_p_two_rebate'       => ['required' => 'Please input the first level reward proportion for members to complete the task', 'numeric' => 'The proportion of secondary reward for members to complete tasks must be a number', 'between' => 'The reward ratio must be between 0 and 100', 'integer' => 'The reward ratio must be an integer'],
            'task_p_three_rebate'     => ['required' => 'Please input the first level reward proportion for members to complete the task', 'numeric' => 'The proportion of three-level reward for members to complete tasks must be a number', 'between' => 'The reward ratio must be between 0 and 100', 'integer' => 'The reward ratio must be an integer'],
            'day'                     => ['required' => 'Please enter the number of days'],
            'hour'                    => ['required' => 'Please enter hour'],
            'minute'                  => ['required' => 'Please enter minutes'],
            'type'                    => ['required' => 'Please select member type']
        ]
    ],
    'Task'           => [
        'CategoryRequest'        => [
            'name'         => ['required' => 'Please enter the category name'],
            'icon'         => ['required' => 'Please upload category Icon'],
            'banner'       => ['required' => 'Please upload the classification map'],
            'lowest_price' => ['required' => 'Please enter the lowest price by category', 'gte' => 'The minimum classification must be greater than or equal to 0'],
            'sort'         => ['required' => 'Please enter sort by category', 'between' => 'Sorting must be between 0 and 999999'],
            'status'       => ['required' => 'Please select classification status', 'in' => 'Wrong selection of classification status'],
            'job_step'     => ['required' => 'Please add task steps'],
            'audit_sample' => ['required' => 'Please add audit case']
        ],
        'CategoryContentRequest' => [
            'id'   => ['required' => 'Please select the category to submit'],
            'type' => ['required' => 'Error in submission type', 'in' => 'Error in submission type']
        ],
        'TaskRequest'            => [
            'category_id' => ['required' => 'Please select task category'],
            'level'       => ['required' => 'Please select task member level'],
            'title'       => ['required' => 'Please enter task title', 'max' => 'The title length is limited to 100 strings'],
            'description' => ['required' => 'Please enter task requirement'],
            'url'         => ['required' => 'Please enter the task address', 'max' => 'The task address length is limited to 255 strings'],
            'amount'      => ['required' => 'Please enter task amount', 'numeric' => 'Task amount must be a numeric value', 'gt' => 'Task amount must be greater than 0'],
            'num'         => ['required' => 'Please input the task distribution quantity', 'between' => 'The number of tasks issued is limited to 0 ~ 9999999'],
            'sort'        => ['required' => 'Sort cannot be empty', 'numeric' => 'Sort must be numeric', 'between' => 'The sorting number is limited to 0 ~ 999999'],
            'status'      => ['required' => 'Please select task status', 'in' => 'Error in task status selection, please select again']
        ]
    ],
    'UserTask'       => [
        'AuditRequest' => [
            'id'     => ['required' => 'Please select the task record to audit'],
            'status' => ['required' => 'Please select audit result', 'in' => 'The audit result is wrong, please select again']
        ]
    ],
    'Help'           => [
        'HelpRequest' => [
            'title'   => ['required' => 'Please enter a title'],
            'content' => ['required' => 'Please enter the content'],
            'status'  => ['required' => 'Please select status', 'in' => 'Status selection is wrong, please select again'],
            'sort'    => ['required' => 'Please enter sort', 'integer' => 'Sort must be an integer greater than 0', 'gte' => 'Sort must be an integer greater than 0']
        ],
        'HelpContentRequest' => [
        'help_id' => ['required' => 'Please select help manual'],
        'locale'  => ['required' => 'Please select language'],
        'content' => ['required' => 'Please enter the content']
        ]
    ],    
    'UserNotify'     => [
        'UserNotifyRequest' => [
            'title'   => ['required' => 'Title cannot be empty'],
            'content' => ['required' => 'Content cannot be empty'],
            'sort'    => ['required' => 'Please enter sort', 'integer' => 'Sort must be an integer greater than 0', 'gte' => 'Sort must be an integer greater than 0']
        ]
    ],
    'UserRecharge'   => [
        'AuditManualRequest' => [
            'id'     => ['required' => 'Please select the approved recharge record'],
            'status' => ['required' => 'Please select audit result', 'in' => 'The audit result is wrong, please select again'],
            'remark' => ['max' => 'Remarks up to 255 string length']
        ]
    ],
    'UserWithdrawal' => [
        'AuditRequest' => [
            'id'     => ['required' => 'Please select the withdrawal record to be approved'],
            'status' => ['required' => 'Please select audit result', 'in' => 'The audit result is wrong, please select again']
        ]
    ],
    'RechargeQrCode' => [
        'QrCodeRequest' => [
            'image'  => ['required' => 'Please upload the QR code for collection'],
            'status' => ['required' => 'Please select the status of collection QR code', 'in' => 'Collection QR code status selection error, please re select']
        ]
    ],
    'Country' => [
        'CountryRequest' => [
            'code' => ['required' => 'Please enter the language (Culture) code'],
            'name' => ['required' => 'Please enter the country name'],
            'lang' => ['required' => 'Please enter language name'],
            'image' => ['required' => 'Please upload the national flag picture'],
            'exchange_rate' => ['required' => 'Please enter the currency exchange rate', 'gt' => 'Exchange rate must be greater than 0']
        ]
    ],
    'Language' => [
        'LanguageRequest' => [
            'key' => ['required' => 'Please enter key'],
            'local' => ['required' => 'Please select language'],
            'value' => ['required' => 'Please enter the corresponding text']
        ]
    ],
    'CountryCode' => [
        'CountryCodeRequest' => [
            'name' => ['required' => 'Please enter the country name'],
            'code' => ['required' => 'Please enter the country code']
        ]
    ],
    'UserNotifyContent' => [
        'UserNotifyContentRequest' => [
            'notify_id' => ['required' => 'Please select news'],
            'locale' => ['required' => 'Please select language'],
            'content' => ['required' => 'Please enter the content']
        ]
    ],
    'Banner' => [
        'BannerRequest' => [
            'image' => ['required' => 'Please upload the carousel map'],
            'sort' => ['required' => 'Please enter sort'],
            'url' => ['required' => 'Please enter the link address']
        ]
    ],
    'TaskAudit' => [
        'TaskAuditRequest' => [
            'id' => ['required' => 'Please select the task to audit'],
            'status' => ['required' => 'Please select audit result', 'in' => 'The audit result is wrong, please select again']
        ]
    ],
    'CountryBank' => [
        'CountryBankRequest' => [
            'country_id' => ['required' => 'Please select a country'],
            'name' => ['required' => 'Please enter the name of the cardholder'],
            'bank_name' => ['required' => 'Please enter bank name'],
            'bank_address' => ['required' => 'Please enter the bank address'],
            'bank_account' => ['required' => 'Please input bank account number'],
            'address' => ['required' => 'Please enter the collection address']
        ]
    ],
    'Video' => [
        'VideoRequest' => [
            'video' => ['required' => 'Please upload the video'],
            'sort' => ['required' => 'Please enter sort']
        ]
    ],
    'Invitation' => [
        'InvitationRequest' => [
            'image' => ['required' => 'Please upload the picture'],
            'locale' => ['required' => 'Please select language']
        ]
    ]
];