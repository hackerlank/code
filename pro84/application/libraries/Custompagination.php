<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    /**
     * @author:xiaoshenge
     * @email:xiaoshengeer@gmail.com
     * @create:2012-09-02 10:09:45
     * @encoding:utf8 sw=4 ts=4
     **/
class Custompagination extends CI_Pagination 
{
    public $total_pages;
    public $next_page;
    public $prev_page;
    public function __construct()
    {
        parent::__construct();
    }
    public function create_links()
    {
        $this->total_pages = ceil($this->total_rows / $this->per_page);
        $this->next_page = $this->cur_page+1;
        $this->prev_page = $this->cur_page -1;

        if ($this->total_pages < 1)
            return '';
        $pagination_str = '';
        if ($this->total_pages < $this->num_links) {
            for ($i = 1; $i <= $this->total_pages; $i++) {
                if ($i == $this->cur_page) 
                    $pagination_str .= "<span class='current'>$i</span>";
                else
                    $pagination_str .= "<a href='{$this->base_url}/$i'>$i</a>";
            }
        } else {
            $iStart = ($this->cur_page < ($this->total_pages-$this->num_links)) ? $this->cur_page : $this->total_pages-$this->num_links;
            $iMax = (($this->cur_page+$this->num_links) > $this->total_pages) ? $this->total_pages : $this->cur_page+$this->num_links;
            
            for ($i = $iStart; $i <= $iMax; $i++){
                if ($i == $this->cur_page) 
                    $pagination_str .= "<span class='current'>$i</span>";
                else
                    $pagination_str .= "<a href='{$this->base_url}/$i'>$i</a>";
            }
            if ($this->cur_page == 2)
                $pagination_str = "<a href='{$this->base_url}/1'>1</a>".$pagination_str;
            elseif ($this->cur_page > 2)
                $pagination_str = "<a href='{$this->base_url}/1'>1</a><span class='ellipsis'>...</span>".$pagination_str;
            if ($iMax < $this->total_pages)
                $pagination_str .= "<span class='ellipsis'>...</span><a href='{$this->base_url}/$this->total_pages'>$this->total_pages</a>"; 
        }
        
        if ($this->cur_page >1)
            $pagination_str = "<a href='{$this->base_url}/$this->prev_page'><ins><<</ins><span>上一页</span></a>".$pagination_str;
        
        if ($this->cur_page < $this->total_pages)
            $pagination_str .= "<a href='{$this->base_url}/$this->next_page'><span>下一页</span></a>";

        $pagination_str .= "<span class='page-skip'>到第 <input type='text' class='goto' />页<button onclick='javascript:gotopage();'>确定</button></span>";
        $pagination_str = "<div class='pagination'><div class='pagelist_info'>共{$this->total_rows}条记录,每页{$this->per_page}条</div>$pagination_str</div>";
        $pagination_str .= "<script type='text/javascript'>function gotopage(){var page=parseInt($('.goto').val()); if (page) window.location.href='{$this->base_url}/'+page;}</script>";
        return $pagination_str;
    }
}
