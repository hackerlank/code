<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@create:2012-3-27
 *@encoding:UTF-8 tab=4space
 */
class Index extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();
    }
    public function Index()
    {
        $this->load->view('welcome_message');
    }
}
