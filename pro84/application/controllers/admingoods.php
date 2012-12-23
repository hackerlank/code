<?php
class Admingoods extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->model('Goods_model');
        $this->load->database();
    }
    public function Add()
    {
        if (!$this->session->userdata("isadmin")) return $this->load->view("admin/login.php");
        
        $data = array();
        $data['gid'] = intval($this->uri->segment(3,0));
        
        $id = $this->uri->segment(3,0);

        $data['info'] = array();
        if ($id) $data['info'] = $this->Goods_model->GetGoodsInfo($id);

        $data['attrOption'] = $this->createAttrSonOption($data['info']['goods_type']);
        $this->load->view('admin/goods_add.php', $data);
    }
    public function getGoodsAttr($type)
    {
        $value = $this->Goods_model->GetGoodsType($type);
        return explode(',',$value);
    }
    public function saveGoods()
    {
        if (!$this->session->userdata("isadmin")) return $this->load->view("admin/login.php");

        $data = array();
        $data = json_decode($this->input->post('goods'), true);
        $data['brief'] = $this->input->post('brief', '');

        if (!$data['time'])
        	$data['time'] = date("Y-m-d H:i:s");
        	
        $id = intval($this->input->post('id',0));
        if ($id) {
            $res = $this->Goods_model->UpdateGoods($data, $id);
            if ($res)
                echo json_encode(array('err'=>0, 'msg'=>'更新成功'));
            else
                echo json_encode(array('err'=>0, 'msg'=>'更新失败'));
        } else {
            $res = $this->Goods_model->SaveGoods($data);
            if ($res)
                echo json_encode(array('err'=>0, 'msg'=>'添加成功', 'gid'=>$res));
            else
                echo json_encode(array('err'=>1, 'msg'=>'添加失败'));
        }
    }
    public function addimg()
    {
        $data['gid'] = intval($this->uri->segment(3,0));
        $data['action'] = "/admingoods/saveimg";
        $data['callback'] = "addimg";
        $this->load->view("/admin/uploadimg.php",$data);
    }
    public function addalbumimg()
    {
        $data['gid'] = intval($this->uri->segment(3,0));
        $data['action'] = "/admingoods/savealbumimg";
        $data['callback'] = 'addblumimg';
        $this->load->view("/admin/uploadimg.php",$data);
    }
    public function saveimg()
    {
        $gid = intval($this->input->post('gid',0));
        if (!$gid) die("<scrpit>alert('非法数据');</script>");

        $upload_dir = date("Ymd");
        if (!file_exists(FCPATH.'uploads/'.$upload_dir))
        	mkdir(FCPATH.'uploads/'.$upload_dir, 0777);

        $config['upload_path'] = 'uploads/'.$upload_dir;

        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = "0";
        $config['max_width'] = "0";
        $config['max_height'] = "0";
        $config['file_name'] = time().'.'.pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
        $this->load->library('upload',$config);

        if($this->upload->do_upload('upload')) {
            $callback = $this->input->post('callback');
            $data = $this->upload->data();
            $img = '/'.$config['upload_path'].'/'.$data['file_name'];
            $thumb_img = '/'.$config['upload_path'].'/'.$this->createMiniImg($img);
            $this->Goods_model->SaveGoodsImg($gid, $img, $thumb_img);
            echo "<script type='text/javascript'>window.parent.$callback('$thumb_img');window.location.href='/admingoods/addimg/$gid';</script>'";
        } else{
            $msg = strip_tags($this->upload->display_errors());
            echo "<script type='text/javascript'>alert('$msg')</script>";
        }
    }
    public function savealbumimg()
    {
        $gid = intval($this->input->post('gid',0));
        if (!$gid) die("<scrpit>alert('非法数据');</script>");

        $upload_dir = date("Ymd");
        if (!file_exists(FCPATH.'uploads/'.$upload_dir))
            mkdir(FCPATH.'uploads/'.$upload_dir, 0777);

        $config['upload_path'] = 'uploads/'.$upload_dir;

        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = "0";
        $config['max_width'] = "0";
        $config['max_height'] = "0";
        $config['file_name'] = time().'.'.pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
        $this->load->library('upload',$config);

        if($this->upload->do_upload('upload')) {
            $callback = $this->input->post('callback');
            $data = $this->upload->data();
            $img = '/'.$config['upload_path'].'/'.$data['file_name'];
            $thumb_img = '/'.$config['upload_path'].'/'.$this->createMiniImg($img);
            $album_id = $this->Goods_model->SaveGoodsAlbumImg(array('gid'=>$gid, 'path'=>$img, 'thumbpath'=>$thumb_img));
            echo "<script type='text/javascript'>window.parent.$callback('$thumb_img', {$album_id});window.location.href='/admingoods/addimg/$gid';</script>'";
        } else{
            $msg = strip_tags($this->upload->display_errors());
            echo "<script type='text/javascript'>alert('$msg')</script>";
        }
    }
    public function goodslist()
    {
        $gtype = $this->uri->segment(3,0);
        $page = $this->uri->segment(4,1);
        $data['attrOption'] = $this->createAttrSonOption($gtype);
        if ($gtype) {
            $goods_total = $this->Goods_model->GetGoodsTotal($gtype);
            $per_page = 10;
            $total_pages = ceil($goods_total/$per_page);
            if ($page > $total_pages || $page < 1) $page = 1;
            $data['goodsList'] = $this->Goods_model->GetGoodsLists($gtype, array(), ($page-1)*$per_page, $per_page);
            $data['pagination'] = $this->createPagination("/admingoods/goodslist/$gtype", $goods_total, $per_page, $page);
        }
        
        $this->load->view('admin/goods_list.php',$data);
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
    public function type()
    {
        $data['attrlist'] = $this->Goods_model->GetTypeList();
        $data['optionStr'] = $this->createAttrSonOption();
        $data['view'] = 'goods_attr';
        $this->load->view('admin/goods_type.php',$data);
    }
    public function addtype()
    {
        $data = array();
        $data['pid'] = intval($this->input->post('pid', 0));
        $data['name'] = trim($this->input->post('name', ''));

        if ($data['name']) {
            $res = $this->Goods_model->AddType($data);
            if ($res) 
                echo json_encode(array('err'=>0, 'msg'=>'添加成功'));
            else
                echo json_encode(array('err'=>1, 'msg'=>'添加失败'));
        } else {
            echo json_encode(array('err'=>2, 'msg'=>'分类名不能为空'));
        }
    }
    public function deltype()
    {
        $id = intval($this->input->post('id', 0));
        if ($id) {
            $res = $this->Goods_model->DelType($id);
            if ($res) 
                echo json_encode(array('err'=>0, 'msg'=>'删除商品分类成功'));
            else
                echo json_encode(array('err'=>1, 'msg'=>'删除商品分类失败'));
        } else {
            echo json_encode(array('err'=>1, 'msg'=>'请指定要删除的分类'));
        }
    }
    public function updatetype()
    {
        $id = intval($this->input->post('id', 0));
        $name = trim($this->input->post('name', ''));
        if ($id && $name) {
            $res = $this->Goods_model->UpdateType($id, $name);
            if ($res)
                echo json_encode(array('err'=>0, 'msg'=>'修改商品分类名成功'));
            else
                echo json_encode(array('err'=>0, 'msg'=>'修改商品分类名失败'));
        } else {
            echo json_encode(array('err'=>0 ,'msg'=>'请指定要修改商品分类及其分类名'));
        }
    }
    public function createAttrOption()
    {
        $optionStr = '';
        $attrlist = $this->Goods_model->GetTypeList();
        if ($attrlist) {
            foreach ($attrlist as $attr) {
                $optionStr .= "<option value='{$attr['id']}'>{$attr['name']}</option>"; 
                //if ($attr['son']) $this->goodsAttrSonOption($attr['son'], $optionStr);
            }
        }
        return $optionStr;
    }
    public function createAttrSonOption($selectedId = 0)
    {
        $optionStr = '';
        $attrlist = $this->Goods_model->GetTypeList();
        if ($attrlist) {
            foreach ($attrlist as $attr) {
                $selectedStr = '';
                if ($attr['id'] == $selectedId) $selectedStr = "selected='selected'";
                $optionStr .= "<option value='{$attr['id']}' $selectedStr>{$attr['name']}</option>"; 
                if ($attr['son']) $this->goodsAttrSonOption($attr['son'], $optionStr, $selectedId);
            }
        }
        return $optionStr;
    }
    public function goodsAttrSonOption($data, &$str, $selectedId = 0)
    {
        foreach ($data as $v) {
            $prestr = str_repeat('&nbsp;&nbsp;', $v['level']);
            $selectedStr = '';
            if ($v['id'] == $selectedId) $selectedStr = "selected='selected'";
            $str .= "<option value='{$v['id']}' $selectedStr>$prestr|--{$v['name']}</option>";
            if ($v['son']) $this->GoodsAttrSonOption($v['son'], $str);
        }
    }
    public function addAttrInfo()
    {
        $aid = intval($this->input->post('aid', ''));
        $attrinfo = $this->input->post('attrinfo', '');
        $atype = $this->input->post('atype', '');
        
        if ($aid && $attrinfo && $atype) {
            $res = $this->Goods_model->AddAttrInfo($aid, $attrinfo, $atype);
            if ($res)
                echo json_encode(array('err'=>0, 'msg'=>'添加成功', 'id'=>$res));
            else
                echo json_encode(array('err'=>2, 'msg'=>'添加失败' ));
        } else {
            echo json_encode(array('err'=>1, 'msg'=>'数据不完整'));
        }
    }
    public function updateAttrInfo()
    {
        $id = intval($this->input->post('id', 0));
        $attrinfo = $this->input->post('attrinfo', '');

        if ($id && $attrinfo) {
            $res = $this->Goods_model->UpdateAttrInfo($id, $attrinfo);
            if ($res)
                echo json_encode(array('err'=>0, 'msg'=>'修改成功'));
            else
                echo json_encode(array('err'=>1, 'msg'=>'修改失败'));
        } else {
            echo json_encode(array('err'=>2, 'msg'=>'数据不完整'));
        }
    }
    public function delAttrInfo()
    {
        $id = intval($this->input->post('id'));
        if ($id) {
            $res = $this->Goods_model->DelAttrInfo($id);
            if ($res)
                echo json_encode(array('err'=>0, 'msg'=>'删除成功'));
            else
                echo json_encode(array('err'=>1, 'msg'=>'删除失败'));
        } else {
            echo json_encode(array('err'=>2, 'msg'=>'数据不完整'));
        }
    }
    public function getAttrInfoList()
    {
        $aid = intval($this->input->post('aid', 0));
        $atype = $this->input->post('atype', '');
        
        if ($aid && $atype) {
            $list  = $this->Goods_model->GetAttrInfo(array('aid'=>$aid,'atype'=>$atype));
            if ($list) 
                echo json_encode(array('err'=>0, 'list'=>$list));
            else
                echo json_encode(array('err'=>1, 'list'=>''));
        } else {
            echo json_encode(array('err'=>1, 'list'=>''));
        }
    }
    public function getGoodsAttrInfoLists()
    {
        $author_type = array();
        $craft = array();
        $theme = array();
        $age = array();
        $aid = intval($this->input->post('aid', 0));
        $pid = $this->getGoodsTypePid($aid);
        
        $attrArr = array();
        $attrArr = $this->Goods_model->getGoodsAttrByAid($pid, $attrArr);
        if ($aid != $pid)
        	$attrArr = $this->Goods_model->getGoodsAttrByAid($aid, $attrArr);
        	
        $attrFlagArr = $this->Goods_model->getGoodsAttrFlg();
        echo json_encode(array('flags'=>$attrFlagArr, 'attrs'=>$attrArr));
    }
    public function getGoodsTypePid($id)
    {
        $goodsInfo = $this->Goods_model->GetType($id);
        if ($goodsInfo['pid'] == 0) return $goodsInfo['id'];
        else return $this->getGoodsTypePid($goodsInfo['pid']);
    }
    public function delimg($id)
    {
        if ($this->Goods_model->DelGoodsImg($id)) {
            echo json_encode(array('err'=>0, 'msg'=>'删除成功'));
        } else {
            echo json_encode(array('err'=>1, 'msg'=>'删除失败'));
        }
    }
    public function miniImg()
    {
        /*
        $query = $this->db->query("select * from goods_info");
        foreach ($query->result_array() as $row) {
            $filename = '/uploads/'.$this->createMiniImg($row['img']);
            if ($this->db->query("update goods_info set thumb_img='{$filename}' where id={$row['id']}")) {
                echo $row['id'].'<br />';
            }
        }
        */
    }
    public function createMiniImg($img)
    {
        $config = array();
        $config['image_library'] = 'gd2';
        $config['source_image'] = BASEPATH.'..'.$img;
        $config['create_thumb'] = TRUE;
        $config['maintain_ratio'] = TRUE;
        $config['quality'] = 60;
        $config['width'] = 202;
        $img_info = getimagesize($config['source_image']);
        $config['height'] = ceil(($img_info[1]*$config['width'])/$img_info[0]);
        $this->load->library('image_lib'); 
        $this->image_lib->initialize($config);
        $this->image_lib->resize();
        $path_info = pathinfo($config['source_image']);
        return $path_info['filename'].'_'.'thumb.'.$path_info['extension'];
    }
    public function delGoods()
    {
        $id = intval($this->uri->segment(3,0));
        if($this->Goods_model->DelGoods($id))
            echo json_encode(array('err'=>0,'msg'=>'操作成功'));
        else
            echo json_encode(array('err'=>1, 'msg'=>'操作失败'));
    }
    public function attrcustom()
    {
    	$gtype = intval($this->uri->segment(3,0));
    	$data['attrflag'] = $this->uri->segment(4, '');
    	$data['attrlists'] = $this->Goods_model->getGoodsAttrLists();
    	$data['optionStr'] = $this->createAttrSonOption($gtype);
    	
    	
    	$goods_pid = $this->getGoodsTypePid($gtype);
    	if ($data['attrflag']) {
    		$data['goodsattr'] = array();
    		$data['goodsattr'] = array_merge($data['goodsattr'], $this->Goods_model->GetAttrInfo(array('aid'=>$goods_pid, 'atype'=>$data['attrflag'])));
    		if ($goods_pid != $gtype)
    			$data['goodsattr'] = array_merge($data['goodsattr'], $this->Goods_model->GetAttrInfo(array('aid'=>$gtype, 'atype'=>$data['attrflag'])));
    	}
    	
    	$this->load->view('admin/goods_attr_custom.php', $data);
    }
    public function addattr()
    {
    	$data['attrlists'] = $this->Goods_model->getGoodsAttrLists();
    	$this->load->view('admin/goods_attr.php',$data);
    }
    public function addgoodsattr()
    {
    	$attrname = trim($this->input->post('attrname'));
    	$attrid = $this->Goods_model->addGoodsAttr(array('val'=>$attrname));
    	$attrflag = 'gooodsattr_'.$attrid;
    	$this->Goods_model->updateGoodsAttr(array('flag'=>$attrflag), array('id'=>$attrid));
    	$this->Goods_model->alterGoodsInfoFields($attrflag);
    	echo json_encode(array('err'=>false, 'attrid'=>$attrid, 'attrflag'=>$attrflag));
    }
    public function updategoodsattr()
    {
    	$attrid = intval($this->input->post('id', 0));
    	$attrname = trim($this->input->post('val', ''));
    	if ($attrid && $attrname)
    		$this->Goods_model->updateGoodsAttr(array('val'=>$attrname), array('id'=>$attrid));
    }
    public function delgoodsattr()
    {
    	$attrid = intval($this->input->post('id', 0));
    	if ($attrid && $this->Goods_model->delGoodsAttr($attrid))
    		echo json_encode(array('err'=>false, 'msg'=>'删除成功'));
    	else
    		echo json_encode(array('err'=>true, 'msg'=>'删除失败'));
    }
} 
