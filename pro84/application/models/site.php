<?php
class Site extends CI_Model
{
	public $table = 'site_info';
	
	public function saveSiteInfo($val, $id)
	{
		return $this->db->update($this->table, array('val'=>$val), array('id'=>$id));
	}
	public function getSiteInfo()
	{
		$query = $this->db->get($this->table);
		
		$info = array();
		if ($query->num_rows())
			foreach ($query->result_array() as $row)
				$info[] = $row;
		return $info;
	}
}