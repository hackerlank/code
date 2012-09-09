<?php
/**
 *@author:shenjian@ztgame.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-4-12
 */
class Articles extends CI_Model
{
    private $typeTable = "articlestype";
    private $artTable = "articles";
    public function __construct()
    {
        $this->load->database();
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
    /**
     * 获取类型列表
     * @param unknown_type $where
     */
    public function GetTypelists($where)
    {
        $query = $this->db->query("SELECT * FROM {$this->typeTable} $where");
        $list = array();
        
        if (0 == $query->num_rows()) return $list;
        
        foreach ($query->result_array() as $row) {
            $row['listid'] = 0;
            $row['son'] = $this->GetSon($row['id'],0);
            $list[] = $row;
        }
        return $list;
    }
    /**
     * 添加类型
     * @param unknown_type $typename
     * @param unknown_type $pid
     * @param unknown_type $type
     */
    public function AddType($typename,$pid,$type)
    {
        $data = array('typename'=>$typename,'pid'=>$pid, 'type'=>$type);
        $this->db->insert("{$this->typeTable}",$data);
        return array('code'=>0,'msg'=>"添加'{$typename}'成功");
    }
    /**
     * 更新类型
     * @param unknown_type $id
     * @param unknown_type $typename
     * @param unknown_type $pid
     */
    public function UpdateType($id,$typename, $pid, $template)
    {
        $this->db->query("UPDATE {$this->typeTable} set typename={$this->db->escape($typename)},template='$template',pid=$pid where id={$id}");
        return array('code'=>0,'msg'=>'更新成功');
    }
    /**
     * 删除类型
     * @param unknown_type $id
     */
    public function DelType($id)
    {
        $query = $this->db->query("SELECT typename from {$this->typeTable} where pid={$id}");
        
        if ($query->num_rows()) return array('code'=>1,'msg'=>'请先删除子类');
        
        $this->db->query("DELETE FROM {$this->typeTable} WHERE id={$id}");
        return array('code'=>0,'msg'=>'删除成功');
    }
    public function GetLists($where='',$page,$pagesize)
    {
        //total
        $query = $this->db->query("SELECT COUNT(*) as total FROM {$this->artTable} $where");
        $total = ceil(($query->row()->total)/$pagesize);
        //list
        $typelistsinfo = $this->GetTypelists("");
        foreach ($typelistsinfo as $v){
            $typelist[$v['id']] = $v['typename'];
        }
        
        if ($pagesize) {
            $page = ($page>0 && $page <= $total)?$page:1;
            $limit = " limit ".($page-1)*$pagesize.",{$pagesize}";
        }
        else
            $limit = "";
        $query = $this->db->query("SELECT * from {$this->artTable} ".$where.' order by date desc '.$limit);
        $list = array();
        foreach ($query->result_array() as $row) {
            $row['typename'] = $typelist[$row['atype']];
            $list[] = $row;
        } 
        $query = $this->db->query("SELECT count(*) as total from {$this->artTable}");
        return array('total'=>$total,'list'=>$list,'page'=>$page);
    }
    public function GetInfo($where)
    {
        $query = $this->db->query("SELECT * FROM {$this->artTable} WHERE {$where}");
        $list = array();
        if (0 == $query->num_rows()) return $list;
        foreach ($query->result_array() as $row) {
            $typelistsinfo = $this->GetTypelists("WHERE pid=0");
            foreach ($typelistsinfo as $v)
                if ($v['id']==$row['atype'])
                    $row['typename'] = $v['typename'];
                    
            $list[] = $row;        
        }
        return $list;
    }
    public function AddArt($data)
    {
        //$data['time'] = date("Y-m-d H:i:s");
        //$data['date'] = date("Y-m-d");
        return $this->db->insert("{$this->artTable}",$data);
    }
    public function UpdateArt($id,$data)
    {
        //$data['time'] = date("Y-m-d H:i:s");
        //$data['date'] = date("Y-m-d");
        $str = '';
        foreach ($data as $k=>$v) {
            $str .= "$k={$this->db->escape($v)},";
        }
        $str = preg_replace("/,$/", '', $str);
        return $this->db->query("UPDATE {$this->artTable} set {$str} where id={$id}");
    }
    public function DelArt($id)
    {
        return $this->db->query("DELETE FROM {$this->artTable} WHERE id={$id}");
    }
}
