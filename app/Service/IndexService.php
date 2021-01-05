<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Service;

use App\Common\Base;
use App\Model\Language;
use App\Model\Payment;
use App\Model\User;
use App\Model\UserBankRecharge;
use App\Model\UserBill;
use App\Model\UserManualRecharge;
use App\Model\UserRecharge;
use App\Model\UserTask;
use App\Model\UserWithdrawal;
use App\Service\DAO\LanguageDAO;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\PaymentDAO;
use App\Service\DAO\TaskCategoryDAO;
use App\Service\DAO\UserBillDAO;
use App\Service\DAO\UserLevelDAO;
use App\Service\DAO\UserManualRechargeDAO;
use App\Service\DAO\UserRechargeDAO;
use App\Service\DAO\UserTaskDAO;
use App\Service\DAO\UserWithdrawalDAO;
use Carbon\Carbon;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\DbConnection\Db;

/**
 * 主页服务
 *
 * @author 
 * @package App\Service
 */
class IndexService extends Base
{
    /**
     * 获取充值数据
     *
     * @return array
     */
    public function getRechargeData()
    {
        $recharge_list = $this->container->get(UserRechargeDAO::class)->get([])->toArray();

        // 在线充值总额
        $online_recharge_sum = 0;

        // 后台充值总额
        $admin_recharge_sum = 0;

        // 扫码充值总额
        $manual_recharge_sum = 0;

        foreach ($recharge_list as $recharge_key => $recharge) {
            switch ($recharge['channel']) {
                case 1:
                    $online_recharge_sum += $recharge['balance'];
                    break;
                case 2:
                    $admin_recharge_sum += $recharge['balance'];
                    break;
                case 3:
                    $manual_recharge_sum += $recharge['balance'];
                    break;
                default :
                    break;
            }
        }

        // 总充值金额
        $total_recharge_sum = $online_recharge_sum + $admin_recharge_sum + $manual_recharge_sum;

        return [
            'total_recharge_sum'  => $total_recharge_sum,
            'online_recharge_sum' => $online_recharge_sum,
            'admin_recharge_sum'  => $admin_recharge_sum,
            'manual_recharge_sum' => $manual_recharge_sum
        ];
    }

    /**
     * 获取提现数据
     *
     * @return array
     */
    public function getWithdrawalData()
    {
        return [
            'withdrawal_audit_count'  => UserWithdrawal::query()->where('status', 0)->count(),
            'withdrawal_pass_count'   => UserWithdrawal::query()->where('status', 1)->count(),
            'withdrawal_refuse_count' => UserWithdrawal::query()->where('status', 2)->count()
        ];

        $withdrawal_list = $this->container->get(UserWithdrawalDAO::class)->get([])->toArray();

        // 待审核总金额
        $withdrawal_audit_amount = 0;
        // 待审核总数量
        $withdrawal_audit_count = 0;
        // 已通过总金额
        $withdrawal_pass_amount = 0;
        // 已通过总数量
        $withdrawal_pass_count = 0;
        // 未通过总金额
        $withdrawal_refuse_amount = 0;
        // 未通过总数
        $withdrawal_refuse_count = 0;

        foreach ($withdrawal_list as $withdrawal) {
            switch ($withdrawal['status']) {
                case 0:
                    $withdrawal_audit_amount += $withdrawal['amount'];
                    $withdrawal_audit_count  += 1;
                    break;
                case 1:
                    $withdrawal_pass_amount += $withdrawal['amount'];
                    $withdrawal_pass_count  += 1;
                    break;
                case 2:
                    $withdrawal_refuse_amount += $withdrawal['amount'];
                    $withdrawal_refuse_count  += 1;
                    break;
                default:
                    break;
            }
        }

        return [
            'withdrawal_audit_amount'  => $withdrawal_audit_amount,
            'withdrawal_audit_count'   => $withdrawal_audit_count,
            'withdrawal_pass_amount'   => $withdrawal_pass_amount,
            'withdrawal_pass_count'    => $withdrawal_pass_count,
            'withdrawal_refuse_amount' => $withdrawal_refuse_amount,
            'withdrawal_refuse_count'  => $withdrawal_refuse_count
        ];
    }

