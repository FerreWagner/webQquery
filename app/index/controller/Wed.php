<?php
namespace app\index\controller;

use function GuzzleHttp\Psr7\str;
use QL\QueryList;
use think\Controller;
use think\Exception;

class Wed extends Controller
{

    public function index()
    {
        $host = 'https://hotel.hunliji.com';
        $url = 'https://hotel.hunliji.com/chengdu/gaoxin/hunyan';   //高新区
        $url = 'https://hotel.hunliji.com/chengdu/tianfu/hunyan';   //天府新区
        $ql = QueryList::get($url);
        $module = $ql->find('.hotel-item')->html();

        $rules = [
            'title' => ['.hotel-header-title>p>a','text'],  //标题
            'money' => ['.hotel-header-price','text'],  //价格
            'area' => ['.hotel-header-date','text'],    //基本介绍
            'cate' => ['.hotel-header-righttop','text'],    //婚宴厅数
            'local' => ['.hotel-header-position>span','text'],    //地址
            'order' => ['.hotel-header-people','text'], //订单人数
            'chart' => ['.hotel-header-te','text'], //特色
            'href' => ['.hotel-header-title>p>a','href'],   //子页
        ];
        $son_rule = [
            'title' => ['.hotel-hall-title','text'], //标题
            'other' => ['.hotel-hall-dl>dd','text'], //其他
        ];
        $menu_rule = [
            'title' => ['.ul1>.li1','text'], //标题
            'price' => ['.ul1>.li2','text'], //价格
            'menu_list' => ['.menu-list','text', 'span'], //菜单
        ];
        $rt = QueryList::get($url)->rules($rules)->queryData();
        $son_tmp = [];
        foreach ($rt as $k => $v){
            $rt[$k]['href'] = $host.$v['href'];
            $son_tmp[$k]['organ'] = QueryList::get($rt[$k]['href'])->rules($son_rule)->queryData();
            $son_tmp[$k]['href'] = $host.$v['href'];
            $son_tmp[$k]['menu'] = QueryList::get($rt[$k]['href'])->rules($menu_rule)->queryData();
            foreach ($son_tmp[$k]['menu'] as $_k => $_v){
                $son_tmp[$k]['menu'][$_k]['menu_list'] = str_replace(['<span>','</span>'], '-', $_v['menu_list']);
            }
        }
        $txt_data = [['分词', '次数', '权重']];   //初始化

        foreach ($son_tmp as $_k => $_v){
            $txt_data[] = [1, 2, 3];
        }
        var_dump($txt_data);die;
        //生成文档
        $this->dlfileftxt($txt_data, 'ferre_'.time(), 'xlsx');
        die(json_encode($son_tmp));
    }

    public function dlfileftxt($data = array(),$filename = "unknown", $cate) {
        header("Content-type:application/octet-stream");
        header("Accept-Ranges:bytes");
        header("Content-Disposition:attachment;filename=$filename.$cate");
        header("Expires:0");
        header("Cache-Control:must-revalidate,post-check=0,pre-check=0 ");
        header("Pragma:public");
        if (!empty($data)){
            foreach($data as $key=>$val){
                foreach ($val as $ck => $cv) {
                    $data[$key][$ck]=iconv("UTF-8", "GB2312", $cv);
                }
                $data[$key]=implode("\t\t", $data[$key]);
            }
            echo implode("\r\n",$data);
        }
        exit();
    }
}
