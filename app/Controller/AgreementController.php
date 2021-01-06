<?php

declare (strict_types=1);
/**
 * @copyright
 * @version 1.0.0
 * @link
 */

namespace App\Controller;

use App\Service\ConfigService;
use App\Service\DAO\AdminConfigDAO;

use App\Service\DAO\AgreementDAO;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PutMapping;
use Psr\SimpleCache\InvalidArgumentException;

/**
 * 协议控制器
 *
 * @Controller()
 * @author
 * @package App\Controller
 */
class AgreementController extends AbstractController
{
    /**
     * 获取使用协议
     *
     * @GetMapping(path="usage_agreement")
     */
    public function getUsageAgreement()
    {
//        $content = $this->container->get(ConfigService::class)->get('usage_agreement', '');
        $locale = $this->request->input('locale', 'zh-CN');

        $result = $this->container->get(AgreementDAO::class)->get(['type' => 1, 'locale' => $locale]);

        $this->success($result);
    }

    /**
     * 修改使用协议
     *
     * @PutMapping(path="usage_agreement")
     */
    public function editUsageAgreement()
    {
        $content = $this->request->input('content', '');
        $locale = $this->request->input('locale', '');

        if ($locale === '') {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        $this->container->get(AgreementDAO::class)->updateOrCreate(['type' => 1, 'locale' => $locale, 'content' => $content]);

        // 更新使用协议
//        $this->container->get(AdminConfigDAO::class)->updateValueByName('usage_agreement', $content);

        // 刷新缓存
//        $this->container->get(ConfigService::class)->initConfigs();

        $this->success();
    }

    /**
     * 获取隐私政策
     *
     * @GetMapping(path="privacy_policy")
     */
    public function getPrivacyPolicy()
    {
//        $content = $this->container->get(ConfigService::class)->get('privacy_policy', '');

        $locale = $this->request->input('locale', '');

        $result = $this->container->get(AgreementDAO::class)->get(['type' => 2, 'locale' => $locale]);

        $this->success($result);
    }

    /**
     * 修改隐私政策
     *
     * @PutMapping(path="privacy_policy")
     */
    public function editPrivacyPolicy()
    {
        $content = $this->request->input('content', '');
        $locale = $this->request->input('locale', '');

        if ($locale === '') {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        $this->container->get(AgreementDAO::class)->updateOrCreate(['type' => 2, 'locale' => $locale, 'content' => $content]);

        /* // 更新隐私政策 privacy_policy
        $this->container->get(AdminConfigDAO::class)->updateValueByName('privacy_policy', $content);

        // 刷新缓存
        $this->container->get(ConfigService::class)->initConfigs();*/

        $this->success();
    }

    /**
     * 获取信用分规则
     *
     * @GetMapping(path="credit_rule")
     */
    public function getCreditRule()
    {
//        $content = $this->container->get(ConfigService::class)->get('credit_rule', '');

        $locale = $this->request->input('locale', '');

        $result = $this->container->get(AgreementDAO::class)->get(['type' => 3, 'locale' => $locale]);

        $this->success($result);
    }

    /**
     * 修改信用分规则
     *
     * @PutMapping(path="credit_rule")
     */
    public function editCreditRule()
    {
        /* $content = $this->request->input('content', '');

        $this->container->get(AdminConfigDAO::class)->updateValueByName('credit_rule', $content);

        // 刷新缓存
        $this->container->get(ConfigService::class)->initConfigs(); */

        $content = $this->request->input('content', '');
        $locale = $this->request->input('locale', '');

        if ($locale === '') {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        $this->container->get(AgreementDAO::class)->updateOrCreate(['type' => 3, 'locale' => $locale, 'content' => $content]);

        $this->success();
    }

    /**
     * 获取首页弹窗
     *
     * @GetMapping(path="home_pop_up")
     */
    public function getHomePopUp()
    {
        $locale = $this->request->input('locale', '');

        $result = $this->container->get(AgreementDAO::class)->get(['type' => 4, 'locale' => $locale]);

        $this->success($result);
    }

    /**
     * @PutMapping(path="home_pop_up")
     */
    public function editHomePopUp()
    {
        $content = $this->request->input('content', '');
        $locale = $this->request->input('locale', '');

        if ($locale === '') {
            $this->error('logic.COUNTRY_NOT_FOUND');
        }

        $this->container->get(AgreementDAO::class)->updateOrCreate(['type' => 4, 'locale' => $locale, 'content' => $content]);

        $this->success();
    }
}