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
        $data['page'] = (int)$this->uri->segment(5,1);
        $this->load->view('zh/goods.php', $data);
    }
    public function Info()
    {
        $gid = $this->uri->segment(3,0);
        $data['info'] = $this->Goods_model->GetGoodsInfo($gid);
        
        $gtypeInfo = $this->Goods_model->GetType($data['info']['goods_type']);
        $data['gtype'] = $data['info']['goods_type'];
        $data['ptype'] = $gtypeInfo['pid'];
        $data['attrflag'] = $this->Goods_model->getGoodsAttrFlg();
        
        $attrs = array();
        $this->Goods_model->getGoodsAttrByAid($data['gtype'], $attrs);
        $this->Goods_model->getGoodsAttrByAid($data['ptype'], $attrs);
        $data['attrs'] = $attrs;
        
        $this->load->view('zh/goods_info.php', $data);
    }
    public function typeinfo()
    {
        $ptype = intval($this->uri->segment(3,0));
        $gtype = intval($this->uri->segment(4,0));
        
        $attrs = array();
        $this->Goods_model->getGoodsAttrByAid($ptype, $attrs);
        $this->Goods_model->getGoodsAttrByAid($gtype, $attrs);
        echo json_encode(array('list'=>$this->Goods_model->GetTypeList($ptype), 
        						'info'=>$this->Goods_model->GetType($ptype), 
        						'attrflag'=>$this->Goods_model->getGoodsAttrFlg(),
        						'attrs'=>$attrs));
        exit;
    }
    public function lists()
    {
        $gtype = intval($this->uri->segment(3,0));
        $offset = intval($this->uri->segment(4,0));
        $pagetotal = (int)$this->input->post('pagetotal');
        $page = (int)$this->input->post('page', 1);

        $offset += ($page-1)*$pagetotal;

        $row_nums = 16;

        $limitArr = json_decode($this->input->post('limit'), true);
        echo json_encode(array('list'=>$this->Goods_model->GetGoodsLists($gtype, $limitArr, $offset, $row_nums)));
        exit;
    }
    public function search()
    {
        $keywords = $this->input->post('keywords', true);
        $type = (int) $this->input->post('type', true);

        die(json_encode(array('list'=>$this->Goods_model->GetGoodsLists($type, array(), null, null,array('name'=>$keywords), array('author'=>$keywords)))));
    }
    public function goods_total()
    {
        $type = (int)$this->input->post('type', true);

        die(json_encode(array('total'=>$this->Goods_model->GetGoodsTotal($type))));
    }
    
}