    /**
     * 获取用户任务数据
     *
     * @return array
     */
    public function getUserTaskData()
    {
        return [
            'user_task_progress_count' => UserTask::query()->where('status', 0)->count(),
            'user_task_audit_count'    => UserTask::query()->where('status', 1)->count(),
            'user_task_pass_count'     => UserTask::query()->where('status', 2)->count(),
            'user_task_refuse_count'   => UserTask::query()->where('status', 3)->count(),
            'user_task_cancel_count'   => UserTask::query()->where('status', 4)->count()
        ];

        $user_task_list = $this->container->get(UserTaskDAO::class)->get([])->toArray();

        // 进行中任务数
        $user_task_progress_count = 0;
        // 进行中任务金额
        $user_task_progress_amount = 0;
        // 待审核任务数
        $user_task_audit_count = 0;
        // 待审核任务金额
        $user_task_audit_amount = 0;
        // 已通过任务数
        $user_task_pass_count = 0;
        // 已通过任务金额
        $user_task_pass_amount = 0;
        // 未通过任务数
        $user_task_refuse_count = 0;
        // 未通过任务金额
        $user_task_refuse_amount = 0;
        // 已取消任务数
        $user_task_cancel_count = 0;
        // 已取消任务金额
        $user_task_cancel_amount = 0;

        foreach ($user_task_list as $user_task) {
            switch ($user_task['status']) {
                case 0:
                    $user_task_progress_count  += 1;
                    $user_task_progress_amount += $user_task['amount'];
                    break;
                case 1:
                    $user_task_audit_count  += 1;
                    $user_task_audit_amount += $user_task['amount'];
                    break;
                case 2:
                    $user_task_pass_count  += 1;
                    $user_task_pass_amount += $user_task['amount'];
                    break;
                case 3:
                    $user_task_refuse_count  += 1;
                    $user_task_refuse_amount += $user_task['amount'];
                    break;
                case 4:
                    $user_task_cancel_count  += 1;
                    $user_task_cancel_amount += $user_task['amount'];
                    break;
                default:
                    break;
            }
        }

        return [
            'user_task_progress_count'  => $user_task_progress_count,
            'user_task_progress_amount' => (float)number_format($user_task_progress_amount, 2),
            'user_task_audit_count'     => $user_task_audit_count,
            'user_task_audit_amount'    => (float)number_format($user_task_audit_amount, 2),
            'user_task_pass_count'      => $user_task_pass_count,
            'user_task_pass_amount'     => (float)number_format($user_task_pass_amount, 2),
            'user_task_refuse_count'    => $user_task_refuse_count,
            'user_task_refuse_amount'   => (float)number_format($user_task_refuse_amount, 2),
            'user_task_cancel_count'    => $user_task_cancel_count,
            'user_task_cancel_amount'   => (float)number_format($user_task_cancel_amount, 2)
        ];
    }

    public function getBankRechargeData()
    {
        return [
            'audit_count' => UserBankRecharge::query()->where('status', 0)->count(),
            'pass_count' => UserBankRecharge::query()->where('status', 1)->count(),
            'refuse_count' => UserBankRecharge::query()->where('status', 2)->count()
        ];
    }

    /**
     * 获取手动充值数据
     *
     * @return array
     */
    public function getManualRechargeData()
    {
        return [
            'manual_recharge_audit_count'  => UserManualRecharge::query()->where('status', 0)->count(),
            'manual_recharge_pass_count'   => UserManualRecharge::query()->where('status', 1)->count(),
            'manual_recharge_refuse_count' => UserManualRecharge::query()->where('status', 2)->count()
        ];
        $manual_recharge_data = $this->container->get(UserManualRechargeDAO::class)->get([])->toArray();

        // 待审核总数
        $manual_recharge_audit_count = 0;
        // 待审核总金额
        $manual_recharge_audit_amount = 0;
        // 已通过总数
        $manual_recharge_pass_count = 0;
        // 已通过总金额
        $manual_recharge_pass_amount = 0;
        // 未通过总数
        $manual_recharge_refuse_count = 0;
        // 未通过总金额
        $manual_recharge_refuse_amount = 0;

        foreach ($manual_recharge_data as $manual_recharge) {
            switch ($manual_recharge['status']) {
                case 0:
                    $manual_recharge_audit_count  += 1;
                    $manual_recharge_audit_amount += $manual_recharge['amount'];
                    break;
                case 1:
                    $manual_recharge_pass_count  += 1;
                    $manual_recharge_pass_amount += $manual_recharge['amount'];
                    break;
                case 2:
                    $manual_recharge_refuse_count  += 1;
                    $manual_recharge_refuse_amount += $manual_recharge['amount'];
                    break;
                default:
                    break;
            }
        }

        return [
            'manual_recharge_audit_count'   => $manual_recharge_audit_count,
            'manual_recharge_audit_amount'  => $manual_recharge_audit_amount,
            'manual_recharge_pass_count'    => $manual_recharge_pass_count,
            'manual_recharge_pass_amount'   => $manual_recharge_pass_amount,
            'manual_recharge_refuse_count'  => $manual_recharge_refuse_count,
            'manual_recharge_refuse_amount' => $manual_recharge_refuse_amount
        ];
    }

