<?php

function php_parser($content)
{
	ob_start();
	$content = str_replace('<'.'?php','<'.'?',$content);
	eval('?'.'>'.trim($content).'<'.'?');
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}


if (!function_exists('http_build_query'))
{
	function http_build_query($data=array(), $ns='', $separator='&')
	{
		if (count($data)>0)
		{
			$tmp = array();
			foreach ($data as $key => $val)
			{
				if (is_numeric($key))
				{	$tmp[] = urlencode($ns.$key). '=' .urlencode($val);	}
				else
				{	$tmp[] = urlencode($key). '=' .urlencode($val);	}
			}

			return implode($separator, $tmp);
		}
	}
}


function mail64($eml_to, $eml_subject, $eml_msg, $eml_from_address, $eml_from_name, $eml_additional_headers=''){
	global $_CFG;

    $emlheaders = '';
    $emlheaders .= "MIME-Version: 1.0\n";
    $emlheaders .= "Content-Type: text/plain; charset=utf-8\n";
    $emlheaders .= "Content-Transfer-Encoding: base64\n";

    if(isset($eml_from_address) && ($eml_from_address!='')){
		if(isset($eml_from_name) && ($eml_from_name!='')){
            $emlheaders .= "From: =?utf-8?b?" .base64_encode($eml_from_name) ."?= <". $eml_from_address .">\n";
            $emlheaders .= "X-Sender: =?utf-8?b?" .base64_encode($eml_from_name) ."?= <". $eml_from_address .">\n";
		} else {
            $emlheaders .= "From: ". $eml_from_address .">\n";
            $emlheaders .= "X-Sender: ". $eml_from_address .">\n";
		}
    } else {
        $emlheaders .= "From: ". $_CFG['sys']['emlweb'] ."\n";
        $emlheaders .= "X-Sender: ". $_CFG['sys']['emlweb'] ."\n";
    }

    $emlheaders .= "X-Mailer: PHP/" . phpversion(). "\n";
    $emlheaders .= "X-Priority: 1\n";

    echo $emlheaders;

    if(isset($eml_additional_headers)){
        $emlheaders .= $eml_additional_headers;
	}

    return @mail($eml_to, "=?utf-8?b?".base64_encode($eml_subject)."?=", base64_encode($eml_msg), $emlheaders);
}


// convert json to array
function json2array($json = '')
{
	return json_decode(
		html_entity_decode($json), true
	);
}


function request_uri()
{
	$uri = getenv('REQUEST_URI');
	$uri = explode('/',$uri);

	return $uri[count($uri)-1];

}

?>
