<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@create:2012-3-27
 *@encoding:UTF-8 tab=4space
 */
class Goods extends CI_Controller
{
    public function __construct()
    { 
        parent::__construct();
        $this->load->model('Goods_model');
    }
    public function Index()
    {
        $data['ptype'] = $this->uri->segment(3,0);
        $data['gtype'] = $this->uri->segment(4,0);
        $this->load->view('zh/goods.php', $data);
    }
    public function Info()
    {
        $gid = $this->uri->segment(3,0);
        $data['info'] = $this->Goods_model->GetGoodsInfo($gid);
        $data['ptype'] = 5;
        $this->load->view('zh/goods_info.php', $data);
    }
    public function attrinfo()
    {
        $pid = intval($this->uri->segment(3,0));
        echo json_encode(array('list'=>$this->Goods_model->GetAttrList($pid), 'info'=>$this->Goods_model->GetAttr($pid)));
        exit;
    }
    public function lists()
    {
        $gtype = intval($this->uri->segment(3,0));
        echo json_encode(array('list'=>$this->Goods_model->GetGoodsLists($gtype, 0, 100)));
        exit;
    }
    
}
