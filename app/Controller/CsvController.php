<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Csv;
use App\Model\User;
use App\Service\DAO\CsvDAO;
use App\Service\DAO\MemberDAO;
use App\Service\DAO\UserBankRechargeDAO;
use App\Service\DAO\UserOnlineRechargeDAO;
use App\Service\DAO\UserWithdrawalDAO;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Response;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 *
 * @AutoController()
 * @package App\Controller
 */
class CsvController extends AbstractController
{
    public function test($AResult){
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        //设置第一行的表头
        $sheet->setCellValue('A1', '我是第一行');
        //把数据循环写入到excel里
        foreach ($AResult as $k => $v) {
            $num = $k + 2;
            //从第二行开始写
            $sheet->setCellValue('A' . $num, $v);
        }
        $writer   = new Xlsx($spreadsheet);
        //这里可以写绝对路径，其他框架到这步就结束了
        $writer->save('test.xlsx');
        //关闭连接，销毁变量
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
    }

    /**
     */
    public function user()
    {
        try {
            $spreadsheet = new Spreadsheet();
            //设置表格
            $spreadsheet->setActiveSheetIndex(0);
            //设置表头
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1','ID')
                ->setCellValue('B1','国家')
                ->setCellValue('C1','上级ID')
//                ->setCellValue('D1','会员等级')
//                ->setCellValue('E1','会员到期时间')
                ->setCellValue('D1','国家区号')
                ->setCellValue('E1','手机号码')
                ->setCellValue('F1','余额')
                ->setCellValue('G1','ip')
                ->setCellValue('H1','创建时间');
            //设置表头居中
            $spreadsheet->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
//            $spreadsheet->setActiveSheetIndex(0)->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
//            $spreadsheet->setActiveSheetIndex(0)->getStyle('J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(50);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);

            go(function () use ($spreadsheet) {
                //查询数据
                $rows = $this->container->get(MemberDAO::class)->getUsers()->toArray()['data'];
                //遍历数据
                foreach ($rows as $i => $user)
                {
                    $spreadsheet->getActiveSheet()->setCellValue('A'.($i+2), $user['id']);
                    $spreadsheet->getActiveSheet()->setCellValue('B'.($i+2), $user['country']['name_text'] ?? '');
                    $spreadsheet->getActiveSheet()->setCellValue('C'.($i+2), $user['parent_id']);
//                    $spreadsheet->getActiveSheet()->setCellValue('D'.($i+2), $user['userLevel']['name_text'] ?? '');
//                    $spreadsheet->getActiveSheet()->setCellValue('E'.($i+2), $user['effective_time']);
                    $spreadsheet->getActiveSheet()->setCellValue('D'.($i+2), $user['country_code'] ?? '-');
                    $spreadsheet->getActiveSheet()->setCellValue('E'.($i+2), $user['phone']);
                    $spreadsheet->getActiveSheet()->setCellValue('F'.($i+2), $user['balance']);
                    $spreadsheet->getActiveSheet()->setCellValue('G'.($i+2), $user['ip']);
                    $spreadsheet->getActiveSheet()->setCellValue('H'.($i+2), $user['created_at']);
                }

                $writer = IOFactory::createWriter($spreadsheet,'Xls');
                //设置filename
                $filename = '用户列表'.'-'.date('YmdHis').'.xls';

                $path = BASE_PATH . '/runtime/';
                //保存
                $writer->save($path . $filename);

                $this->container->get(CsvDAO::class)->create([
                    'filename' => $filename
                ]);
            });

        }catch (\Throwable $throwable){
            $this->error($throwable->getMessage());
        }

        $this->success();
    }

