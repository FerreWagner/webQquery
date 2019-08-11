<?php
namespace app\index\controller;

use function GuzzleHttp\Psr7\str;
use QL\QueryList;
use think\Exception;

class Index
{

    public $title = [];
    public $time = [];
    public $since_id = '';
    public function index()
    {
        return 1;
    }

    public function readSpirit()
    {
        //采集某页面所有的图片
//        $url = 'https://weibo.com/p/1008088e29cdcebbc389732e994b36db14d085/super_index';
//        $url = 'https://m.weibo.cn/p/index?containerid=1008088e29cdcebbc389732e994b36db14d085&extparam=%E5%89%A7%E7%89%88%E9%95%87%E9%AD%82&luicode=10000011&lfid=100103type%3D1%26q%3D%E5%89%A7%E7%89%88%E9%95%87%E9%AD%82';
        $url = 'https://m.weibo.cn/p/index?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085_-_main';
//        $data = QueryList::get($url)->find('WB_text')->texts();

        // 定义采集规则
        $rules = [
            // 采集文章标题
            'title' => ['weibo-text','text'],
        ];
        $rt = QueryList::get($url)->rules($rules)->query()->getData();

        print_r($rt->all());

    }

    public function test()
    {
        $url = 'https://weibo.com/p/1008088e29cdcebbc389732e994b36db14d085/super_index?current_page=3&since_id=4404151645233368&page=2#Pl_Core_MixedFeed__291';
        $ql = QueryList::get($url,'',[
            'headers' => [
                //填写从浏览器获取到的cookie
                'Cookie' => 'SINAGLOBAL=362393147655.33325.1547053969982;WEIBOCN_FROM=1110006030;WBtopGlobal_register_version=307744aa77dd5677;M_WEIBOCN_PARAMS=luicode%3D10000011%26lfid%3D1008088e29cdcebbc389732e994b36db14d085_-_main%26oid%3D4248023829886840%26fid%3D1008088e29cdcebbc389732e994b36db14d085_-_soul%26uicode%3D10000011;MLOGIN=1;'
            ]
        ]);
//echo $ql->getHtml();
        echo $ql->find('title')->text();

    }

    public function test1()
    {
        $url = 'https://weibo.com/p/1008088e29cdcebbc389732e994b36db14d085/super_index?current_page=3&since_id=4404155659930883&page=2#Pl_Core_MixedFeed__2910';
        $url = 'https://weibo.com/uktimes?refer_flag=0000015010_&from=feed&loc=avatar';
        $url = 'https://m.weibo.cn/p/index?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085_-_main';
        // 用post登录
        $ql = QueryList::post('https://weibo.com/login',[
            'username' => '18408229270',
            'password' => 'WZW19953000'
        ])->get($url)->getHtml();
//采集需要登录才能访问的页面
//        $ql->get('http://xxx.com/admin/page');
print_r($ql);die;
    }

    public function test2()
    {
        $urlParams = ['param1' => 'testvalue','params2' => 'somevalue'];
        $opts = [
            // 设置http代理
            'proxy' => '122.241.72.191:808',
            //设置超时时间，单位：秒
            'timeout' => 30,
            // 伪造http头
            'headers' => [
                'Referer' => '122.241.72.191:808',
                'User-Agent' => 'testing/1.0',
                'Accept'     => 'application/json',
                'X-Foo'      => ['Bar', 'Baz'],
                'Cookie'    => 'abc=111;xxx=222'
            ]
        ];
        $asd = QueryList::get('https://weibo.com/p/1008088e29cdcebbc389732e994b36db14d085/super_index?current_page=6&since_id=4404170456987785&page=7#Pl_Core_MixedFeed__291',$urlParams,$opts);
        var_dump($asd);
//        m.weibo.cn/api/container/getIndex?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085&since_id=4404193303348538
//        m.weibo.cn/api/container/getIndex?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085&since_id=4404193303348538
//        m.weibo.cn/api/container/getIndex?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085&since_id=4404192405830627
//        m.weibo.cn/api/container/getIndex?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085&since_id=4404191231142646
//        m.weibo.cn/api/container/getIndex?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085&since_id=4404189255627348

    }