    /**
     * 获取用户数据
     *
     * @return array
     */
    public function getUserData()
    {
        $user_list = $this->container->get(MemberDAO::class)->get([])->toArray();

        // 当前用户总数
        $user_count = count($user_list);
        // 今日注册总数
        $today_user_count = 0;
        // 最近七日注册数
        $week_user_count = 0;
        // 最近一个月注册数
        $month_user_count = 0;
        // 当前用户余额总和
        $user_balance_amount = 0;

        foreach ($user_list as $user) {
            $user_balance_amount += $user['balance'];

            if (strtotime($user['created_at']) > strtotime(date('Y-m-d'))) {
                $today_user_count += 1;
            }

            if (strtotime($user['created_at']) > strtotime('-7 days')) {
                $week_user_count += 1;
            }

            if (strtotime($user['created_at']) > strtotime('-1 month')) {
                $month_user_count += 1;
            }
        }

        return [
            'user_count'          => $user_count,
            'user_balance_amount' => $user_balance_amount,
            'today_user_count'    => $today_user_count,
            'week_user_count'     => $week_user_count,
            'month_user_count'    => $month_user_count
        ];
    }

    /**
     * 获取用户收益
     *
     * @return array
     */
    public function getUserIncomeData()
    {
        $user_bill_list = $this->container->get(UserBillDAO::class)->get([])->toArray();

        // 用户任务收益总额
        $user_task_income_amount = 0;
        // 用户下级收益总额
        $user_lower_income_amount = 0;
        // 系统充值总额
        $system_recharge_amount = 0;

        foreach ($user_bill_list as $user_bill) {
            switch ($user_bill['way']) {
                case 2:
                    $user_task_income_amount += $user_bill['balance'];
                    break;
                case 3:
                    $user_lower_income_amount += $user_bill['balance'];
                    break;
                case 4:
                    $system_recharge_amount += $user_bill['balance'];
                    break;
                case 1:
                default:
                    break;
            }
        }

        // 用户收益总金额
        $user_total_income_amount = $user_task_income_amount + $user_lower_income_amount + $system_recharge_amount;

        return [
            'user_task_income_amount'  => $user_task_income_amount,
            'user_lower_income_amount' => $user_lower_income_amount,
            'user_total_income_amount' => $user_total_income_amount
        ];
    }

    /**
     * 获取用户等级数据
     *
     * @return array
     */
    public function getUserLevelData()
    {
        $data = $this->container->get(UserLevelDAO::class)->getUserCount()->toArray();

        $data = array_map(function ($user_level) {
            $user_level['value'] = $user_level['user_count'];
            unset($user_level['user_count']);
            $user_level['name'] = $user_level['name_text'];
            return $user_level;
        }, $data);

        $name = array_column($data, 'name');

        return ([
            'name' => $name,
            'data' => $data
        ]);
    }

