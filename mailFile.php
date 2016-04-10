<?php
function mail_attachment($payload, $path, $mailto, $from_mail, $from_name, $replyto, $subject, $message) {

    $files = array();

    if (is_array($payload)) {
        $files = $payload;
    } else {
        $files[] = $payload;
    }

    $headers = "From: $from_name<$from_mail>";

    // boundary
    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    // headers for attachment
    $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

    // multipart boundary
    $message = "This is a multi-part message in MIME format.\n\n" . "--{$mime_boundary}\n" . "Content-Type: text/plain; charset=\"iso-8859-1\"\n" . "Content-Transfer-Encoding: 7bit\n\n" . $message . "\n\n";
    $message .= "--{$mime_boundary}\n";

    // preparing attachments
    for($x=0;$x<count($files);$x++){
var_dump($files[$x]);
        $file = fopen($files[$x],"rb");
        $data = fread($file,filesize($files[$x]));
        fclose($file);
        $data = chunk_split(base64_encode($data));
        $message .= "Content-Type: {\"application/octet-stream\"};\n" . " name=\"$files[$x]\"\n" .
        "Content-Disposition: attachment;\n" . " filename=\"$files[$x]\"\n" .
        "Content-Transfer-Encoding: base64\n\n" . $data . "\n\n";
        $message .= "--{$mime_boundary}\n";
    }

    // send
    if (mail($mailto, $subject, $message, $headers)) {
        // echo "mail send ... OK\n"; // or use booleans here
  } else {
        // echo "mail send ... ERROR!\n";
    }
}

/*
$my_path = '/var/www/html/selfies/';
$my_file = $my_path . 'selfie-44.jpg';

$recipients = "fredrik.safsten@gmail.com";
$my_name = "Nils Heffe";
$my_mail = "noreply@wakakuu.com";
$my_replyto = "no-mail@wakakuu.com";
$my_subject = "Subject";
$my_message = "Hej,\r\nBlajj /Nils Heffe";
mail_attachment($my_file, $my_path, "$recipients", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);
*/

?>

