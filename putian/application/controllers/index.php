<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@create:2012-3-27
 *@encoding:UTF-8 tab=4space
 */
class Index extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
    }
    public function Index()
    {
        //关于
        $this->load->model("Pages");
        $art = $this->Pages->GetInfo(" id=2 ");
        $data['aboutus'] = strip_tags($art[0]['content']);
        $data['aboutus'] = mb_substr($data['aboutus'],0,280);
        //新闻
        $this->load->model("Articles");
        $news = $this->Articles->GetLists('WHERE status=0 and type=1',0,7);
        foreach ($news['list'] as $k=>$v) {
            if (strlen($v['title']) > 18)
                $v['subtitle'] = mb_substr($v['title'], 0 ,18).'...';
            else
                $v['subtitle'] = $v['title'];
            $data['newslist'][$k] = $v;
        }
        //新闻图片列表
        $newsimglist = $this->Articles->GetLists(" WHERE status=0 and type=1 and imgurl!=''",0,5);
        $data['newsimglist'] = $newsimglist['list'];

        //产品
        $this->load->model('Product');
        $res = $this->Product->GetProList(' AND showindex=1 ',0,4);
        foreach ($res['list'] as $k=>$v) {
            $res['list'][$k]['prosubname'] = mb_substr($v['proname'], 0 ,10);
        }
        $data['productlist'] = $res['list'];
        //解决方案
        $res = $this->Articles->GetLists('WHERE status=0 and type=2',0,7);
        foreach ($res['list'] as $k=>$v) {
            $res['list'][$k]['subtitle'] = mb_substr($v['title'],0,18);
        }
        $data['prolist'] = $res['list'];
        //成功案例
        $res = $this->Pages->GetInfo('id=4');
        $data['example'] = $res[0];
        //公司荣誉图片
        $data['pimg'] = $this->Pages->GetInfo('id in (6,7)');
        $this->load->view('zh/index.php',$data);
    }
}
