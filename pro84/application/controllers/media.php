<?php
/**
 *@author:shenjian@ztgame.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-4-4
 */
class Media extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product');
        $this->load->Model("Articles");
    }
    public function Info()
    {
        $id = intval($this->uri->segment(3,0));
        
        $res = $this->Articles->GetInfo("id={$id}");
        $data['info'] = $res[0];
        $type = $res[0]['type'];
        
        $res = $this->Articles->GetTypelists("WHERE pid=0 and type=$type");
        $data['typelist'] = $res;
        $this->load->view('zh/mediainfo.php',$data);
    }
    public function Lists()
    {
        $pagesize = 10;
        $res = $this->Articles->GetTypelists("WHERE pid=0 and type=1");
        $data['typelist'] = $res;
        
        $id = intval($this->uri->segment(3,0));
        $page = intval($this->uri->segment(4,0));
        
        if ($id) 
            $res = $this->Articles->GetLists("WHERE atype={$id} and type=1",$page,$pagesize);
        else 
            $res = $this->Articles->GetLists('WHERE type=1',$page,$pagesize);
            
        if ($res['total']>1){
            $this->load->library('common');
            $data['page'] = $this->common->page($res['total'],$page,'/media/lists/'.$id);
        }
        $data['lists'] = $res;
        
        if ($id) {
            foreach ($data['typelist'] as $v)
            if ($id == $v['id'])
                $data['typename'] = $v['typename'];
        }else 
            $data['typename'] = "新闻资讯";
            
        $data['baseurl'] = "/media/lists";    
        $this->load->view('zh/medialist.php',$data);
    }
    public function Cases()
    {
        $pagesize = 10;
        $res = $this->Articles->GetTypelists("WHERE pid=0 and type=2");
        $data['typelist'] = $res;
        
        $id = intval($this->uri->segment(3,0));
        $page = intval($this->uri->segment(4,0));
        
        if ($id) 
            $res = $this->Articles->GetLists("WHERE atype={$id} and type=2",$page,$pagesize);
        else 
            $res = $this->Articles->GetLists('WHERE type=2',$page,$pagesize);
            
        if ($res['total']>1){
            $this->load->library('common');
            $data['page'] = $this->common->page($res['total'],$page,'/media/lists/'.$id);
        }
        $data['lists'] = $res;
        
        if ($id) {
            foreach ($data['typelist'] as $v)
            if ($id == $v['id'])
                $data['typename'] = $v['typename'];
        }else 
            $data['typename'] = "解决方案";
        $data['baseurl'] = "/media/cases";    
        $this->load->view('zh/medialist.php',$data);
    }
}