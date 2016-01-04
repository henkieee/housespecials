<?php
//functions on sending email

//Send email txt/html including attachments, using SMTP
//address can be: "User" <user@example.com>, "Another User" <anotheruser@example.com>
//input ContentType:
//	txt	=default=send simple txt message
//	html	=send html message
//input Chkfiles: TRUE/FALSE, on TRUE then check existence of attachment files
//return result: TRUE/FALSE on email execution
//source: http://php.net/manual/en/function.mail.php
//see also: http://forums.devnetwork.net/viewtopic.php?f=1&t=127699#p645478
function Fnc_email_smtp($ContentType, $from, $to, $cc, $bcc, $replyto, $failto, $subject, $message, $Chkfiles=TRUE, $attachments=NULL) {
	if ($from=="" || $to=="") return FALSE;

	//check if attachments exist
	$NumAttachments=0;
	if (is_array($attachments)) {
		if ($Chkfiles==TRUE) {
			$ChkAttachments=array();
			foreach ($attachments as $attachment) {
				if (@is_readable($attachment)) {
					$ChkAttachments[$NumAttachments]=$attachment;
					$NumAttachments++;
				}
			}
			$attachments=$ChkAttachments;
		} else {
			$NumAttachments=count($attachments);
		}
	}

	//$message='tekst inhoud.';
	if ($ContentType=="txt" && strtoupper(substr(PHP_OS, 0, 3))==='WIN') {
		//$message=str_replace(PHP_EOL, "\n", $message); //each line should be separated with a LF (\n)
		$message=str_replace("\n.", "\n..", $message); //resolve feature on Windows and talking to a SMTP server directly
		$message=wordwrap($message, 70); //each line should not be larger than 70 characters
	}

	$headers='MIME-Version: 1.0'.PHP_EOL;
	$headers.='From: '.$from.PHP_EOL;
	if ($cc>"") $headers.='Cc: '.$cc.PHP_EOL;
	if ($bcc>"") $headers.='Bcc: '.$bcc.PHP_EOL;
	if ($replyto>"") $headers.='Reply-To: '.$replyto.PHP_EOL;
	//if ($failto>"") $headers.='Fail-To: '.$failto.PHP_EOL; //NOT_TODO, not supported, use other mailers: http://swiftmailer.org, http://phpmailer.sourceforge.net
	if ($ContentType=="txt") $headers.='X-Mailer: PHP/'.phpversion().PHP_EOL;

	//handle simple email
	if ($NumAttachments<1) {
		$headers.="Content-Type: text/".($ContentType=="html"?"html":"plain")."; charset=\"iso-8859-1\"".PHP_EOL;
	} else { //handle email including attachments
		// boundary
		$semi_rand=md5(time());
		$mime_boundary="==Multipart_Boundary_x{$semi_rand}x";

		// headers for attachment
		$headers.="Content-Type: multipart/mixed;".PHP_EOL." boundary=\"{$mime_boundary}\"";

		// multipart boundary
		$message="--{$mime_boundary}".PHP_EOL."Content-Type: text/".($ContentType=="html"?"html":"plain")."; charset=\"iso-8859-1\"".PHP_EOL."Content-Transfer-Encoding: 7bit".PHP_EOL.PHP_EOL.$message.PHP_EOL.PHP_EOL;

		// add attachments
		foreach ($attachments as $attachment) {
			$message.="--{$mime_boundary}".PHP_EOL;
			$fp=@fopen($attachment, "rb");
			if ($fp!==FALSE) {
				$data=@fread($fp, filesize($attachment));
				@fclose($fp);
				if ($data!==FALSE) {
					$data=chunk_split(base64_encode($data));
					$message.="Content-Type: application/octet-stream; name=\"".basename($attachment)."\"".PHP_EOL.
					"Content-Description: ".basename($attachment).PHP_EOL.
					"Content-Disposition: attachment;".PHP_EOL." filename=\"".basename($attachment)."\"; size=".filesize($attachment).";".PHP_EOL.
					"Content-Transfer-Encoding: base64".PHP_EOL.PHP_EOL.$data.PHP_EOL.PHP_EOL;
				}
			}
		}
		$message.="--{$mime_boundary}--";
	}

	//$returnpath="-f".$from;
	//$result=@mail($to, $subject, $message, $headers, $returnpath); //TODO_ERROR, security, set extra returnpath param, ERROR: More than one "from" person
	$result=@mail($to, $subject, $message, $headers);
	return $result;
} //Fnc_email_smtp
?>
