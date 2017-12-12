<?php

require_once $_CFG['sys']['fn_src'] . 'f_static.php';		//	functions for this module
require_once 'static_forms.php';							//	custom functions for static forms

$module = "static";

$module_data_path = $_CFG['sys']['content_src'] . $_CFG['sys']['static_src'];

if (!isset($_GET['page']))
{
	$st_page = $_CFG['static']['homepage'];
	$st_file = $st_page .'.'. $_CFG['sys']['lang'].$_CFG['sys']['stf_ext'];	// front page
}
else
{
	$st_page = strtolower($_GET['page']);
	$st_file = $st_page .'.'. $_CFG['sys']['lang']. $_CFG['sys']['stf_ext'];	// static page - from text file
}

if (isset($_SESSION['msg']))
{
	if (isset($_SESSION['msgpuri']) && ($_SESSION['msgpuri']==request_uri()))
	{
		// flash message, just for page which is intended
		$tpl->define(array('static_msg_tpl' => 'static_msg.tpl'));
		$tpl->assign('STAT_MSG', preg_replace("/(\r\n|\n)/", $_CFG['sys']['br']."\\1", $_SESSION['msg']));
		$tpl->parse('STAT_MSG_TPL', 'static_msg_tpl');
	}
	else
	{
		unset($_SESSION['msg']);		// unset message
		unset($_SESSION['msgpuri']);	// unset also page URI for this message
	}
}

// create URI for language switch, for all supported languages assigned as template keyword
// (example: SWITCH_LN_DE, SWITCH_LN_EN, SWITCH_LN_SK)
foreach ($locale as $val)
{
	$tpl->assign(
		'SWITCH_LN_'.strtoupper($val['code']),
		'?'.http_build_query(
			array_merge(
				$_GET, array('lang' => $val['code'])),
				'',
				'&amp;'
		)
	);
}

if(file_exists($module_data_path.$st_file))	// checking if this content file exists
{
	$output = file_get_contents($module_data_path . $st_file);

	$output = trim($output);

	// H1 title as page title
	if (preg_match('/<h1>(.*)<\/h1>(\s*)/i', $output, $matches))
	{	// you can use other inline tags between H1 tags
		$title = $matches[0];
		$title = trim(preg_replace('/<h1>(.*)<\/h1>/i', '$1', $title));

		$output = substr($output, strlen($matches[0]), strlen($output));
		$tpl->assign("PAGE_TITLE", " - ".strip_tags($title));	// html head page title
		$tpl->assign("MAIN_HEADING", "<h2>".$title."</h2>");	// main content heading
	}

	if (strpos($output, "<?") !== FALSE)
	{
		$output = php_parser($output);	// parse PHP code if there are any
	}

	// path to images includes page name (content/static/img/PAGE_NAME)
	$img_path = "./" . $module_data_path . $_CFG['sys']['img_src'] . $st_page . "/";
	$output = str_replace('[IMG_SRC]', $img_path, $output);

	// path to files includes page name (content/static/file/PAGE_NAME)
	$file_path = "./" . $module_data_path . $_CFG['sys']['file_src'] . $st_page . "/";
	$output = str_replace('[FILE_SRC]', $file_path, $output);

	// path to files includes page name (content/static/file/PAGE_NAME)
	$media_path = "./" . $module_data_path . $_CFG['sys']['media_src'] . $st_page . "/";
	$output = str_replace('[MEDIA_SRC]', $file_path, $output);

	// break if there are simple text
	if(isset($_GET['br'])) $output = preg_replace("/(\r\n|\n)/", $_CFG['sys']['br']."\\1", $output);

	$tpl->define(array('static_tpl' => 'static.tpl'));
	$tpl->assign('MAIN_CONTENT', $output);

    static_parse_form_vals($tpl);

	$tpl->parse('MAIN_CONTENT_TPL', 'static_tpl');
}
else
{
    header('HTTP/1.0 404 Not Found', true, 404);

	$tpl->define(array('not_found_tpl' => '_not_found.tpl'));
	$tpl->parse('MAIN_CONTENT_TPL', 'not_found_tpl');

    $tpl->assign("PAGE_TITLE", " - 404");	// html head page title
}

?>
