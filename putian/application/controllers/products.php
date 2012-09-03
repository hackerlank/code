<?php
/**
 *@author:shenjian@ztgame.com
 *@encoding=UTF-8 ts=4 sw=4
 *@create:2012-4-4
 */
class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Product");
        $this->load->model("Articles");
    }
    public function Lists()
    {
        $pagesize = 8;
        $type = intval($this->uri->segment(3,0));
        $page = intval($this->uri->segment(4,1));
        
        if ($type)
            $res = $this->Product->GetProList($type,$page,$pagesize);
        else 
            $res = $this->Product->GetProList("",$page,$pagesize);
        foreach ($res['list'] as $k=>$v){
            $res['list'][$k]['prodesc'] = mb_substr(strip_tags($v['prodesc']), 0, 50).'...';
        }
        //分页
        if ($res['total']>1){
            $this->load->library('common');
            $data['page'] = $this->common->page($res['total'],$page,'/products/lists/'.$type);
        }
        $data['plist'] = $res['list'];
        
        if ($type){
            $res = $this->Product->GetTypeList("WHERE id=$type");
            $data['category_title'] = $res[0]['typename'];
        }
        else 
            $data['category_title'] = '产品与服务';
        $data['typelist'] = $this->Product->GetTypeList("WHERE pid=0");
        $this->load->view("zh/productslist.php",$data);
    }
    public function Info()
    {
        $id = intval($this->uri->segment(3,0));
        $this->load->model("Product");
        $res = $this->Product->GetProInfo("id={$id}");
        $data['info'] = $res[0];
        
        //get type name
        $res = $this->Product->GetTypeList("WHERE id={$data['info']['type']}");
        $data['info']['typename'] = $res[0]['typename'];
        $data['typelist'] = $this->Product->GetTypeList("WHERE pid=0");
        $this->load->view("zh/productsinfo.php",$data);
    }
}
