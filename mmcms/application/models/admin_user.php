<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * @author:xiaoshenge
     * @email:xiaoshengeer@gmail.com
     * @create:2012-09-03 21:09:14
     * @encoding:utf8 sw=4 ts=4
     **/
class Admin_user extends CI_model
{
    public $user_table = 'adminuser';

    public function __construct()
    {
        $this->load->database();
    }

    public function get_user($name, $pwd)
    {
        $query = $this->db->get_where($this->user_table, array('name'=>$name, 'pwd'=>md5($pwd)));

        $user_lists = array();

        if ($query->num_rows() > 0 ) 
            foreach($query->result_array() as $row)
                $user_lists[] = $row;

        return $user_lists;
    }
}
