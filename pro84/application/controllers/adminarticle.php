<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-3-17
 */
class adminarticle extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Articles");
        $this->load->library('session');
    }
	public function get_aticle_name($type)
	{
		switch($type) {
			case 1:
				return "新闻";
		}
	}
    public function typelists()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $type = intval($this->uri->segment(3,0));
        $data['type'] = $type;
        
        $data['typelist'] = $this->Articles->GetTypelists("WHERE type=$type and pid=0");
        
        $data['view'] = "article_typelist";
        
        $data['title'] = $this->get_aticle_name($type);
		$data['type_option'] = $this->create_type_option($type);
        $this->load->view("/admin/article_typelist.php",$data);
    }
    public function addtype()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $pid = intval($this->input->post('pid',0));
        $typename = trim($this->input->post('typename',0));
        $type = intval($this->input->post('type',0));
        
        $res = $this->Articles->AddType($typename,$pid,$type);
        echo json_encode($res);
    }
    public function deltype()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $id = intval($this->input->post('id',0));
        $res = $this->Articles->DelType($id);
        echo json_encode($res);
    }
    public function typeinfo()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $type = intval($this->uri->segment(3,0));
        $id = intval($this->uri->segment(4,0));
        
        $data['typelist'] = $this->Articles->GetTypelists("WHERE type=$type and pid=0");
        
        $res  = $this->Articles->GetTypelists("WHERE id=$id");
        $data['info'] = $res[0];
        
        
        
        $data['title'] = $this->get_aticle_name($type);
        $this->load->view("/admin/article_typeinfo.php",$data);
    }
    public function updatetype()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $id = intval($this->input->post('id',0));
        $pid = intval($this->input->post('pid',0));
        $typename = trim($this->input->post('typename',''));
        $template = trim($this->input->post('template',''));
        
        $res = $this->Articles->UpdateType($id, $typename, $pid, $template);
        echo json_encode($res);
    }
    public function lists()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $pagesize = 10;
        $article_type = intval($this->uri->segment(3,0));
		$news_type = intval($this->uri->segment(4,0));
        $page = intval($this->uri->segment(5,1));
        $total_pages = $this->Articles->GetArticleTotal($news_type);
        if ($page > $total_pages || $page < 1) $page = 1;
        $data['list'] = $this->Articles->GetLists("WHERE status=0 and atype=$news_type", $page, $pagesize);
        $data['pagination'] = $this->createPagination("/adminarticle/lists/$article_type/$news_type", $total_pages, $pagesize, $page);
        
        $data['news_type'] = $news_type;
        $data['title'] = $this->get_aticle_name($article_type);
		$data['type_option'] = $this->create_type_option($article_type,$news_type);
        $this->load->view("/admin/article_list.php",$data);
    }
    public function createPagination($base_url, $total_rows, $per_page, $cur_page)
    {
        $this->load->library('custompagination');
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['cur_page'] = $cur_page;
        $config['uri_segment'] = 4;
        $config['num_links'] = 6;
        $this->custompagination->initialize($config);
        return $this->custompagination->create_links(); 
    }
    public function addart()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $article_type = intval($this->uri->segment(3,0));

        $id = intval($this->uri->segment(4,0));
        $data['type'] = $type;
        
        
        if ($id)
            $res = $this->Articles->GetInfo("id=$id");
        
        $data['info'] = $res[0];
        $data['title'] = $this->get_aticle_name($article_type);
		$data['type_option'] = $this->create_type_option($article_type,$res[0]['atype']);
        $this->load->view("/admin/article_art.php",$data);
    }
    public function saveart()
    {
         if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");

         $data['type'] = intval($this->input->post('type'));
         $data['atype'] = intval($this->input->post('atype'));
         $data['content'] = trim($this->input->post('content'));
         $data['imgurl'] = trim($this->input->post('newsimg'));
         $data['title'] = trim($this->input->post('title'));
         $data['time'] = trim($this->input->post('time',''));
         
         $data['description'] = trim($this->input->post('description',''));
         if (!$data['time']) $data['time'] = date('Y-m-d H:i:s');
         
         $id = intval($this->input->post('id',0));
         if ($id)
             $res = $this->Articles->UpdateArt($id,$data);
         else 
             $res = $this->Articles->AddArt($data);
             
         if ($res)
             echo "<script type='text/javascript'>window.location.href='/adminarticle/lists/{$data['type']}';</script>";
         
    }
    public function delart()
    {
        $id = intval($this->input->post('id'));
        if($id)
            $res = $this->Articles->DelArt($id);
        if($res)
            echo json_encode(array('code'=>0,'msg'=>'操作成功'));
        else
            echo json_encode(array('code'=>1,'msg'=>'操作失败'));
    }
    public function gettypeinfo()
    {
        $typeid = intval($this->input->post('typeid',0));
        if ($typeid) {
            $typeinfo = $this->Articles->GetTypelists("WHERE id=$typeid");
            die(json_encode(array('info'=>$typeinfo[0])));
        }
    }
	public function create_type_option($article_type, $news_type_id=0)
	{
		$type_lists = $this->Articles->GetTypelists("WHERE type=$article_type and pid=0");

		$option_str = '';
		if ($type_lists)
			foreach($type_lists as $row) {
				$stelected = '';
				if ($row['id'] == $news_type_id) $selected = "selected='selected'";
				$option_str .= "<option $selected value='{$row['id']}'>{$row['typename']}</option>";

				 if (!empty($row['son']))
                    $this->create_stype_option($row['son'],$option_str,$news_type_id);
			}

		return $option_str;
	}
	public function create_stype_option($lists, &$str, $type_id)
	{
		foreach ($lists as $k=>$v) {
			if(!empty($v)) {
				$prestr = str_repeat('&nbsp;&nbsp;',$v['listid']);
				$selected = '';
				if ($type_id==$v['id']) $selected = " selected='selected'";
					
				$str .= "<option $selected value='{$v['id']}'>$prestr|--{$v['typename']}</option>";
				if (!empty($v['son']))
					createstype($v['son'], $str,$type_id);
			}
		}
	}
}
