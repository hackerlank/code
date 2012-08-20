<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@create:2012-3-5
 *@encoding:UTF-8 tab=4space
 */
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->database();
    }
    public function Index()
    {
        if (!$this->session->userdata("isadmin")){
            return $this->load->view("admin/login.php");
        }
        $data['view'] = 'sysinfo';
        $this->load->view("admin/index.php",$data);
    }
    public function Addinfo()
    {
        if (!$this->session->userdata("isadmin")){
            return $this->load->view("admin/login.php");
        }
        $this->load->view("admin/add.php");
    }
    /**
     * left menu
     */
    public function Left()
    {
        if (!$this->session->userdata("isadmin")) {
            return $this->load->view("admin/login.php");        
        }
        $this->load->view("admin/left.php");
    }
    /**
     * user login 
     */
    public function Login()
    {
        $name = trim($this->input->post("name"));
        $pwd = trim($this->input->post("pwd"));
        
        if(!$name || !$pwd) {
            echo json_encode(array('code'=>1,'msg'=>'用户名或者密码不能为空'));
            exit;
        }
        
        $query = $this->db->query("SELECT name FROM adminuser WHERE name='{$name}' and pwd='".md5($pwd)."'");
        $row = $query->row();
        if (!$row) {
            echo json_encode(array('code'=>2,'msg'=>'用户名或者密码错误'));
            exit;
        }
        
        $this->session->set_userdata('isadmin',1);
        $this->session->set_userdata('adminname',$name);
        echo json_encode(array('code'=>0,'msg'=>'登陆成功'));
        exit;
    }
    public function Sysinfo()
    {
        $data['view'] = 'sysinfo';
        $this->load->view("admin/index.php",$data);
    }
    public function Logout()
    {
        $this->session->unset_userdata('isadmin');
        $this->session->unset_userdata('adminname');
        return header("Location: /admin");
    }
    public function Changepwd()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view('admin/login.php');
        
        $oldPwd = $this->input->post("oldpwd", '');
        $newPwd = $this->input->post("newpwd", '');
        $rePwd  = $this->input->post("repwd", '');
        
        $data = array();
        if ($oldPwd && $newPwd && $rePwd) {
            $name = $this->session->userdata('adminname');
        
            $query = $this->db->query("SELECT name FROM adminuser WHERE name='{$name}' and pwd='".md5($oldPwd)."'");
            if (!$query->row()) {
                $data['errmsg'] = "原始密码不正确";
                return $this->load->view('admin/index.php',$data);
            }
            
            if ($newPwd != $rePwd) {
                $data['errmsg'] = "新密码输入的不一致";
                return $this->load->view('admin/index.php', $data);
            }
            
            $query = $this->db->query("UPDATE adminuser SET pwd='".md5($newPwd)."' WHERE name='$name'");
            $data['errmsg'] = "密码更改成功";
        }
        return $this->load->view('admin/admin_changepwd.php', $data);
    }
}
