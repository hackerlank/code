<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@create:2012-4-12
 *@encoding:UTF-8 tab=4space
 */
class Adminpage extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model("Pages");
    }
    
    public function save()
    {
        $id = intval($this->input->post('id'));
        $content = $this->input->post('content');
        $imgurl = $this->input->post('imgurl','');
        $descs = $this->input->post('descs','');
        
        if (empty($content)) 
            echo json_encode(array('code'=>1,'msg'=>'数据不完整'));
        else {
            $this->Pages->UpdateInfo(array('content'=>$content, 'imgurl'=>$imgurl, 'descs'=>$descs),$id);
            echo json_encode(array('code'=>0,'msg'=>'更新成功'));
        }
    }
    //公司荣誉
    public function honour()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $info = $this->Pages->GetInfo(" id = 1");
        $data['id'] = 1;
        $data['content'] = $info[0]['content'];
        $data['view'] = "page_info";
        $data['title'] = '公司荣誉';
        $this->load->view('/admin/index.php',$data);
    }
    //公司简介
    public function about()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $info = $this->Pages->GetInfo(" id = 2");
        $data['id'] = 2;
        $data['content'] = $info[0]['content'];
        $data['view'] = "page_info";
        $data['title'] = '公司简介';
        $this->load->view('/admin/index.php',$data);
    }
    //联系我们
    public function link()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        
        $info = $this->Pages->GetInfo(" id = 3");
        $data['id'] = 3;
        $data['content'] = $info[0]['content'];
        $data['view'] = 'page_info';
        $data['title'] = '联系我们';
        $this->load->view('/admin/index.php',$data);
    }
    //成功案例
    public function example()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $info = $this->Pages->GetInfo(" id = 4");
        $data['id'] = 4;
        $data['content'] = $info[0]['content'];
        //$data['imgurl'] = $info[0]['imgurl'];
        $data['descs'] = $info[0]['descs'];
        $data['view'] = 'page_info';
        $data['title'] = '成功案例';
        $this->load->view('/admin/index.php',$data);
    }
    //企业文化
    public function slogen()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $info = $this->Pages->GetInfo(" id = 5");
        $data['id'] = 5;
        $data['content'] = $info[0]['content'];
        $data['view'] = 'page_info';
        $data['title'] = '企业文化';
        $this->load->view('/admin/index.php',$data);
    }
    //首页公司荣誉图片
    public function indeximg()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $info = $this->Pages->GetInfo(' id in (6,7)');
        $data['list'] = $info;
        $data['view'] = 'page_info';
        $data['page'] = 'indeximg';
        $data['title'] = '企业文化';
        $this->load->view('/admin/index.php',$data);
    }
    public function saveIndeximg()
    {
        $id = intval($this->input->post('id'));
        $imgurl = trim($this->input->post('imgurl'));
        
        $this->Pages->UpdateInfo(array('imgurl'=>$imgurl),$id);         
    }
}
