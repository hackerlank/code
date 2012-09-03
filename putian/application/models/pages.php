<?php
/**
 *@author:shenjian@ztgame.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-4-12
 */
class Pages extends CI_Model
{
    private $table = "pages";
    public function __construct()
    {
        $this->load->database();
    }
    public function GetInfo($where)
    {
        $query = $this->db->query("SELECT * FROM {$this->table} where $where");
        $list = array();
        if (0 == $query->num_rows()) return $list;

        foreach ($query->result_array() as $row) {
            $list[] = $row;        
        }
        return $list;
    }
    public function UpdateInfo($data,$id)
    {
        $data['time'] = date("Y-m-d H:i:s");
        $data['date'] = date("Y-m-d");
        $str='';
        foreach ($data as $k=>$v) {
            $str .= "$k={$this->db->escape($v)},";
        }
        $str = preg_replace("/,$/", '', $str);
        return $this->db->query("UPDATE {$this->table} set {$str} where id={$id}");
    }
    public function GetList($where,$page,$pagesize)
    {
        //total
        $query = $this->db->query("SELECT COUNT(*) as total FROM {$this->table} $where");
        $total = ceil(($query->row()->total)/$pagesize);
        if ($pagesize) {
            $page = ($page>0 && $page <= $total)?$page:1;
            $limit  = "limit ".($page-1)*$pagesize.", $pagesize";
        } else 
            $limit  = '';
        //list
        $query = $this->db->query("SELECT * FROM {$this->table} $where $limit");
        $list = array();
        if (0 == $query->num_rows()) return $list;
        
        foreach ($query->result_array() as $row) {
            $list[] = $row;
        }
        return array('list'=>$list,'total'=>$total,'page'=>$page);
    }
    public function AddArt($data)
    {
        $data['time'] = date("Y-m-d H:i:s");
        $data['date'] = date("Y-m-d");
        $this->db->insert("{$this->table}",$data);
        return array('code'=>0,'msg'=>'添加案例成功');        
    }
}