    /**
     * @GetMapping(path="withdrawal")
     */
    public function withdrawal()
    {
        try {
            $spreadsheet = new Spreadsheet();
            //设置表格

            $sheet       = $spreadsheet->getActiveSheet();
            $spreadsheet->setActiveSheetIndex(0);
            //设置表头
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1','用户ID')
                ->setCellValue('B1','国家')
                ->setCellValue('C1','提现金额')
                ->setCellValue('D1','汇率')
                ->setCellValue('E1','手续费比率')
                ->setCellValue('F1','银行名')
                ->setCellValue('G1','姓名')
                ->setCellValue('H1','银行账号')
                ->setCellValue('I1','提现状态')
                ->setCellValue('J1','提现时间');
            //设置表头居中
            $spreadsheet->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(40);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);


            $status_arr = [
                '待审核',
                '已打款',
                '未通过'
            ];

            go(function () use ($spreadsheet, $status_arr) {
                //查询数据
                $rows = $this->container->get(UserWithdrawalDAO::class)->get([])->toArray();
                //遍历数据
                foreach ($rows as $i => $data)
                {
                    $spreadsheet->getActiveSheet()->setCellValue('A'.($i+2), $data['user_id']);
                    $spreadsheet->getActiveSheet()->setCellValue('B'.($i+2), $data['country']['name_text'] ?? '');
                    $spreadsheet->getActiveSheet()->setCellValue('C'.($i+2), $data['amount']);
                    $spreadsheet->getActiveSheet()->setCellValue('D'.($i+2), 0);
                    $spreadsheet->getActiveSheet()->setCellValue('E'.($i+2), $data['service_charge']);
                    $spreadsheet->getActiveSheet()->setCellValue('F'.($i+2), $data['bank_name']);
                    $spreadsheet->getActiveSheet()->setCellValue('G'.($i+2), $data['name']);
                    $spreadsheet->getActiveSheet()->setCellValue('H'.($i+2), $data['account']);
                    $spreadsheet->getActiveSheet()->setCellValue('I'.($i+2), $status_arr[$data['status']]);
                    $spreadsheet->getActiveSheet()->setCellValue('J'.($i+2), $data['updated_at']);
                }

                $writer = IOFactory::createWriter($spreadsheet,'Xls');
                //设置filename
                $filename = '提现列表'.'-'.date('YmdHis').'.xls';

                $path = BASE_PATH . '/runtime/';
                //保存
                $writer->save($path . $filename);

                $this->container->get(CsvDAO::class)->create([
                    'filename' => $filename
                ]);
            });

        }catch (\Throwable $throwable){
            $this->error($throwable->getMessage());
        }
        $this->success();
    }

    /**
     */
    public function bankRecharge()
    {
        try {
            $spreadsheet = new Spreadsheet();
            //设置表格

            $spreadsheet->setActiveSheetIndex(0);
            //设置表头
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1','用户ID')
                ->setCellValue('B1','国家')
                ->setCellValue('C1','汇款人姓名')
                ->setCellValue('D1','汇款人银行')
                ->setCellValue('E1','汇款人银行名')
                ->setCellValue('F1','充值金额')
                ->setCellValue('G1','汇率')
                ->setCellValue('H1','汇款金额')
                ->setCellValue('I1','收款银行名')
                ->setCellValue('J1','收款银行账号')
                ->setCellValue('K1','收款银行开户行')
                ->setCellValue('L1','状态')
                ->setCellValue('M1','创建时间');
            //设置表头居中
            $spreadsheet->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('I1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('J1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('K1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('L1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('M1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(40);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(30);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(30);


            $status_arr = [
                '待审核',
                '已通过',
                '未通过'
            ];

            go(function () use ($spreadsheet, $status_arr) {
                //查询数据
                $rows = $this->container->get(UserBankRechargeDAO::class)->get([])->toArray();
                //遍历数据
                foreach ($rows as $i => $data)
                {
                    $spreadsheet->getActiveSheet()->setCellValue('A'.($i+2), $data['user_id']);
                    $spreadsheet->getActiveSheet()->setCellValue('B'.($i+2), $data['country']['name_text'] ?? '');
                    $spreadsheet->getActiveSheet()->setCellValue('C'.($i+2), $data['name']);
                    $spreadsheet->getActiveSheet()->setCellValue('D'.($i+2), $data['bank']);
                    $spreadsheet->getActiveSheet()->setCellValue('E'.($i+2), $data['bank_name']);
                    $spreadsheet->getActiveSheet()->setCellValue('F'.($i+2), $data['amount']);
                    $spreadsheet->getActiveSheet()->setCellValue('G'.($i+2), 0);
                    $spreadsheet->getActiveSheet()->setCellValue('H'.($i+2), $data['remittance']);
                    $spreadsheet->getActiveSheet()->setCellValue('I'.($i+2), $data['receive_bank_name']);
                    $spreadsheet->getActiveSheet()->setCellValue('J'.($i+2), $data['receive_bank_account']);
                    $spreadsheet->getActiveSheet()->setCellValue('K'.($i+2), $data['receive_bank_address']);
                    $spreadsheet->getActiveSheet()->setCellValue('L'.($i+2), $status_arr[$data['status']]);
                    $spreadsheet->getActiveSheet()->setCellValue('M'.($i+2), $data['updated_at']);
                }

                $writer = IOFactory::createWriter($spreadsheet,'Xls');
                //设置filename
                $filename = '银行卡充值列表'.'-'.date('YmdHis').'.xls';
                $path = BASE_PATH . '/runtime/';
                //保存
                $writer->save($path . $filename);

                $this->container->get(CsvDAO::class)->create([
                    'filename' => $filename
                ]);
            });

        }catch (\Throwable $throwable){
            $this->error($throwable->getMessage());
        }
        $this->success();
    }

    public function onlineRecharge()
    {
        try {
            $spreadsheet = new Spreadsheet();
            //设置表格

            $spreadsheet->setActiveSheetIndex(0);
            //设置表头
            $spreadsheet->setActiveSheetIndex(0)
                ->setCellValue('A1','用户ID')
                ->setCellValue('B1','国家')
                ->setCellValue('C1','充值订单号')
                ->setCellValue('D1','充值金额')
                ->setCellValue('E1','汇率')
                ->setCellValue('F1','支付渠道')
                ->setCellValue('G1','支付状态')
                ->setCellValue('H1','创建时间');
            //设置表头居中
            $spreadsheet->setActiveSheetIndex(0)->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('B1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('C1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('D1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('E1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('F1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('G1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $spreadsheet->setActiveSheetIndex(0)->getStyle('H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(40);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);


            $status_arr = [
                '未支付',
                '已支付'
            ];

            go(function () use ($spreadsheet, $status_arr) {
                //查询数据
                $rows = $this->container->get(UserOnlineRechargeDAO::class)->get([])->toArray();
                //遍历数据
                foreach ($rows as $i => $data)
                {
                    $spreadsheet->getActiveSheet()->setCellValue('A'.($i+2), $data['user_id']);
                    $spreadsheet->getActiveSheet()->setCellValue('B'.($i+2), $data['country']['name_text'] ?? '');
                    $spreadsheet->getActiveSheet()->setCellValue('C'.($i+2), $data['payment']['pay_no'] ?? '');
                    $spreadsheet->getActiveSheet()->setCellValue('D'.($i+2), $data['amount']);
                    $spreadsheet->getActiveSheet()->setCellValue('E'.($i+2), 0);
                    $spreadsheet->getActiveSheet()->setCellValue('F'.($i+2), $data['channel']);
                    $spreadsheet->getActiveSheet()->setCellValue('G'.($i+2), $status_arr[$data['status']]);
                    $spreadsheet->getActiveSheet()->setCellValue('H'.($i+2), $data['updated_at']);
                }

                $writer = IOFactory::createWriter($spreadsheet,'Xls');
                //设置filename
                $filename = '在线充值列表'.'-'.date('YmdHis').'.xls';
                $path = BASE_PATH . '/runtime/';
                //保存
                $writer->save($path . $filename);

                $this->container->get(CsvDAO::class)->create([
                    'filename' => $filename
                ]);
            });

        }catch (\Throwable $throwable){
            $this->error($throwable->getMessage());
        }
        $this->success();
    }

    /**
     * @GetMapping(path="download")
     * @param $file_name
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function download()
    {
        $file_name = $this->request->input('filename', '');

        if (!$file_name) {
            $this->error('logic.SERVER_ERROR');
        }
        $response = new Response();
        $content_type = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
        return $response->withHeader('content-description', 'File Transfer')
            ->withHeader('content-type', $content_type)
            ->withHeader('content-disposition', 'attachment; filename='. $file_name)
            ->withHeader('Cache-Control', 'max-age=0')
            ->withHeader('pragma', 'public')
            ->withBody(new SwooleStream(file_get_contents(BASE_PATH . '/runtime/' .$file_name)));
    }

    public function get()
    {
        $perPage = (int)$this->request->input('perPage', 10);
        $result = Csv::query()->orderByDesc('id')->paginate($perPage);

        $this->success($result);
    }
}