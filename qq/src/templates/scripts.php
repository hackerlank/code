<?php echo "\n" .
'<script type="text/javascript" src="'.TMConfig::get('base_url').'_jsmin/b='.preg_replace('#^http://.*?/(.*)$#i', '$1', TMConfig::get('base_url')).'js&f=' .
'jquery/jquery.1.4.min.js,' .
'swf/swfobject.js,' .
'project.js' .
($_ENV['SERVER_TYPE'] == 'test' ? '&debug=1' : '') .
'"></script>' .
"\n\n";
?>
