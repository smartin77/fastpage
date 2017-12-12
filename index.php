<?php

// read config
require '_config.inc.php';

// template init - switch template folder based on language code
$tpl = new FastTemplate($_CFG['sys']['tpl_src']. "/".$_CFG['sys']['lang']."/");	// localized

$tpl->define(array(
	'layout_tpl' => '_main.tpl',					// main layout - you can add more fragments of web page
	'menu_left_tpl' => '_menu_left.tpl'				// left side categories menu
));


// FastTemplate variables
$tpl->assign('VAR_CSS_SRC', $_CFG['sys']['css_src']);
$tpl->assign('VAR_JS_SRC', $_CFG['sys']['js_src']);
$tpl->assign('VAR_LANG', $_CFG['sys']['lang']);

$tpl->assign('HREF_BASEHREF', $_CFG['basehref']);

$tpl->assign('MAIN_HEADING', '');

include 'static.php';	// static page module

$tpl->parse('MENU_LEFT_TPL', 'menu_left_tpl');
$tpl->parse('ALL', 'layout_tpl');
$tpl->FastPrint('ALL');

?>
