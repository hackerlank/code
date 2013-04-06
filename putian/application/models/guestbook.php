<?php
class GuestBook extends CI_Model
{
    public $table = 'guestbook';
    public function  __construct()
    {
        $this->load->database();
    }
    public function Insert($data)
    {
        return $this->db->insert("{$this->table}", $data);
    }
    public function getLists($where = array(), $offset = 0, $pagesize = 0)
    {
        if($where) $this->db->where($where);

        if($offset && $pagesize) $this->db->limit($offset, $pagesize);

        $query = $this->db->get($this->table);

        $lists = array();
        foreach($query->result_array() as $row)
            $lists[] = $row;

        return $lists;

    }
}