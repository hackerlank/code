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
        $this->load->Model("Articles");
    }
    public function lists()
    {
        $ptype = intval($this->uri->segment(3));
        $stype = intval($this->uri->segment(4));
       
        $data['ptype'] = $ptype;
        $data['stype'] = $stype;

        $data['lists'] = $this->Articles->GetLists(" WHERE atype=$stype",0, 30);
        
        //if (!$data['lists']['list']) {header('Location: /');exit;}
        $typeinfo = $this->Articles->GetTypelists("WHERE id=$stype");
        $template = empty($typeinfo[0]['template']) ? "medialists1" : $typeinfo[0]['template'];
        $this->load->view("zh/$template.php",$data);
    }
    
    public function info()
    {
        $id = intval($this->uri->segment(3));
        
        if (!$id) {header('Location: /');exit;}
        
        $info = $this->Articles->GetInfo("id=$id");

        $stype = $info['0']['atype'];
        $ptype = $this->getPType($stype);

        $data['info'] = $info[0];
        $data['ptype'] = $ptype;
        $data['stype'] = $stype;
        $this->load->view('zh/media_info.php',$data);
    }
    
    public function getPType($id)
    {
        $type_lists = $this->Articles->GetTypeLists(" WHERE pid=0");

        foreach ($type_lists as $ptype) {
            if ($ptype['son']) {
                foreach($ptype['son'] as $stype) {
                    if ($stype['id'] == $id)
                        return $ptype['id'];
                }
            }
        }
    }
    public function getTypeLists()
    {
        $id = intval($this->uri->segment(3,0));
        $type_lists = $this->Articles->GetTypelists("WHERE id=$id");
        echo json_encode(array('list'=>$type_lists[0]));
    }
}
