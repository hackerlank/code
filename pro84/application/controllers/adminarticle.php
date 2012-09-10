<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-3-17
 */
class adminarticle extends CI_Controller
{
    //type 1:新闻   2：解决方案
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Articles");
        $this->load->library('session');
    }
    public function typelists()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $type = intval($this->uri->segment(3,0));
        $data['type'] = $type;
        
        $data['typelist'] = $this->Articles->GetTypelists("WHERE type=$type and pid=0");
        
        $data['view'] = "article_typelist";
        
        if (1 == $type)
            $data['title'] = "新闻";
        elseif (2 == $type)
            $data['title'] = "解决方案";
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
        
        
        if (1 == $type)
            $data['title'] = "新闻";
        elseif (2 == $type)
            $data['title'] = "解决方案";
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
        $type = intval($this->uri->segment(3,0));
        $page = intval($this->uri->segment(4,0));
        $res = $this->Articles->GetLists("WHERE status=0 and type=$type", $page, $pagesize);
        
        $data['list'] = $res['list'];
        $data['type'] = $type;
        if (1 == $type)
            $data['title'] = "新闻";
        elseif (2 == $type)
            $data['title'] = "解决方案";
        $this->load->view("/admin/article_list.php",$data);
    }
    public function addart()
    {
        if (!$this->session->userdata('isadmin')) return $this->load->view("/admin/login.php");
        
        $type = intval($this->uri->segment(3,0));
        $id = intval($this->uri->segment(4,0));
        $data['type'] = $type;
        
        $data['typelist'] = $this->Articles->GetTypelists("WHERE type=$type and pid=0");
        if ($id)
            $res = $this->Articles->GetInfo("id=$id");
        
        $data['info'] = $res[0];
        if (1 == $type)
            $data['title'] = "新闻";
        elseif (2 == $type)
            $data['title'] = "解决方案";
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
         $data['date'] = trim($this->input->post('date',''));
         $data['show_start_date'] = trim($this->input->post('show_start_date',''));
         $data['show_end_date'] = trim($this->input->post('show_end_date',''));
         $data['show_area'] = trim($this->input->post('show_area',''));
         $data['description'] = trim($this->input->post('show_link',''));
         if (''==$data['date']) $data['date'] = date('Y-m-d');
         
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
}