    /**
     * 本周新注册用户数据
     *
     * @return array
     */
    public function getWeekRegisterUserData()
    {
        $start_date = Carbon::parse('-6 days')->toDateString();
        $end_date   = Carbon::now()->toDateString() . ' 23:59:59';

        $user_data = array_column(User::query()
            ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(created_at),"%Y-%m-%d") as date')
            ->selectRaw('COUNT(*) as count')
            ->whereBetween('created_at', [strtotime($start_date), strtotime($end_date)])
            ->groupBy('date')
            ->get()
            ->toArray(), 'count', 'date');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::parse('-' . $i . 'days')->toDateString();
            if (in_array($date, array_keys($user_data))) {
                $result['date'][]  = $date;
                $result['count'][] = $user_data[$date];
            } else {
                $result['date'][]  = $date;
                $result['count'][] = 0;
            }
        }

        return $result;
    }

    /**
     * 获取本周支付数据
     *
     * @return array
     */
    public function getWeekPaymentData()
    {
        $start_date = Carbon::parse('-6 days')->toDateString();
        $end_date   = Carbon::now()->toDateString() . ' 23:59:59';

        $payment_data = array_column(UserBill::query()
            ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(created_at),"%Y-%m-%d") as date')
            ->selectRaw('abs(sum(balance)) as amount')
            ->where('balance', '<', 0)
            ->whereBetween('created_at', [strtotime($start_date), strtotime($end_date)])
            ->groupBy('date')
            ->get()
            ->toArray(), 'amount', 'date');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::parse('-' . $i . 'days')->toDateString();
            if (in_array($date, array_keys($payment_data))) {
                $result['date'][]   = $date;
                $result['amount'][] = $payment_data[$date];
            } else {
                $result['date'][]   = $date;
                $result['amount'][] = 0;
            }
        }

        return $result;
    }

    /**
     * 获取本周提现数据
     *
     * @return array
     */
    public function getWeekWithdrawalData()
    {
        $start_date = Carbon::parse('-6 days')->toDateString();
        $end_date   = Carbon::now()->toDateString() . ' 23:59:59';

        $withdrawal_data = array_column(UserWithdrawal::query()
            ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(updated_at), "%Y-%m-%d") as date')
            ->selectRaw('sum(amount) as amount')
            ->where('status', 1)
            ->whereBetween('updated_at', [strtotime($start_date), strtotime($end_date)])
            ->groupBy('date')
            ->get()
            ->toArray(), 'amount', 'date');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::parse('-' . $i . 'days')->toDateString();
            if (in_array($date, array_keys($withdrawal_data))) {
                $result['date'][]   = $date;
                $result['amount'][] = $withdrawal_data[$date];
            } else {
                $result['date'][]   = $date;
                $result['amount'][] = 0;
            }
        }

        return $result;
    }

    public function getWeekUserIncomeData()
    {
        $start_date = Carbon::parse('-6 days')->toDateString();
        $end_date   = Carbon::now()->toDateString() . ' 23:59:59';

        $user_income_data = array_column(UserBill::query()
            ->selectRaw('DATE_FORMAT(FROM_UNIXTIME(created_at), "%Y-%m-%d") as date')
            ->selectRaw('sum(balance) as amount')
            ->whereNotIn('type', [5, 6, 8, 12])
            ->where('balance', '>', 0)
            ->whereBetween('created_at', [strtotime($start_date), strtotime($end_date)])
            ->groupBy('date')
            ->get()
            ->toArray(), 'amount', 'date');

        $result = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::parse('-' . $i . 'days')->toDateString();
            if (in_array($date, array_keys($user_income_data))) {
                $result['date'][]   = $date;
                $result['amount'][] = (float)$user_income_data[$date];
            } else {
                $result['date'][]   = $date;
                $result['amount'][] = 0;
            }
        }

        return $result;
    }

    /**
     * 获取用户总数
     *
     * @return int
     */
    public function getTotalUserCount()
    {
        return $this->container->get(MemberDAO::class)->getMemberCount();
    }

    /**
     * 获取支付成功总金额
     *
     * @return int
     */
    public function getPaymentSumAmount()
    {
        return abs($this->container->get(UserBillDAO::class)->getPayAmountSum());
    }

    /**
     * 获取提现成功总金额
     *
     * @return int
     */
    public function getWithdrawalSumAmount()
    {
        return $this->container->get(UserWithdrawalDAO::class)->getPassAmountSum();
    }

    /**
     * 获取已完成任务总金额
     *
     * @return int
     */
    public function getUserTaskSum()
    {
        return $this->container->get(UserTaskDAO::class)->getCompleteUserTask();
    }

    /**
     * 获取用户收入总金额
     *
     * @return int
     */
    public function getUserIncomeSumAmount()
    {
        return $this->container->get(UserBillDAO::class)->getIncomeAmountSum();
    }

    /**
     * 获取用户领取任务的分类数据
     *
     * @return array
     */
    public function getUserReceiveTaskCategoryData()
    {
        $raw = 'select `task_category`.`name`, (select count(*) from `user_task` inner join `task` on `task`.`id` = `user_task`.`task_id` where `task`.`deleted_at` is null and `task_category`.`id` = `task`.`category_id` and `user_task`.`status` in (0, 1, 2) and `task`.`deleted_at` is null) as `value` from `task_category`';

        $data = Db::select($raw);

        $local = $this->container->get(TranslatorInterface::class)->getLocale();


        foreach ($data as $datum) {
            $datum->name = Language::query()->where('local', $local)->where('key', $datum->name)->value('value') ?? $datum->name;
        }

        $name = array_column($data, 'name');

        return [
            'name' => $name,
            'data' => $data
        ];
    }

    /**
     * 获取任务分类数据
     *
     * @return array
     */
    public function getTaskCategoryData()
    {
        $data = array_map(function ($task_category) {
            $task_category['value'] = $task_category['task_count'];
            unset($task_category['task_count']);
            $task_category['name'] = $task_category['name_text'];
            return $task_category;
        }, $this->container->get(TaskCategoryDAO::class)->getTaskNum()->toArray());

        $name = array_column($data, 'name');

        return [
            'name' => $name,
            'data' => $data
        ];
    }

    public function getDetail(int $type, int $perPage, array $time = null)
    {
        switch ($type) {
            case 1:
                // 充值金额
                //  $result = $this->container->get(UserBillDAO::class)->getDetail(['type' => [6, 8], 'time' => $time, 'perPage' => $perPage]);
                $model = UserBill::query();
                if ($time !== null) {
                    $model->whereBetween('created_at', $time);
                }

                $result = $model->whereIn('type', [6, 8])->paginate($perPage);

                break;
            case 2:
                // 提现金额
                $result = $this->container->get(UserWithdrawalDAO::class)->get(['type' => 1, 'time' => $time, 'perPage' => $perPage]);

                break;
            case 3:
                // 首充人数
                $model = UserBill::query()->with('user:id,account,phone,nickname');

                if (is_array($time) && count($time) === 2) {
                    $model->whereBetween('created_at', $time);
                }

                $result = $model->whereIn('type', [6, 8])->orderByDesc('id')->groupBy('user_id')->paginate($perPage);
                break;
            case 4;
                // 叠加会员
                $user_ids = $this->container->get(UserRechargeDAO::class)->getOverLayMemberIds(['time' => $time]);

                $model = UserRecharge::query()->with(['user:id,account,phone,nickname', 'userLevel:level,name', 'payment']);

                if (is_array($time) && count($time) === 2) {
                    $model->whereBetween('recharge_time', $time);
                }

                $result = $model->where('status', 1)->whereIn('user_id', $user_ids)
                    ->groupBy('user_id')
                    ->orderByDesc('id')
                    ->paginate($perPage);
                break;
            case 5:
                // 新用户数量
                $result = $this->container->get(MemberDAO::class)->get(['search_time' => $time, 'perPage' => $perPage]);
                break;
            case 6:
                // 完成任务人数
                $model = UserTask::query()->where('status', 2);

                if ($time !== null) {
                    $model->whereBetween('audit_time', $time);
                }

                return $model->groupBy('user_id')->orderBy('id')->paginate($perPage);
                break;
            case 7:
                // 赠送活动金额
                $model = UserBill::query();
                if ($time !== null) {
                    $model->whereBetween('created_at', $time);
                }

                $result = $model->where('type', 11)->paginate($perPage);
                break;
            case 8:
                // 提现人数
                $model = UserWithdrawal::query()->where('status', 1)->groupBy('user_id');

                if ($time !== null) {
                    $model->whereBetween('updated_at', $time);
                }

                $result = $model->paginate($perPage);
                break;
            default :
                $result = null;
        }

        return $result;
    }
}