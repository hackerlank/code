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

        $data['imglist'] = array();
        if ($id) $data['imglist'] = $this->Goods_model->GetGoodsImgList($id);
        $data['attrOption'] = $this->createAttrSonOption($data['info']['goods_type']);
        $this->load->view('admin/goods_add.php', $data);
    }
    public function getGoodsAttr($type)
    {
        $value = $this->Goods_model->GetGoodsAttrType($type);
        return explode(',',$value);
    }
    public function saveGoods()
    {
        if (!$this->session->userdata("isadmin")) return $this->load->view("admin/login.php");

        $data = array();
        $data['goods_type'] = $this->input->post('goods_type',0);
        $data['name'] = $this->input->post('goods_name', '');
        $data['author'] = $this->input->post('author_name', '');
        $data['author_type'] = $this->input->post('author_type', '');
        $data['author_title'] = $this->input->post('author_title', '');
        $data['standard'] = $this->input->post('standard', '');
        $data['craft'] = $this->input->post('craft', '');
        $data['theme'] = $this->input->post('theme', '');
        $data['age'] = $this->input->post('age', '');
        $data['time'] = $this->input->post('time', '');
        $data['price'] = $this->input->post('price', '');
        $data['brief'] = $this->input->post('brief', '');

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
        $this->load->view("/admin/uploadimg.php",$data);
    }
    public function saveimg()
    {
        $gid = intval($this->input->post('gid',0));
        if (!$gid) die("<scrpit>alert('非法数据');</script>");
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = "0";
        $config['max_width'] = "0";
        $config['max_height'] = "0";
        $config['file_name'] = time().'.'.pathinfo($_FILES['upload']['name'], PATHINFO_EXTENSION);
        $this->load->library('upload',$config);
        
        if($this->upload->do_upload('upload')) {
            $callback = $this->input->post('callback');
            $data = $this->upload->data();
            $img = '/uploads/'.$data['file_name'];
            $thumb_img = '/uploads/'.$this->createMiniImg($img);
            $this->Goods_model->SaveGoodsImg($gid, $img, $thumb_img);
            echo "<script type='text/javascript'>window.parent.$callback('$img');window.location.href='/admingoods/addimg/$gid';</script>'";
        } else{
            $msg = strip_tags($this->upload->display_errors());
            echo "<script type='text/javascript'>alert('$msg')</script>";
        }
    }
    public function goodslist()
    {
        $gtype = intval($this->input->post('gtype',0));
        $data['attrOption'] = $this->createAttrSonOption($gtype);
        if ($gtype)
            $data['goodsList'] = $this->Goods_model->GetGoodsLists($gtype);
        $this->load->view('admin/goods_list.php',$data);
    }
    public function attr()
    {
        $data['attrlist'] = $this->Goods_model->GetAttrList();
        $data['view'] = 'goods_attr';
        $this->load->view('admin/goods_attr.php',$data);
    }
    public function addattr()
    {
        $data = array();
        $data['pid'] = intval($this->input->post('pid', 0));
        $data['name'] = trim($this->input->post('name', ''));

        if ($data['name']) {
            $res = $this->Goods_model->AddAttr($data);
            if ($res) 
                echo json_encode(array('err'=>0, 'msg'=>'添加成功'));
            else
                echo json_encode(array('err'=>1, 'msg'=>'添加失败'));
        } else {
            echo json_encode(array('err'=>2, 'msg'=>'分类名不能为空'));
        }
    }
    public function delattr()
    {
        $id = intval($this->input->post('id', 0));
        if ($id) {
            $res = $this->Goods_model->DelAttr($id);
            if ($res) 
                echo json_encode(array('err'=>0, 'msg'=>'删除商品分类成功'));
            else
                echo json_encode(array('err'=>1, 'msg'=>'删除商品分类失败'));
        } else {
            echo json_encode(array('err'=>1, 'msg'=>'请指定要删除的分类'));
        }
    }
    public function updateattr()
    {
        $id = intval($this->input->post('id', 0));
        $name = trim($this->input->post('name', ''));
        if ($id && $name) {
            $res = $this->Goods_model->UpdateAttr($id, $name);
            if ($res)
                echo json_encode(array('err'=>0, 'msg'=>'修改商品分类名成功'));
            else
                echo json_encode(array('err'=>0, 'msg'=>'修改商品分类名失败'));
        } else {
            echo json_encode(array('err'=>0 ,'msg'=>'请指定要修改商品分类及其分类名'));
        }
    }
    public function authortype()
    {
        $data['attr_option'] = $this->createAttrOption();
        $data['title'] = "作者分类";
        $data['type'] = 'author_type';
        $this->load->view('admin/goods_attr_info.php', $data);
    }
    public function craft()
    {
        $data['attr_option'] = $this->createAttrOption();
        $data['title'] = "工艺";
        $data['type'] = 'craft';
        $this->load->view('admin/goods_attr_info.php', $data);
    }
    public function theme()
    {
        $data['attr_option'] = $this->createAttrOption();
        $data['view'] = 'goods_attr_info';
        $data['title'] = "题材";
        $data['type'] = 'theme';
        $this->load->view('admin/goods_attr_info.php', $data);
    }
    public function age()
    {
        $data['attr_option'] = $this->createAttrOption();
        $data['view'] = 'goods_attr_info';
        $data['title'] = "创作时间";
        $data['type'] = 'age';
        $this->load->view('admin/goods_attr_info.php', $data);
    }
    public function createAttrOption()
    {
        $optionStr = '';
        $attrlist = $this->Goods_model->GetAttrList();
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
        $attrlist = $this->Goods_model->GetAttrList();
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
                echo json_encode(array('err'=>0, 'msg'=>'添加成功'));
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
        $pid = $this->getGoodsAttrPid($aid);
        if ($pid) {
            $author_type = $this->Goods_model->GetAttrInfo(array('aid'=>$pid, 'atype'=>'author_type'));
            $craft = $this->Goods_model->GetAttrInfo(array('aid'=>$pid, 'atype'=>'craft'));
            $theme = $this->Goods_model->GetAttrInfo(array('aid'=>$pid, 'atype'=>'theme'));
            $age = $this->Goods_model->GetAttrInfo(array('aid'=>$pid, 'atype'=>'age'));
        }
        echo json_encode(array('author_type'=>$author_type, 'craft'=>$craft, 'theme'=>$theme, 'age'=>$age));
    }
    public function getGoodsAttrPid($id)
    {
        $goodsInfo = $this->Goods_model->GetAttr($id);
        if ($goodsInfo['pid'] == 0) return $goodsInfo['id'];
        else return $this->getGoodsAttrPid($goodsInfo['pid']);
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
        $query = $this->db->query("select * from goods_info");
        foreach ($query->result_array() as $row) {
            $filename = '/uploads/'.$this->createMiniImg($row['img']);
            if ($this->db->query("update goods_info set thumb_img='{$filename}'")) {
                echo $row['id'].'<br />';
            }
        }
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
} 
