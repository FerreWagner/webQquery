<?php
namespace app\index\controller;

//use function GuzzleHttp\Psr7\str;
use QL\QueryList;
use think\Controller;
use think\Exception;

class Wed extends Controller
{

    //https://www.cnblogs.com/lovebing/p/11199294.html PHP7.2以上版本的问题
    public function index()
    {
        set_time_limit(0);
        $host = 'https://hotel.hunliji.com';
//        $url = 'https://hotel.hunliji.com/chengdu/wuhou/hunyan';   //武侯区
        $url = 'https://hotel.hunliji.com/chengdu/wuhou/hunyan/p/2';   //武侯区2
//        $url = 'https://hotel.hunliji.com/chengdu/gaoxin/hunyan';   //高新区

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
        foreach ($rt as $k => $v){
            $rt[$k]['href'] = $host.$v['href'];
            $rt[$k]['organ'] = QueryList::get($rt[$k]['href'])->rules($son_rule)->queryData();
            $rt[$k]['href'] = $host.$v['href'];
            $rt[$k]['menu'] = QueryList::get($rt[$k]['href'])->rules($menu_rule)->queryData();
            foreach ($rt[$k]['menu'] as $_k => $_v){
                $rt[$k]['menu'][$_k]['menu_list'] = str_replace(['<span>','</span>'], '-', $_v['menu_list']);
            }
        }
//        $txt_data = [['酒店', '价格区间', '概况', '酒宴概况', '地址', '预定量', '特色', '厅桌数', '菜单', '详情']];   //初始化

        $tmp_table = $tmp_menu = '';
//        die(json_encode($rt));
        foreach ($rt as $_k => $_v){
            if (!empty($_v['organ'])){
                foreach ($_v['organ'] as $ok => $ov){
                    $tmp_table .= $ov['title'].'-'.$ov['other'].'  ';
                }
            }else{
                $tmp_table = '';
            }
            if (!empty($_v['menu'])){
                foreach ($_v['menu'] as $mk => $mv){
                    if (empty($mv['price'])) $mv['price'] = '暂无';
                    if (empty($mv['menu_list'])) $mv['menu_list'] = '暂无';
                    $tmp_menu .= $mv['price'].' - '.$mv['menu_list'];
                }
            }else{
                $tmp_menu = '';
            }
            if (empty($_v['chart'])) $_v['chart'] = '';

            $txt_data[] = [$_v['title'], $_v['money'], $_v['area'],$_v['cate'],$_v['local'],$_v['order'],$_v['chart'],$tmp_table,$tmp_menu,$_v['href']];
            $tmp_table = '';
            $tmp_menu = '';
        }
        $excel = new \PHPExcel();
        //Excel表格式,这里简略写了8列
        $letter = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        //表头数组
        $tableheader = ['酒店', '价格区间', '概况', '酒宴概况', '地址', '预定量', '特色', '厅桌数', '菜单', '详情'];
        //填充表头信息
        for ($i = 0; $i < count($tableheader); $i++) {
            $excel->getActiveSheet()->setCellValue("$letter[$i]1", "$tableheader[$i]");
        }
        for ($i = 2; $i <= count($txt_data) + 1; $i++) {
            $j = 0;
            foreach ($txt_data[$i - 2] as $key => $value) {
                $excel->getActiveSheet()->setCellValue("$letter[$j]$i", "$value");
                $j++;
            }
        }
        $date = 'Wedding-武侯二'.time();
        $write = new \PHPExcel_Writer_Excel5($excel);
//die(json_encode($txt_data));
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="' . $date . '.xls"');
        header("Content-Transfer-Encoding:binary");

        ob_end_clean();
        $write->save('php://output');

//        die(json_encode($txt_data));
//        die(json_encode($rt));
        //生成文档
//        $this->dlfileftxt($txt_data, 'ferre_'.time(), 'xlsx');
//        echo 'success';
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
//                    $data[$key][$ck]=iconv("UTF-8", "gbk", $cv);
//                    $data[$key][$ck]=iconv("UTF-8", "gbk//IGNORE", $cv);
                    $data[$key][$ck]=iconv("UTF-8", "GB2312//IGNORE", $cv);
                }
                $data[$key]=implode("\t\t", $data[$key]);
            }
            echo implode("\r\n",$data);
        }
        exit();
    }
}
