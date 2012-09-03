<?php
/**
 *@author:shenjian@ztgame.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-4-12
 */
class Page extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
        $this->load->model("Articles");
    }
    public function Lists()
    {
        $pagesize = 10;
        $type = intval($this->uri->segment(3,0));
        $page = intval($this->uri->segment(4,0));
        $this->load->model("Pages");
        if ($type)
            $res = $this->Pages->GetList("WHERE type1={$type}",$page,$pagesize);
        else 
            $res = $this->Pages->GetList("WHERE type1!=0",$page,$pagesize);
        if ($res['total']>1) {
            $this->load->library('common');
            $data['page'] = $this->common->page($res['total'],$res['page'],'/article/lists/'.$type);
        }
        $data['list'] = $res['list'];
        $this->load->view('zh/articlelist.php',$data);
    }
    public function Info()
    {
        $id = intval($this->uri->segment(3,0));
        $this->load->model("Pages");
        $res = $this->Pages->GetInfo(" id={$id}");
        $data['info'] = $res[0];
        $this->load->view('zh/articleinfo.php',$data);
    }
    //公司荣誉
    public function honour()
    {
        $this->load->model("Pages");
        $res = $this->Pages->GetInfo(' id=1');
        $data['info'] = $res[0];
        $this->load->view('zh/articleinfo.php', $data);
    }
    //公司简介
    public function aboutus()
    {
        $this->load->model("Pages");
        $res = $this->Pages->GetInfo(' id=2');
        $data['info'] = $res[0];
        $this->load->view('zh/articleinfo.php', $data);
    }
    //联系我们
    public function link()
    {
        $this->load->model("Pages");
        $res = $this->Pages->GetInfo(' id=3');
        $data['info'] = $res[0];
        $this->load->view('zh/articleinfo.php', $data);
    }
    //成功案例
    public function example()
    {
        $this->load->model("Pages");
        $res = $this->Pages->GetInfo(' id=4');
        $data['info'] = $res[0];
        $this->load->view('zh/articleinfo.php', $data);
    }
    //企业文化
    public function slogen()
    {
        $this->load->model("Pages");
        $res = $this->Pages->GetInfo(' id=5');
        $data['info'] = $res[0];
        $this->load->view('zh/articleinfo.php', $data);
    }
    
}