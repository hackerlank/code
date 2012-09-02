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
        return $this->db->update($this->goodsInfoTable, $data, array('id'=>$id));
    }
    public function GetGoodsInfo($id)
    {
        $query = $this->db->query("SELECT * FROM $this->goodsInfoTable WHERE id=$id");
        $info = $query->row_array(0);
 
        $craft = self::GetAttrInfo(array('id'=>$info['craft']));
        $theme = $this->GetAttrInfo(array('id'=>$info['theme']));
        $age = $this->GetAttrInfo(array('id'=>$info['age']));
        $author_type = $this->GetAttrInfo(array('id'=>$info['author_type']));
        
        $info['craft_name'] = $craft[0]['val'];
        $info['theme_name'] = $theme[0]['val'];
        $info['age_name'] = $age[0]['val'];
        $info['author_type_name'] = $author_type[0]['val'];
        return $info;
    }
    public function GetGoodsLists($gtype, $offset, $row_count)
    {
        $goodsLists = array();
        $query = $this->db->query("SELECT * FROM $this->goodsInfoTable where goods_type=$gtype limit $offset, $row_count");

        if ($query->num_rows() > 0)
            foreach ($query->result_array() as $row) {
                $craft = self::GetAttrInfo(array('id'=>$row['craft']));
                $theme = $this->GetAttrInfo(array('id'=>$row['theme']));
                $age = $this->GetAttrInfo(array('id'=>$row['age']));
                $author_type = $this->GetAttrInfo(array('id'=>$row['author_type']));
                $row['craft'] = isset($craft[0]['val'])?$craft[0]['val']:'';
                $row['theme'] = isset($theme[0]['val'])?$theme[0]['val']:'';
                $row['age'] = isset($age[0]['val'])?$age[0]['val']:'';
                $row['author_type'] = isset($author_type[0]['val'])?$author_type[0]['val']:'';
                $goodsLists[] = $row;
            }
        return $goodsLists;
    }
    public function GetGoodsTotal($gtype)
    {
        $this->db->where('goods_type', $gtype);
        return $this->db->count_all_results($this->goodsInfoTable);
    }
    public function SaveGoodsImg($gid, $img, $thumb_img)
    {
        //$sql = "INSERT INTO $this->goodsImgTable (gid, path) VALUES($gid, '$img')";
        $this->db->update($this->goodsInfoTable,array('img'=>$img, 'thumb_img'=>$thumb_img), array('id'=>$gid));
        //$this->db->query($sql);
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
    public function GetAttrList($pid=0)
    {
        $attrArray = array();
        $sql = "SELECT * FROM $this->goodsAttrTable WHERE pid=$pid";
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
    public function GetAttrInfo($arr)
    {
        $data = array();
        $query = $this->db->get_where($this->goodsAttrInfoTable, $arr);
        if ($query->num_rows() > 0)
            foreach ($query->result() as $row)
                $data[] = array('id'=>$row->id, 'aid'=>$row->aid, 'val'=>$row->val, 'atype'=>$row->atype);
        return $data;
    }
    public function GetGoodsImgList($gid)
    {
        $list = array();
        $query = $this->db->get_where($this->goodsImgTable, array('gid'=>$gid));
        if ($query->num_rows() > 0)
            foreach($query->result() as $row)
                $list[] = array('id'=>$row->id, 'path'=>$row->path);
        return $list;
    }
    public function DelGoods($id)
    {
        return $this->db->query("delete from {$this->goodsInfoTable} where id=$id");
    }
}