    public function test3()
    {
        $url = 'https://m.weibo.cn/api/container/getIndex?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085&since_id=4404186898928104';
//        $curl=curl_init();
//        curl_setopt_array($curl,array(
//            CURLOPT_URL=>$url,
//            CURLOPT_RETURNTRANSFER=>true,
//            CURLOPT_ENCODING=>"",
//            CURLOPT_MAXREDIRS=>10,
//            CURLOPT_TIMEOUT=>30,
//            CURLOPT_HTTP_VERSION=>CURL_HTTP_VERSION_1_1,
//            CURLOPT_CUSTOMREQUEST=>"GET"
//        ));
//        $response=curl_exec($curl);
//        $err=curl_error($curl);
//        curl_close($curl);
//        if($err){
////错误处理
////echo"cURLError#:".$err;
//        }else{
//            $tmp = json_decode($response, true);
//        }
        echo strtotime('Sun Aug 11 00:33:34 +0800 2019');die;
        $tmp  = json_decode(file_get_contents($url), true);
        $filter = $tmp['data'];
        $time_data = $filter['cards'];
        foreach ($time_data as $k => $v){
            foreach ($v['card_group'] as $_k => $_v){
                if (strstr($_v['created_at'], '2018')){
                    if (strtotime($_v['created_at']) > strtotime('2018-06') && strtotime($_v['created_at']) < strtotime('2018-09')){  //18年
                        $title[] = $_v['mblog']['text'];
                        $time[]  = $_v[''];
                    }
                }
//                if (!strstr($_v['created_at'], '2018') && strstr($_v['created_at'], 06))  //TODO 2019年
//                var_dump($_v);die;
            }
        }
        $json = json_encode($tmp, true);
        die($json);
//        var_dump(json_encode($response));
        $a = floatval($tmp['data']['pageInfo']['since_id']);
        echo $a;die;
    }

    public function test4()
    {
        $title = [];
        $time = [];
        $url       = 'https://m.weibo.cn/api/container/getIndex?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085&since_id=4404186898928104';
        $this->handleTopicWeiBo('4404229705806820');




//        die;
//        $json = json_encode($tmp, true);
//        var_dump($title);die;
//        die($json);
//        $a = floatval($tmp['data']['pageInfo']['since_id']);
//        echo $a;die;
    }

    public function handleTopicWeiBo($since_id)
    {
//        $txt = '';
//        $asd = fopen('asd.txt', 'w');
//        fwrite($asd, $txt);
//        $txt = 'asd';
//        fwrite($asd, $txt);
//        $txt = 'asd';
//        fwrite($asd, $txt);
//        $txt = 'asd';
//        fwrite($asd, $txt);
//        fclose($asd);die;

        set_time_limit(0);
        $this->since_id = $since_id;
        $url = "https://m.weibo.cn/api/container/getIndex?containerid=1008088e29cdcebbc389732e994b36db14d085_-_feed&luicode=10000011&lfid=1008088e29cdcebbc389732e994b36db14d085&since_id=$since_id";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; rv:21.0) Gecko/20100101 Firefox/21.0');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 0);
        curl_setopt($curl, CURLOPT_HTTPGET, 0);
//curl_setopt($curl, CURLOPT_COOKIEFILE, $this->_cookie); // 如果是需要登陆才能采集的话,需要加上你登陆后得到的cookie文件
        curl_setopt($curl, CURLOPT_TIMEOUT, 0); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 0); // 在发起连接前等待的时间，如果设置为0，则无限等待。
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT_MS, 0); // 尝试连接等待的时间，以毫秒为单位。如果设置为0，则无限等待。
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmp_get = curl_exec($curl);

        try{
            if (empty($tmp_get)){
                $tmp_get   = file_get_contents($url);
            }
        }catch (Exception $e){
            $this->handleTopicWeiBo($this->since_id);
        }
        $tmp       = json_decode($tmp_get, true);
        $filter    = $tmp['data'];
        $time_data = $filter['cards'];
        foreach ($time_data as $k => $v){
            $txt = '';
            foreach ($v['card_group'] as $_k => $_v){
                if (strtotime($_v['mblog']['latest_update']) > strtotime('2019-06') && strtotime($_v['mblog']['latest_update']) < strtotime('2019-07')){
                    if (empty($myfile)){
                        $myfile = fopen('ferre-'.date('Y-m-d H-i-s',strtotime($_v['mblog']['latest_update'])).'.txt', "w");
                    }
                    $txt .= 'Title: '.strip_tags($_v['mblog']['text']);
                    $txt .= 'Time: '.$_v['mblog']['created_at'];
                    fwrite($myfile, $txt);
                }
                if (strstr($_v['mblog']['created_at'], '2018')){
                    if (strtotime($_v['mblog']['created_at']) > strtotime('2018-06') && strtotime($_v['mblog']['created_at']) < strtotime('2018-09')){  //18年
                        if (empty($myfile)){
                            $myfile = fopen('ferre-'.date('Y-m-d H-i-s',strtotime($_v['mblog']['latest_update'])).'.txt', "w");
                        }
                        $txt .= 'Title: '.strip_tags($_v['mblog']['text']);
                        $txt .= 'Time: '.$_v['mblog']['created_at'];
                        fwrite($myfile, $txt);
                    }
                }
                if (strtotime($_v['mblog']['latest_update']) < strtotime('2018-05-31')){
                    echo '超过时间节点';die;
                }
                if (!empty($myfile)){
                    @fclose($myfile);
                }
            }


        }

        $since_id = json_encode($filter['pageInfo']['since_id']);
        $this->handleTopicWeiBo($since_id);
    }
}
