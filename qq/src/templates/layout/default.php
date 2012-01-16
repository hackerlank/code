<?php
/**
 * 如何使用layout
 * 你需要在调用controller render方法的时候传入第一个参数即可
 * 例如 你的某个页面需要使用 这个layout（即 default )，这样调用render:
 *   $this->render('default');
 * 至于render的第二个参数是页面所需的模板，可以是相对于ROOT_PATH.'templates/'的相对路径，也可以是绝对路径
 *   $this->render('default', 'list.php'); //use simple layout render list.php
 *   $this->render('default', ROOT_PATH.'templates/detail.php'); //use simple layout render ROOT_PATH.'templates/detail.php'
 *   $this->render(null, 'list.php'); //default layout render list.php
 *   $this->render('', 'list.php'); //render list.php directly same as $this->render(null, 'list.php');
 *
 * layout使用的原理，可以这样理解
 *   1. 获取页面所需要数据放入$vars中
 *   2. 利用output buffer处理template, 即$layoutContent = renderFile($vars, $tpl);
 *   3. $vars['layoutContent'] = $layoutContent;
 *   4. 利用output buffer处理layout, 即$output = renderFile($vars, $layout);
 *   5. 返回$output
 *
 *   注：不使用layout相当于把$output=$layoutContent作为结果返回
 *      即不考虑slot的情况下，如果不使用layout每个页面只需一次renderFile，而使用layout则需要两次renderFile
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<base href="<?php echo TMConfig::get("base_url"); ?>"/>
<title>PHPLIB2 Demo</title>
<link href="<?php echo TMConfig::get("base_url") . 'css/app1.3/jsapplib.css'; ?>" rel="stylesheet" type="text/css" />
<?php include ROOT_PATH . 'templates/scripts.php'; ?>
</head>
<body >
<?php TMSlot::includeSlot('menu'); ?>
<?php echo $layoutContent; ?>
<?php TMSlot::includeSlot('footer'); ?>
</body>
</html>