<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-3-17
 */
class adminproduct extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model("Product");
    }
    public function typelists()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view('/admin/login.php');
        
        $data['list'] = $this->Product->GetTypeList("WHERE pid=0");
        $data['view'] = 'protypelists';
        $this->load->view('/admin/index.php',$data);
    }
    public function type()
    {
        $id = intval($this->uri->segment(3,0));
        if ($id) {
            $res = $this->Product->GetTypeList("WHERE pid=0");
            $data['ptype'] = $res;
            $res = $this->Product->GetTypeList("WHERE id=$id");
            $data['info'] = $res[0];
            $data['view'] = 'producttype';
            $this->load->view('/admin/index.php',$data);
        }
    }
    public function savetype()
    {
        $pid = intval($this->input->post('pid'));
        $typename = trim($this->input->post('typename'));
        $id = intval($this->input->post('id'));
        $type = $this->input->post('type','');
        
        //调整子类的分类
        if ('mtype' == $type){
            //子类调整为父类
            $this->Product->UpdateType($id,array('pid'=>$pid,'typename'=>$typename));
            echo json_encode(array('code'=>0,'msg'=>'修改成功'));
            exit;
        }
        
        //修改父类名
        if ($id){
            $this->Product->UpdateType($id,array('typename'=>$typename));
            echo json_encode(array('code'=>0,'msg'=>'修改成功'));
            exit;
        }
        
        //添加分类：有pid是子类，否则父类
        if ($pid)
            $this->Product->AddType($typename,$pid);
        else
            $this->Product->AddType($typename);
        echo json_encode(array('code'=>0,'msg'=>'添加成功'));
    }
    public function deltype()
    {
        $id = intval($this->input->post('id'));
        if (!$id)
            show_error("非法数据");
        $res = $this->Product->DelType($id); 
        echo json_encode($res);   
    }
    public function lists()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view('/admin/login.php');
        
        $res = $this->Product->GetProList('',0,100);
        $data['list'] = $res['list'];
        $data['view'] = 'prolists';
        $this->load->view('/admin/index.php',$data);
    }
    public function addproduct()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view('/admin/login.php');
        
        $data['ptype'] = $this->Product->GetTypeList("WHERE pid=0");
        $data['view'] = 'addproduct';
        
        $pid = intval($this->uri->segment(3,0));
        if ($pid) {
            $res = $this->Product->GetProInfo(" id={$pid}");
            $data['info'] = $res[0];
        }
        $this->load->view("/admin/index.php",$data);
    }
    public function saveproduct()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view('/admin/login.php');
        
        
        $data['type'] = intval($this->input->post('type'));
        $data['proname'] = $_POST['proname'];
        $data['proargv'] = $_POST['proargv'];
        $data['proinfo'] = $_POST['proinfo'];
        $data['prodown'] = $_POST['prodown'];
        $data['proarea'] = $_POST['proarea'];
        $data['prodesc'] = $_POST['prodesc'];
        $data['proimg'] = $_POST['proimg'];
        $data['date'] = date("Y-m-d");
        $data['time'] = date("Y-m-d H:i:s");
        $pid = intval($this->input->post('pid'));
        
        if ($pid) 
            $this->Product->UpdatePro($pid,$data);
        else 
            $this->Product->addProduct($data);
        echo "<script type='text/javascript'>window.location.href='/adminproduct/lists';</script>";
    }
    public function delpro()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view('/admin/login.php');
        
        $id = $this->uri->segment(3,0);
        if ($id) {
            if ($this->Product->DelPro($id))
                echo json_encode(array('err'=>0,'msg'=>'操作成功'));
            else 
                echo json_encode(array('err'=>1,'msg'=>'操作失败'));
        }
    }
    public function isshow()
    {   
        $id = intval($this->input->post('id'));
        $isshow = intval($this->input->post('isshow'));
        
        if($id)
            $res = $this->Product->UpdatePro($id,array('showindex'=>$isshow));
        if($res)
            echo json_encode(array('code'=>0,'msg'=>'操作成功'));
        else
            echo json_encode(array('code'=>1,'msg'=>'操作失败'));
    }
    public function ordernum()
    {
        $id = intval($this->input->post('id'));
        $ordernum = intval($this->input->post('ordernum'));
        
        if ($id)
            $res = $this->Product->UpdatePro($id,array('ordernum'=>$ordernum));
        if ($res)
            echo json_encode(array('code'=>0,'msg'=>'操作成功'));
        else
            echo json_encode(array('code'=>0,'msg'=>'操作失败'));
    }
}
