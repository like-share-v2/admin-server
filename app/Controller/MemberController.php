<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link https://dayiguo.com
 */

namespace App\Controller;

use App\Request\Member\BalanceRequest;
use App\Request\Member\MemberRequest;
use App\Request\Member\RechargeRequest;
use App\Request\Member\UpdateRequest;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\UserMemberDAO;
use App\Service\DAO\UserRelationDAO;
use App\Service\MemberService;
use App\Service\UserLevelService;

use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;

/**
 * 用户控制器
 *
 * @Controller()
 * @author
 * @package App\Controller\Admin
 */
class MemberController extends AbstractController
{
    /**
     * 获取用户列表
     *
     * @GetMapping(path="")
     */
    public function get()
    {
        $params = $this->request->all();

        $result = $this->container->get(MemberDAO::class)->get($params);

        /* foreach ($result as $value) {
            $level_name = [];
            foreach ($value->userMember as $user_member) {
                $level_name[] = $user_member->userLevel->name_text;
            }
            $level_name = implode(',', $level_name);

            $value->level_name = $level_name;
        } */

        $this->success($result);
    }

    /**
     * 后台充值会员服务
     *
     * @PostMapping(path="recharge")
     * @param RechargeRequest $request
     */
    public function recharge(RechargeRequest $request)
    {
        $user_id = (int)$request->input('id', 0);
        $level = (int)$request->input('level', 0);
        $trade_no = trim($request->input('trade_no', ''));
        $recharge_remark = (string)$request->input('recharge_remark', '');


//        $credit = (int)$request->input('credit', 0);
//        $credit_remark = (string)$request->input('credit_remark', '');
//        $this->container->get(MemberService::class)->recharge($user_id, $level, $credit, $recharge_remark, $credit_remark);
        // $user = $this->container->get(MemberDAO::class)->findUserById($user_id);
        // if ($user->level !== $level) {
            $this->container->get(UserLevelService::class)->rechargeLevel($user_id, $level, $recharge_remark, $trade_no, 2);
         // }

        $this->success();
    }

    /**
     * 批量启用
     *
     * @PutMapping(path="enable/{id}")
     * @param int $id
     */
    public function enable(int $id)
    {
        $this->container->get(MemberDAO::class)->changeStatusById($id, 1);

        $this->success();
    }

    /**
     * 批量禁用
     *
     * @PutMapping(path="disable/{id}")
     * @param int $id
     */
    public function disable(int $id)
    {
        $this->container->get(MemberDAO::class)->changeStatusById($id, 0);

        $this->success();
    }

    /**
     * 修改用户余额
     *
     * @PutMapping(path="balance")
     * @param BalanceRequest $request
     */
    public function changeBalance(BalanceRequest $request)
    {
        // 用户ID
        $user_id = (int)$request->input('id', 0);

        // 变动金额
        $amount = (float)$request->input('amount', 0);

        // 备注
        $remark = trim($request->input('remark', ''));

        $type = (int)$request->input('type', 6);

        $this->container->get(MemberService::class)->changeUserBalance($user_id, $amount, $remark, $type);

        $this->success();
    }

    /**
     * 修改用户信用分
     *
     * @PutMapping(path="credit")
     * @param BalanceRequest $request
     */
    public function changeCredit(BalanceRequest $request)
    {
        // 用户ID
        $user_id = (int)$request->input('id', 0);

        // 变动信用分
        $credit = (int)$request->input('amount', 0);

        // 备注
        $remark = trim($request->input('remark', ''));

        $this->container->get(MemberService::class)->changeUserCredit($user_id, $credit,  $remark);

        $this->success();
    }

    /*public function changeParentId()
    {
        $user_id = (int)$this->request->input('id', 0);

        $parent_id = $this->request->input('parent_id', 0);

        $this->container->get(MemberService::class)->changeUserParentId($user_id, $parent_id);

        $this->success();
    }*/

    /**
     * 获取下级团队列表
     *
     * @GetMapping(path="team")
     */
    public function getTeam()
    {
        // $user_id = (int)$this->request->input('id', 0);

        // $result = $this->container->get(MemberService::class)->getUserTeam($user_id);

        $params = map_filter([
            'parent_id' => 'Integer',
            'level' => 'Integer',
            'perPage' => 'Integer'
        ], $this->request->all());

        $result = $this->container->get(UserRelationDAO::class)->get($params);

        $this->success($result);
    }

    /**
     * @GetMapping(path="team_level")
     */
    public function getTeamLevel()
    {
        $parent_id = $this->request->input('parent_id', '');

        $result = $this->container->get(UserRelationDAO::class)->getTeamLevelByParentId((int)$parent_id);

        $this->success($result);
    }

    /**
     * 添加用户
     *
     * @PostMapping(path="")
     * @param MemberRequest $request
     */
    public function create(MemberRequest $request)
    {
        $params = $request->all();

        $this->container->get(MemberService::class)->create($params);

        $this->success();
    }

    /**
     * @PutMapping(path="set_up_agent")
     */
    public function setUpUserAgent()
    {
        $id = (int)$this->request->input('id');

        $this->container->get(MemberService::class)->setUpUserAgent($id);

        $this->success();
    }

    /**
     *
     * @PutMapping(path="cancel_agent")
     */
    public function cancelUserAgent()
    {
        $id = (int)$this->request->input('id');

        $this->container->get(MemberService::class)->cancelUserAgent($id);

        $this->success();
    }

    /**
     * 更新用户数据
     *
     * @PutMapping(path="update")
     */
    public function update()
    {
        $params = $this->request->all();

        $this->container->get(MemberService::class)->update($params);

        $this->success();
    }

    /**
     * 修改用户会员到期时间
     *
     * @PutMapping(path="effective_time")
     */
    public function updateEffectiveTime()
    {
        $user_id = (int)$this->request->input('id', 0);
        $level = (int)$this->request->input('level', -1);
        $day = (int)$this->request->input('day', 0);

        $this->container->get(MemberService::class)->updateEffectiveTime($user_id, $day, $level);

        $this->success();
    }

    /**
     * 修改上级ID
     *
     * @PutMapping(path="changeParentId")
     */
    public function changeParentId()
    {
        $user_id = (int)$this->request->input('id', 0);
        $parent_id = (int)$this->request->input('parent_id', 0);

        $this->container->get(MemberService::class)->changeParentId($user_id, $parent_id);

        $this->success();
    }

    /**
     * @GetMapping(path="user_member")
     */
    public function checkUserMember()
    {
        $user_id = (int)$this->request->input('id', 0);

        $result = $this->container->get(UserMemberDAO::class)->getListByUserId($user_id);

        $this->success($result);
    }
}