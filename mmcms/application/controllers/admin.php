<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * @author:xiaoshenge
     * @email:xiaoshengeer@gmail.com
     * @create:2012-09-03 17:09:16
     * @encoding:utf8 sw=4 ts=4
     **/
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Admin_user');
    }
    public function index()
    {
        if (!$this->session->userdata('isadmin')) { include(FCPATH.APPPATH.'views/admin/login.html');exit;}
        
        $this->config->load('admin_menu');
        $data['admin_menu'] = $this->config->item('admin_menu'); 
        print_r($data);
    }

    public function login()
    {
        $user_name = $this->input->post('user_name', '');
        $user_pwd = $this->input->post('user_pwd', '');
        
        if($user_name && $user_pwd) {

            $user = $this->Admin_user->get_user($user_name, $user_pwd);
            if ($user) {
                $this->session->set_userdata('isadmin', true);
                die(json_encode(array('err'=>false, 'msg'=>'登录成功')));
            } else {
                die(json_encode(array('err'=>true, 'msg'=>'用户名或者密码错误')));
            }

        } else {
            die(json_encode(array('err'=>true,'msg'=>'用户名或密码不能为空')));
        }
    }
    
}
