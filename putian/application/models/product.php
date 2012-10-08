<?php
/**
 *@author:shenjian@ztgame.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-3-17
 */
class Product extends CI_Model
{
    private $typeTable = "producttype";
    private $proTable = "product";
    public function __construct()
    {
        $this->load->database();
    }
    public function AddType($typename,$pid=0)
    {
        if ($pid){
            $this->db->insert("{$this->typeTable}",array('typename'=>$typename,'pid'=>$pid));
        }else
            $this->db->insert("{$this->typeTable}",array('typename'=>$typename,'pid'=>0));
    }
    /**
     * 无极分类：取出子类
     */
    public function GetSon($id,$listid)
    {
        $query = $this->db->query("SELECT * FROM {$this->typeTable} WHERE pid={$id}");
        $data = array();
        if (0 == $query->num_rows()) 
            return $list;
        else 
            $listid++;
        foreach ($query->result_array() as $row) {
            $row['listid'] = $listid;
            $row['son'] = $this->GetSon($row['id'],$listid);
            $list[] = $row;
        }
        return $list;
    }
    public function GetTypeList($where)
    {
        $query = $this->db->query("SELECT * FROM {$this->typeTable} $where");
        $list = array();
        if (0 == $query->num_rows()) return $list;
        foreach ($query->result_array() as $row) {
            $row['listid'] = 0;
            $row['son'] = $this->GetSon($row['id'],0);
            $list[] = $row;
        }
        //处理无极分类层次关系
        return $list;
    }
    public function UpdateType($id,$data)
    {
        $str='';
        foreach ($data as $k=>$v) {
            $str .= "$k={$this->db->escape($v)},";
        }
        $str = preg_replace("/,$/", '', $str);
        return $this->db->query("UPDATE {$this->typeTable} set {$str} where id={$id}");
    }
    public function DelType($id)
    {
        $query = $this->db->query("SELECT * FROM {$this->typeTable} WHERE id={$id}");
        if (0 == $query->num_rows()) return array('code'=>1,'msg'=>'你删除的分类不存在');
        $query = $this->db->query("SELECT COUNT(*) as total FROM {$this->typeTable} WHERE pid={$id}");
        if (0 != $query->row()->total)
            return array('code'=>2,'msg'=>'改分类存在子类，请先删除子类');
        
        $this->db->query("UPDATE {$this->proTable} SET status=1 WHERE type={$id}");
        $this->db->query("DELETE FROM {$this->typeTable} WHERE id={$id}");
        return array('code'=>0,'msg'=>'删除成功');
    }
    public function AddProduct($data)
    {
        $data['time'] = date("Y-m-d H:i:s");
        $data['date'] = date("Y-m-d");
        return $this->db->insert("{$this->proTable}",$data);
    }
    public function GetProList($where,$page,$pagesize)
    {
        if(is_int($where)){
            $stype = $this->GetSon($where,0);
            if ($stype){
                foreach($stype as $v)
                    $stype_str .= "{$v['id']},";
                $stype_str = preg_replace('/,$/','',$stype_str);
                $where = " WHERE status=0 AND type in ($stype_str,$where)";
            }else
                $where = " WHERE status=0 AND type=$where ";
        }else
            $where = " WHERE status=0 ".$where;
        //total
        $query = $this->db->query("SELECT COUNT(*) as total FROM {$this->proTable} $where order by ordernum ASC,id DESC");
        $total = ceil(($query->row()->total)/$pagesize);
        
        //lists
        if ($pagesize) {
            $page = ($page >0 && $page <= $total)?$page:1;
            $limit = 'limit '.($page-1)*$pagesize.", $pagesize";
        }
        else 
            $limit = '';
        $query = $this->db->query("SELECT * FROM {$this->proTable}".$where.' order by id desc '.$limit);
        $list = array();
        foreach ($query->result_array() as $row) {
            $query = $this->db->query("SELECT typename FROM {$this->typeTable} WHERE id={$row['type']}");
            $row['typename'] = $query->row()->typename;
            $list[]=$row;
        }
        return array('list'=>$list,'total'=>$total,'page'=>$page);
    }
    public function GetProInfo($where)
    {
        $query = $this->db->query("SELECT * FROM {$this->proTable} WHERE {$where}");
        $list = array();
        if (0 == $query->num_rows()) return $list;
        foreach ($query->result_array() as $row) {
            $query = $this->db->query("SELECT typename FROM {$this->typeTable} WHERE id={$row['type']}");
            $row['typename'] = $query->row()->typename;
            $list[] = $row;        
        }
        return $list;
    }
    public function DelPro($id) 
    {
        if ($this->db->query("DELETE FROM {$this->proTable} WHERE id={$id}"))
            return TRUE;
        else 
            return FALSE;
    }
    public function UpdatePro($id,$data)
    {
        $data['time'] = date("Y-m-d H:i:s");
        $data['date'] = date("Y-m-d");
        $str='';
        foreach ($data as $k=>$v) {
            $str .= "$k='".mysql_escape_string($v)."',";
        }
        $str = preg_replace("/,$/", '', $str);
        return $this->db->query("UPDATE {$this->proTable} set {$str} where id={$id}");
    }
}
