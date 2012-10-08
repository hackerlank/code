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
    public function uploadimg()
    {
        if (!$this->session->userdata('isadmin')) {
            echo json_encode(array('code'=>'1','msg'=>'没有权限'));
            return;
        }
        $callback = $this->uri->segment(3, '');//img label
        
        $upload_dir = date("Ymd");
        if (!file_exists(FCPATH.'uploads/'.$upload_dir))
        	mkdir(FCPATH.'uploads/'.$upload_dir, 0777);
        
        $config['upload_path'] = 'uploads/'.$upload_dir;
        $config['allowed_types'] = 'gif|jpg|png|zip';
        $config['max_size'] = '40000';
        $config['max_width']  = '10240';
        $config['max_height']  = '7680';
        $config['file_name'] = time().'.'.pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);

        if (isset($_GET['CKEditorFuncNum']))
            $funcNum = $_GET['CKEditorFuncNum'];
        $this->load->library('upload', $config);
        if ( ! $this->upload->do_upload("upload")) {
            $error = array('error' => $this->upload->display_errors());
        } else {
            $data = array('upload_data' => $this->upload->data());
            if (isset($funcNum))
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '/{$config['upload_path']}/{$data['upload_data']['file_name']}', '成功');</script>";
            elseif (''!=$callback){
                echo "<script>window.parent.$callback('/{$config['upload_path']}/{$data['upload_data']['file_name']}');window.location.href='/admin/upload/{$callback}';</script>";
            }else{
                $str = "<li class='span6'><a class='close'>x</a><img src='/{$config['upload_path']}/{$data['file_data']['orig_name']}' /><input type='hidden' name='img[]' value='/{$config['upload_path']}/{$data['upload_data']['file_name']}' /></li>";
                echo '<script>window.parent.$("#imglists").append("'.$str.'");window.location.href="/admin/upload";</script>';
            }
        }
    }
    public function upload()
    {
        $data['callback'] = $this->uri->segment(3,'');
        $this->load->view('/admin/upload.php',$data);
    }
}
