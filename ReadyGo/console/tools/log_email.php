<?php
class log_email_tool
{
	function sent_email($sBody,$sSubject='',$sCharSet = 'UTF-8',$sEmailClassPath = '')
	{
		require_once("PHPMailer/class.phpmailer.php");
		$mail = new PHPMailer();
		
		$mail->IsSMTP();                                      // set mailer to use SMTP
		$mail->Host = "mail.cyberon.com.tw";  // specify main and backup server
		$mail->SMTPAuth = true;     // turn on SMTP authentication
		$mail->Username = "jianwen_qiu";  // SMTP username
		$mail->Password = "Qjw16899"; // SMTP password
		
		$mail->From = "jianwen_qiu@cyberon.com.tw";
		$mail->FromName = "jianwen_qiu";
		$mail->AddAddress('dongchenfeel@qq.com', "dongchen");
		//$mail->AddAddress("ellen@example.com");                  // name is optional
		//$mail->AddReplyTo("info@example.com", "Information");
		
		$mail->WordWrap = 50;                                 // set word wrap to 50 characters
		//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
		//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
		$mail->IsHTML(true);                                  // set email format to HTML
		
		$mail->CharSet = $sCharSet;  //编码
		$mail->Subject = $sSubject;
		$mail->Body    = $sBody;
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients";
		
		if(!$mail->Send())
		{
		   $sMsg = "Message could not be sent. \nMailer Error: " . $mail->ErrorInfo;
		   $this->write_log($sMsg);
	//	   exit;
		}
	}
	
	function write_log($sMsg,$sLogsPath='',$sLogClassPath = '',$sLogFilename='')
	{
		require_once("class.loger.php");
		if ($sLogFilename) {
			$loger = new Logs($sLogsPath,$sLogFilename);
		} else {
			$loger = new Logs($sLogsPath);
		}
		$loger->setLog($sMsg);
	}
}