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
    }
    public function Index()
    {
        $this->load->view('zh/goods.php');
    }
    public function Info()
    {
        $this->load->view('zh/goods_info.php');
    }
    
}
