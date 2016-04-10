<?php
$name = "selfies/selfie-".date('Y-m-d_H-i-s').".jpg";
shell_exec('/var/www/html/picture.sh /var/www/html/'.$name);
echo json_encode($name);
