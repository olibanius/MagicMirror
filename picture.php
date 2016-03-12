<?php
echo '{"employees":[
    {"firstName":"John", "lastName":"Doe"},
    {"firstName":"Anna", "lastName":"Smith"},
    {"firstName":"Peter", "lastName":"Jones"}
]}';
die;
echo 'vad';
$name = $_GET['name'];
$x = shell_exec('/usr/bin/fswebcam '.$name);
var_dump('hej', $x);
echo "<img src=\"$name\">";
echo "<img src=\"test.jpg">";
