<?php

// form data validation and sending as email

if(!isset($_SESSION['formdata']) || !is_array($_SESSION['formdata'])){
    $_SESSION['formdata'] = array();
}

if (isset($_POST['submit_contactus']))
{
	// store form fields values to the session in case of prefill returned (invalid) form
	foreach ($_POST as $key => $val)
	{
        if (is_array($val))
        {
        	// some fields can be sent as arrays
            $_SESSION['formdata'][$key] = $val;
        }
        else if (trim($val) != '')
        {
            // store just non empty values
            $_SESSION['formdata'][$key] = trim($val);
        }
	}

	// validation
	$valid = TRUE;

	// at least one contact information - telephone or email
	if ($valid === TRUE)
	{
		if (isset($_SESSION['formdata']['email']) || isset($_SESSION['formdata']['phone']))
		{
			if (!preg_match('/^[a-z0-9_]{1}[a-z0-9\-_]*(\.[a-z0-9\-_]+)*@[a-z0-9]{1}[a-z0-9\-_]*(\.[a-z0-9\-_]+)*\.[a-z]{2,4}$/i', $_SESSION['formdata']['email']))
			{
				$valid = FALSE;
				$_SESSION['msg'] = $_CFG['msg'][$_CFG['sys']['lang_id']][5];
			}
		}
		else
		{
			$valid = FALSE;
			$_SESSION['msg'] = $_CFG['msg'][$_CFG['sys']['lang_id']][4];
			$_SESSION['msgpuri'] = request_uri();
		}
	}

	// message
	if ($valid === TRUE)
	{
		if (!isset($_SESSION['formdata']['message']))
		{
			$valid = FALSE;
			$_SESSION['msg'] = $_CFG['msg'][$_CFG['sys']['lang_id']][3];
			$_SESSION['msgpuri'] = request_uri();
		}
	}

	if ($valid === TRUE)
	{
        $tpl->define(array('email_tpl' => '_mail_contactus.tpl'));	// define email only template

		if (isset($_SESSION['formdata']['email']))
		{
            $eml_from_address = $_SESSION['formdata']['email'];
            $tpl->assign('VAR_FRM1_EMAIL', $_SESSION['formdata']['email']);
		}
		if (isset($_SESSION['formdata']['name']))
		{
            $eml_from_name = $_SESSION['formdata']['name'];
            $tpl->assign('VAR_FRM1_NAME', $_SESSION['formdata']['name']);
		}
		if(isset($_SESSION['formdata']['phone']))
		{
            $tpl->assign('VAR_FRM1_PHONE', $_SESSION['formdata']['phone']);
		}

        if(isset($_SESSION['formdata']['subject']))
        {	// subject comes from form too - its hidden field
            $eml_subject = $_SESSION['formdata']['subject'];
        } else {
            $eml_subject = '';
		}

        $tpl->assign('VAR_FRM1_MESSAGE', $_SESSION['formdata']['message']);
        $tpl->parse('EMAIL', 'email_tpl');		// parse EMAIL - not in output yet

        $eml_msg = $tpl->fetch('EMAIL');					// fetch passed EMAIL content to variable

		if (isset($_CFG['sys']['admineml']))
		{	$eml_headers .= "bcc:".$_CFG['sys']['admineml']."\n";	}

		$eml_recipient = implode(', ', $_CFG['sys']['emlrecipients']);

		$eml_sent = mail64($eml_recipient, $eml_subject, $eml_msg='', $eml_from_address='', $eml_from_name='', $eml_headers='');

		if($eml_sent) {
            $_SESSION['msg'] = $_CFG['msg'][$_CFG['sys']['lang_id']][2];
            unset($_SESSION['formdata']);		// clear stored data - successfully sent
		} else {
            $_SESSION['msg'] = $_CFG['msg'][$_CFG['sys']['lang_id']][6];
		}
	} else {
        $_SESSION['msg'] = $_CFG['msg'][$_CFG['sys']['lang_id']][7];
	}

    $redirect =  request_uri();			// redirect on same page
    $_SESSION['msgpuri'] = $redirect;	// store URI to pagege where message will be displayed

    header('Location:' . $redirect);
	exit;
}

function static_parse_form_vals($tpl){
	global $tpl;

    // parse sent values back to the form fields
    if (isset($_SESSION['formdata']['email'])){
        $tpl->assign('FRM1_VAL_EMAIL', $_SESSION['formdata']['email']);
    }
    if (isset($_SESSION['formdata']['phone'])){
        $tpl->assign('FRM1_VAL_PHONE', $_SESSION['formdata']['phone']);
    }
    if (isset($_SESSION['formdata']['name'])){
        $tpl->assign('FRM1_VAL_NAME', $_SESSION['formdata']['name']);
    }
    if (isset($_SESSION['formdata']['message'])){
        $tpl->assign('FRM1_TXT_MESSAGE', $_SESSION['formdata']['message']);
    }
}

?>
