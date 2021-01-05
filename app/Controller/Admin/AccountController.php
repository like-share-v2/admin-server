<?php

declare (strict_types=1);
/**
 * @copyright zunea/hyperf-admin
 * @version   1.0.0
 * @link      https://github.com/Zunea/hyperf-admin
 */

namespace App\Controller\Admin;

use App\Controller\AbstractController;
use App\Exception\LogicException;
use App\Kernel\Utils\JwtInstance;
use App\Kernel\Utils\UserJwtInstance;
use App\Model\AdminGoogleAuth;
use App\Model\User;
use App\Request\Account\ChangePasswordRequest;
use App\Service\UserService;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use PHPGangsta_GoogleAuthenticator;

/**
 * 获取账户信息
 *
 * @Controller()
 * @author  
 * @package App\Controller
 */
class AccountController extends AbstractController
{
    /**
     * @Inject()
     * @var UserService
     */
    private $userService;

    /**
     * 用户信息接口
     *
     * @GetMapping(path="info")
     */
    public function info()
    {
        $user = JwtInstance::instance()->build()->getUser();

        $this->success([
            'id'          => $user->id,
            'username'    => $user->username,
            'nickname'    => $user->nickname,
            'phone'       => $user->phone,
            'email'       => $user->email,
            'avatar'      => $user->avatar,
            'google_auth' => $user->google_auth
        ]);
    }

    /**
     * 退出接口
     *
     * @PostMapping(path="logout")
     */
    public function logout()
    {
        $this->success();
    }

    /**
     * 获取菜单接口
     *
     * @GetMapping(path="menu")
     */
    public function menu()
    {
        $user = JwtInstance::instance()->build()->getUser();

        $result = $this->userService->getMenus($user->id);

        $this->success($result);
    }

    /**
     * 获取选项接口
     *
     * @GetMapping(path="option")
     */
    public function option()
    {
        $result = $this->userService->getOptions();

        $this->success($result);
    }

    /**
     * 修改密码
     *
     * @PutMapping(path="password")
     * @param ChangePasswordRequest $request
     */
    public function password(ChangePasswordRequest $request)
    {
        $user = JwtInstance::instance()->build()->getUser();
        if (!password_verify($request->post('old_password'), $user->password)) {
            $this->formError([
                'old_password' => 'logic.PASSWORD_ERROR'
            ]);
        }
        $user->password = $request->post('new_password');
        $user->save();

        // 清理缓存
        $this->flushCache('UpdateAdminUser', [$user->id]);

        $this->success();
    }

    /**
     * 获取WebSocket Token
     *
     * @GetMapping(path="wstoken")
     */
    public function getWsToken()
    {
        /** @var User $user */
        if (!$user = User::query()->find(0)) {
            $this->error('logic.USER_NOT_FOUND');
        }

        $token = UserJwtInstance::instance()->encode($user);

        $this->success($token);
    }

    /**
     * 获取谷歌两步验证验证码
     *
     * @GetMapping(path="google_auth_qrcode")
     */
    public function getGoogleAuthQRCode()
    {
        $user = JwtInstance::instance()->build()->getUser();

        try {
            $ga        = new PHPGangsta_GoogleAuthenticator();
            $secret    = $ga->createSecret();
            $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username, $secret, getConfig('web_title', $user->username));
        }
        catch (\Throwable $e) {
            $this->error($e->getMessage());
            return;
        }

        $this->success([
            'qrCodeUrl' => $qrCodeUrl,
            'secret'    => $secret
        ]);
    }

    /**
     * 绑定谷歌两步验证
     *
     * @PostMapping(path="bind_google_auth")
     */
    public function bindGoogleAuth()
    {
        $user   = JwtInstance::instance()->build()->getUser();
        $secret = $this->request->input('secret');
        $code   = $this->request->input('code');

        try {
            $ga = new PHPGangsta_GoogleAuthenticator();
            if (!$checkResult = $ga->verifyCode($secret, $code, 2)) {
                throw new LogicException('logic.GOOGLE_AUTH_VERIFY_CODE_ERROR');
            }
            AdminGoogleAuth::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'secret'    => $secret,
                'is_enable' => true
            ]);
        }
        catch (\Throwable $e) {
            $this->error($e->getMessage());
        }

        $this->success();
    }

    /**
     * 开启/关闭谷歌两步验证
     *
     * @PostMapping(path="switch_google_auth")
     */
    public function switchGoogleAuth()
    {
        $user = JwtInstance::instance()->build()->getUser();
        if (!$user->google_auth) {
            $this->error('logic.NO_BINDING_GOOGLE_AUTH');
        }

        $user->google_auth->is_enable = !$user->google_auth->is_enable;
        $user->google_auth->save();

        $this->success([
            'is_enable' => $user->google_auth->is_enable
        ]);
    }
}