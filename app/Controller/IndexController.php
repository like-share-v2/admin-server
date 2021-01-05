<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Controller;

use App\Service\DAO\MemberDAO;
use App\Service\DAO\UserBillDAO;
use App\Service\DAO\UserRechargeDAO;
use App\Service\DAO\UserTaskDAO;
use App\Service\DAO\UserWithdrawalDAO;
use App\Service\IndexService;

use Cassandra\Index;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;

/**
 * 首页控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * 获取统计数据
     *
     * @GetMapping(path="data")
     */
    public function getData()
    {
        // 提现数据
        $withdrawal_data = $this->container->get(IndexService::class)->getWithdrawalData();

        // 用户任务数据
        $user_task_data = $this->container->get(IndexService::class)->getUserTaskData();

        // 扫码充值数据
        // $manual_recharge_data = $this->container->get(IndexService::class)->getManualRechargeData();

        // 银行卡充值数据
        $bank_recharge_data = $this->container->get(IndexService::class)->getBankRechargeData();

        $this->success([
            'withdrawal_data'    => $withdrawal_data,
            'userTaskData'       => $user_task_data,
            'bankRechargeData' => $bank_recharge_data,
        ]);
    }

    /**
     * 获取每周数据
     *
     * @GetMapping(path="week_data")
     */
    public function getWeekData()
    {
        $user_data        = $this->container->get(IndexService::class)->getWeekRegisterUserData();
        $payment_data     = $this->container->get(IndexService::class)->getWeekPaymentData();
        $withdrawal_data  = $this->container->get(IndexService::class)->getWeekWithdrawalData();
        $user_income_data = $this->container->get(IndexService::class)->getWeekUserIncomeData();

        $this->success([
            'user_data'        => $user_data,
            'payment_data'     => $payment_data,
            'withdrawal_data'  => $withdrawal_data,
            'user_income_data' => $user_income_data
        ]);
    }

    /**
     * 获取统计数据
     *
     * @GetMapping(path="statistical_data")
     */
    public function getStatisticalData()
    {
        $user_count      = $this->container->get(IndexService::class)->getTotalUserCount();
        $payment_sum     = $this->container->get(IndexService::class)->getPaymentSumAmount();
        $withdrawal_sum  = $this->container->get(IndexService::class)->getWithdrawalSumAmount();
        $user_income_sum = $this->container->get(IndexService::class)->getUserIncomeSumAmount();

        $this->success([
            'user_count'      => (float)$user_count,
            'payment_sum'     => (float)$payment_sum,
            'withdrawal_sum'  => (float)$withdrawal_sum,
            'user_income_sum' => (float)$user_income_sum
        ]);
    }

    /**
     * 饼状图数据
     *
     * @GetMapping(path="pie_data")
     */
    public function getPieData()
    {
        // 任务分类任务数据
        $task_category_data = $this->container->get(IndexService::class)->getTaskCategoryData();

        // 用户领取任务分类数据
        $user_task_category_data = $this->container->get(IndexService::class)->getUserReceiveTaskCategoryData();

        // 会员数据
        // $user_level_data = $this->container->get(IndexService::class)->getUserLevelData();

        $this->success([
            'task_category_data'      => $task_category_data,
            'user_task_category_data' => $user_task_category_data,
            'user_level_data'         => []
        ]);
    }

    /**
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        if (!isset($params['time'])) $params['time'] = '';

        if ($params['time'] === '') {
            unset($params['time']);
        } elseif (is_array($params['time']) && count($params['time']) === 2) {
            $params['time'][0] = strtotime($params['time'][0]);
            $params['time'][1] = strtotime($params['time'][1]);
        }

        $recharge_amount_sum = $this->container->get(UserBillDAO::class)->getAmountSum($params, [6, 8]);

        $withdrawal_amount_sum = $this->container->get(UserWithdrawalDAO::class)->getAmountSum($params);

        $withdrawal_user_count = $this->container->get(UserWithdrawalDAO::class)->getUserCount($params);

        $first_recharge_count = $this->container->get(UserBillDAO::class)->getFirstRechargeUserCount($params);

        $overlay_member_count = $this->container->get(UserRechargeDAO::class)->getOverlayMemberCount($params);

        $user_count = $this->container->get(MemberDAO::class)->getMemberCountByParams($params);

        $complete_task_count = $this->container->get(UserTaskDAO::class)->getCompleteUserCount($params);

        $activity_amount = $this->container->get(UserBillDAO::class)->getAmountSum($params, [11]);

        $d_value = $recharge_amount_sum - $withdrawal_amount_sum;

        $this->success([
            'recharge_amount_sum'   => (int)$recharge_amount_sum,
            'withdrawal_amount_sum' => (int)$withdrawal_amount_sum,
            'withdrawal_user_count' => $withdrawal_user_count,
            'first_recharge_count'  => $first_recharge_count,
            'overlay_member_count'  => $overlay_member_count,
            'user_count'            => $user_count,
            'complete_task_count'   => $complete_task_count,
            'activity_amount'       => (int)$activity_amount,
            'd_value'               => (int)$d_value
        ]);
    }

    /**
     * @GetMapping(path="detail")
     */
    public function getDetail()
    {
        $type = (int)$this->request->input('type',1);

        $time = $this->request->input('time', '');

        $perPage = (int)$this->request->input('perPage', 10);

        $search_time = [];
        if (is_array($time) && count($time) === 2) {
            $search_time[0] = strtotime($time[0]);
            $search_time[1] = strtotime($time[1]);
        } else {
            $search_time = null;
        }

        $result = $this->container->get(IndexService::class)->getDetail($type, $perPage, $search_time);

        $this->success($result);
    }
}