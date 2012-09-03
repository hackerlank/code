<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@create:2012-3-15
 *@encoding:UTF-8 tab=4space
 */
class News extends CI_Model
{
    private $typeTable = "newstype";
    private $newsTable = "news";
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
    public function GetNewsTypelists($where)
    {
        $query = $this->db->query("SELECT id, typename, ordernum FROM {$this->typeTable} $where");
        $list = array();
        
        if (0 == $query->num_rows()) return $list;
        
        foreach ($query->result_array() as $row) {
            $row['listid'] = 0;
            $row['son'] = $this->GetSon($row['id'],0);
            $list[] = $row;
        }
        return $list;
    }
    public function AddNewsType($typename,$pid,$ordernum=0)
    {
        if ($ordernum){
            $query = $this->db->query("SELECT typename,ordernum FROM {$this->typeTable} where ordernum={$ordernum}");
            
            if (1 == $query->num_rows())
                return array('code'=>1,'msg'=>"您添加的{$typename}与'{$query->row()->typename}'顺序相同");
            else {
                $data = array('typename'=>$typename,'ordernum'=>$ordernum);
            }
        }else{
//            $query = $this->db->query("SELECT MAX(ordernum) as max FROM {$this->typeTable}");
//            $max = $query->row()->max+1;
            $data = array('typename'=>$typename,'pid'=>$pid);
        }
        $this->db->insert("{$this->typeTable}",$data);
        return array('code'=>0,'msg'=>"添加'{$typename}'成功");
    }
    public function UpdateNewsType($id,$typename, $pid, $ordernum)
    {
        $query = $this->db->query("SELECT typename from {$this->typeTable} where id={$id}");
        
        if (0 == $query->num_rows()) return array('code'=>1,'msg'=>'更新失败，请联系管理员');
        
        if ($ordernum){
            $query = $this->db->query("SELECT typename,ordernum FROM {$this->typeTable} where ordernum={$ordernum}");
            
            if (1 == $query->num_rows())
                return array('code'=>1,'msg'=>"您更新的与'{$query->row()->typename}'顺序相同");
            $this->db->query("UPDATE {$this->typeTable} set ordernum={$ordernum} where id={$id}");
        }else{
            $this->db->query("UPDATE {$this->typeTable} set typename={$this->db->escape($typename)},pid=$pid where id={$id}");
        }
        return array('code'=>0,'msg'=>'更新成功');
    }
    public function DelNewsType($id)
    {
        $query = $this->db->query("SELECT typename from {$this->typeTable} where id={$id}");
        
        if (0 == $query->num_rows()) return array('code'=>1,'msg'=>'删除失败，请联系管理员');
        
        $this->db->query("DELETE FROM {$this->typeTable} where id={$id}");
        return array('code'=>0,'msg'=>'删除成功');
    }
    public function GetNewsLists($where='',$page,$pagesize)
    {
        //total
        $query = $this->db->query("SELECT COUNT(*) as total FROM {$this->newsTable} $where");
        $total = ceil(($query->row()->total)/$pagesize);
        //list
        $typelistsinfo = $this->GetNewsTypelists("WHERE pid=0");
        foreach ($typelistsinfo as $v){
            $typelist[$v['id']] = $v['typename'];
        }
        
        if ($pagesize) {
            $page = ($page>0 && $page <= $total)?$page:1;
            $limit = " limit ".($page-1)*$pagesize.",{$pagesize}";
        }
        else
            $limit = "";
        $query = $this->db->query("SELECT id,type1,title,content,date,imgurl from {$this->newsTable} ".$where.$limit);
        $list = array();
        foreach ($query->result_array() as $row) {
            $row['typename'] = $typelist[$row['type1']];
            $list[] = $row;
        } 
        $query = $this->db->query("SELECT count(*) as total from {$this->newsTable}");
        return array('total'=>$total,'list'=>$list,'page'=>$page);
    }
    public function GetNewsInfo($where)
    {
        $query = $this->db->query("SELECT * FROM {$this->newsTable} WHERE {$where}");
        $list = array();
        if (0 == $query->num_rows()) return $list;
        foreach ($query->result_array() as $row) {
            $typelistsinfo = $this->GetNewsTypelists("WHERE pid=0");
            foreach ($typelistsinfo as $v)
                if ($v['id']==$row['type1'])
                    $row['typename'] = $v['typename'];
                    
            $list[] = $row;        
        }
        return $list;
    }
    public function AddNews($data)
    {
        $data['time'] = date("Y-m-d H:i:s");
        $data['date'] = date("Y-m-d");
        return $this->db->insert("{$this->newsTable}",$data);
    }
    public function UpdateNews($id,$data)
    {
        $data['time'] = date("Y-m-d H:i:s");
        $data['date'] = date("Y-m-d");
        $str = '';
        foreach ($data as $k=>$v) {
            $str .= "$k={$this->db->escape($v)},";
        }
        $str = preg_replace("/,$/", '', $str);
        return $this->db->query("UPDATE {$this->newsTable} set {$str} where id={$id}");
    }
    public function DelNews($id)
    {
        return $this->db->query("DELETE FROM {$this->newsTable} WHERE id={$id}");
    }
}
