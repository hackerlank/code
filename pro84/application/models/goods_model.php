<?php
class Goods_model extends CI_Model
{
    private $goodsInfoTable = 'goods_info';
    private $goodsAttrTable = 'goods_attr';
    private $goodsAttrInfoTable = "goods_attr_info";
    private $goodsImgTable = 'goods_img';
    
    public function __construct()
    {
        $this->load->database();
    }
    public function GetGoodsAttrType($type)
    {
        $query = $this->db->query("SELECT value FROM $this->goodsAttrTable WHERE type='$type'");
        return $query->row()->value;
    }
    public function SaveGoods($data)
    {
        $k_str = '';
        $v_str = '';
        foreach($data as $k=>$v) {
            $k_str .= "$k,";
            $v_str .= "'$v',";
        }
        $k_str = rtrim($k_str, ',');
        $v_str = rtrim($v_str, ',');
        $sql  = "INSERT INTO $this->goodsInfoTable ($k_str) VALUES ($v_str)";
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    public function UpdateGoods($data, $id)
    {
        $str = '';
        foreach($data as $k=>$v) {
            $str .= "$k='$v',";
        }
        $str = rtrim($str, ',');
        $sql = "UPDATE $this->goodsInfoTable set $str WHERE id=$id";
        $query = $this->db->query($sql);
        return $this->db->affected_rows();
    }
    public function GetGoodsInfo($id)
    {
        $query = $this->db->query("SELECT * FROM $this->goodsInfoTable WHERE id=$id");
        return $query->row_array(0);
    }
    public function GetGoodsLists()
    {
        $goodsLists = array();
        $query = $this->db->query("SELECT * FROM $this->goodsInfoTable");

        if ($query->num_rows() > 0)
            foreach ($query->result_array() as $row)
                $goodsLists[] = $row;

        return $goodsLists;
    }
    public function SaveGoodsImg($gid, $path)
    {
        $sql = "INSERT INTO $this->goodsImgTable (gid, path) VALUES($gid, '$path')";
        $this->db->query($sql);
        return $this->db->insert_id();
    }
    public function AddAttr($data)
    {
        $sql  = "INSERT INTO $this->goodsAttrTable (name, pid) VALUES ('{$data['name']}', {$data['pid']})";
        if ($this->db->query($sql))
            return $this->db->insert_id();
        else
            return false;
    }
    public function UpdateAttr($id, $name)
    {
        $sql = "UPDATE $this->goodsAttrTable SET name='$name' WHERE id=$id";
        return $this->db->query($sql);
    }
    public function DelAttr($id)
    {
        $sql = "DELETE FROM $this->goodsAttrTable WHERE id=$id";
        return $this->db->query($sql);
    }
    public function GetAttr($id)
    {
        $sql = "SELECT * FROM $this->goodsAttrTable where id=$id";
        $query = $this->db->query($sql);
        return $query->row_array();
    }
    public function GetAttrList()
    {
        $attrArray = array();
        $sql = "SELECT * FROM $this->goodsAttrTable WHERE pid=0";
        $query  = $this->db->query($sql);

        if ($query->num_rows() > 0) 
            foreach ($query->result() as $row) 
                $attrArray[] = array('id'=>$row->id, 'name'=>$row->name, 'level'=>0, 'son'=>$this->getAttrByPid($row->id,0));
        return $attrArray;
    }
    public function GetAttrByPid($pid, $level=0)
    {
        $data = array();
        $sql = "SELECT * FROM $this->goodsAttrTable WHERE pid=$pid";
        $query = $this->db->query($sql);

        if ($query->num_rows() > 0){
            $level++;
            foreach ($query->result() as $row) {
                $data [] = array('id'=>$row->id, 'name'=>$row->name, 'level'=>$level, 'son'=>$this->getAttrByPid($row->id, $level));
            }
        }

        return $data;
    }
    public function AddAttrInfo($aid, $attrinfo, $atype)
    {
        $sql = "INSERT INTO $this->goodsAttrInfoTable (aid, atype, val) VALUES ($aid, '$atype', '$attrinfo')";
        if ($this->db->query($sql))
            return $this->db->insert_id();
        else
            return false;
    }
    public function UpdateAttrInfo($id, $attrinfo)
    {
        $sql = "UPDATE $this->goodsAttrInfoTable SET val='$attrinfo' WHERE id=$id";
        return $this->db->query($sql);
    }
    public function DelAttrInfo($id)
    {
        $sql = "DELETE FROM $this->goodsAttrInfoTable WHERE id=$id";
        return $this->db->query($sql);
    }
    public function GetAttrInfo($aid, $atype)
    {
        $sql = "SELECT * FROM $this->goodsAttrInfoTable WHERE aid=$aid and atype='$atype'";
        $data = array();
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0)
            foreach ($query->result() as $row)
                $data[] = array('id'=>$row->id, 'val'=>$row->val, 'atype'=>$row->atype);
        return $data;
    }
}
