<?php
/**
 *@author:xiaoshengeer@gmail.com
 *@create:2012-4-10
 *@encoding:UTF-8 tab=4space
 */
class Common
{
    public function  page($total,$page,$url)
    {
        $str = '';
        for ($i=1;$i<=$total;$i++) {
            $str .= "<a href='{$url}/$i'>$i</a>";
        }
        if ($page==1 || $page==0)
            return '<div class="page"><a href="javascript:;">&lt;&lt; 上一页</a>'.$str.'<a href="'."$url/".($page+1).'">下一页 &gt;&gt;</a></div>';
        if ($page==$total)
            return '<div class="page"><a href="'."$url/".($page-1).'">&lt;&lt; 上一页</a>'.$str.'<a href="javascript:;">下一页 &gt;&gt;</a></div>';
        return '<div class="page"><a href="'."$url/".($page-1).'">&lt;&lt; 上一页</a>'.$str.'<a href="'."$url/".($page+1).'">下一页 &gt;&gt;</a></div>';
    }
}

