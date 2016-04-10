<?php
include('mailFile.php');
//$name = "selfies/selfie-".date('Y-m-d_H-i-s').".jpg";
$name = $_GET['name'];
//shell_exec('/var/www/html/picture.sh /var/www/html/'.$name);
//echo json_encode($name);

$my_path = '/var/www/html/';
$my_file = $my_path . $name;

$recipients = "fredrik.safsten@gmail.com";
$my_name = "Nils Heffe";
$my_mail = "noreply@wakakuu.com";
$my_replyto = "no-mail@wakakuu.com";
$my_subject = "Subject";
$my_message = "Hej,\r\nBlajj /Nils Heffe";
// Somehow destroys the element output if an email is sent.. dunno why.
mail_attachment($my_file, $my_path, "$recipients", $my_mail, $my_name, $my_replyto, $my_subject, $my_message);

die;

echo '{"employees":[
    {"firstName":"John", "lastName":"Doe"},
    {"firstName":"Anna", "lastName":"Smith"},
    {"firstName":"Peter", "lastName":"Jones"}
]}';
