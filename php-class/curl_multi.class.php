<?php
class curlMulti
{
    public function createCurlInstance($url)
    {
        $ch = curl_init();
        //curl_setopt
    }
    public function execMulti($ch_lists)
    {
        $res = array();

        $mh = curl_multi_init();

        foreach($ch_lists as $ch)
        {
            curl_multi_add_handle($mh, $ch);
        }

        $running = null;
        do {
            usleep(10000);
            curl_multi_exec($mh, $running);
        } while($running > 0);

        foreach($ch_lists as $k=>$ch)
        {
            $res = curl_multi_getcontent($ch);
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
        }
        curl_multi_close($mh);
        return $res;
    }
}
