<?php

/********** Currently all images are in same directory with this file. ******************\


/*
* sendMailAttachment : Send Email with file as an attachment (e.g Image here))
*
* $fileatt_temp : attachment file
* $fileatt_type : attachment file type
* $fileatt_name : attachment file name
* $to : destination Email
* $from : source email
* $subject : Email Subject
* 
*/
function sendMailAttachment($to,$from,$subject){
	
	$fileatt_temp = "imageText.jpg";

	$subject =	 $subject;
	$boundary_text = "anyRandomStringOfCharactersThatIsUnlikelyToAppearInEmail";
	$boundary = "--".$boundary_text."\r\n";
	$boundary_last = "--".$boundary_text."--\r\n";
	$to = $to;
	$htmlMessage = "<html><head><title>Thank You So Much...!!</title></head>"
			."<body></body></html>";

	$fileContents = file_get_contents($fileatt_temp);
	$fileatt_type = "image/jpg";
	$fileatt_name = "Attachment.jpg";
	$emailAttachments = "Content-Type: " .$fileatt_type . "; name=\"" .$fileatt_name. "\"\r\n"
			  ."Content-Transfer-Encoding: base64\r\n"
			  ."Content-disposition: attachment; filename=\"" .$fileatt_name. "\"\r\n"
			  ."\r\n"
			  //Convert the file's binary info into ASCII characters
			  .chunk_split(base64_encode($fileContents))
			  .$boundary;


	$headers =  "From: ".$from."\r\n";
	$headers .=     "MIME-Version: 1.0\r\n"
	."Content-Type: multipart/mixed; boundary=\"$boundary_text\"" . "\r\n";  

	$body ="If you can see this, your email client "
	."doesn't accept MIME types!\r\n"
	.$boundary;

	//Insert the attachment information we built up above.
	//Each of those attachments ends in a regular boundary string    
	$body .= $emailAttachments;

	$body .= "Content-Type: text/html; charset=\"iso-8859-1\"\r\n"
	."Content-Transfer-Encoding: 7bit\r\n\r\n"
	//Inert the HTML message body you passed into this function
	.$htmlMessage . "\r\n"
	//This section ends in a terminating boundary string - meaning
	//"that was the last section, we're done"
	.$boundary_last;

	return mail($to,$subject,$body,$headers);

}

/*
* sendMailHTML : Send Email with HTML Embedded (e.g Image here))
*
* $toEmail : destination Email
* $fromEmail : source email
* $subjectEmail : Email Subject
*
*/
function sendMailHTML($fromEmail,$subjectEmail,$toEmail){
	
	$submobile = "Satish";
	$subemail = "Satish";
	$subuser = "Satish";
	$name = "Satish";
	$url = "";
	$from  = 'MIME-Version: 1.0' . "\r\n";
	$from .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$from .= "From: ".$fromEmail."\r\n";
	$subject = $subjectEmail;
	$to= $toEmail; 
	$tbl = "
	<html>
		<head>
			<title>Enquiry for budget</title>
		</head>
		<body>
			<table width='500px' border='0' cellspacing='0' cellpadding='10' bgcolor='#000000'>"
			."<tr>"
				."<td><a href=''><img src='' height='53' width='301' border='0' /></a></td>"
			."</tr>"
			."<tr>"
				."<td style='color:#FFF; font-size:12px;'>"
					."Dear ".$name.", We have just recieved new enquiry for <a href='".$url."'>".$url."</a><br>"
					."<br> Recieved information <br>Name: ".ucfirst($subuser).". <br>Email: ".$subemail.". <br>Contact number: ".$submobile.". <br>Enquiry about: hello testing. <br>Enquiry on URL: <a href='".$url."'>".$url."</a>."
				."</td>"
			."</tr>"
			."</table>"
		."</body>"
	."</html>";
	return mail($to,$subject,$tbl,$from);
}


/*
* sendMailEmbededWithAttachment : Send Email with file as an attachment and image as embedded(e.g Image here))
*
* $toEmail : destination email
* $subjectEmail : Email subject
* $from : source email
* $bcc = Bcc Email.. comma seperated list
*
*/
function sendMailEmbededWithAttachment($toEmail, $subjectEmail, $from, $bcc){
	
	$to = $toEmail;
	$subject = $subjectEmail;

	// Create a boundary string.  It needs to be unique (not in the text) so ...
	// We are going to use the sha1 algorithm to generate a 40 character string:
	$sep = sha1(date('r', time()));

	// Define the headers we want passed.
	$headers = "From: ".$from."\r\n"
	
	if($bcc != ""){
		$headers .="Bcc: ".$bcc."\r\n";
	}
	$headers .="X-Mailer: Custom PHP Script";

	// Add in our primary content boundary, and mime type specification:
	$headers .=
		"\r\nContent-Type: multipart/mixed; boundary=\"PHP-mixed-{$sep}\"";

	// Also now prepare our inline image - Also read, encode, split:
	$inline = chunk_split(base64_encode(file_get_contents('xampp-logo.jpg')));

	// Now the body of the message.
$body =<<<EOBODY
--PHP-mixed-{$sep}
Content-Type: multipart/alternative; boundary="PHP-alt-{$sep}"


--PHP-alt-{$sep}
Content-Type: multipart/related; boundary="PHP-related-{$sep}"

--PHP-related-{$sep}
Content-Type: text/html

<html>
<head></head>
<body>
<img src="cid:PHP-CID-{$sep}" />
</body>
</html>


--PHP-related-{$sep}
Content-Type: image/jpeg
Content-Transfer-Encoding: base64
Content-ID: <PHP-CID-{$sep}>

{$inline}
--PHP-related-{$sep}--

--PHP-alt-{$sep}--

--PHP-mixed-{$sep}
Content-Type: image/jpg; name="xampp-logo.jpg"
Content-Transfer-Encoding: base64
Content-Disposition: attachment

{$inline}
--PHP-mixed-{$sep}--
EOBODY;

	// Finally, send the email
	echo mail($to, $subject, $body, $headers);
}

?>
