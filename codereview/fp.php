<?php
$data=$_GET['param'];
 
 #echo $data;
 #$data=base64_decode($data);
 echo "data is".$data;
 #$hash = md5($data);
 $myfile = file_put_contents('fp.txt', $data.PHP_EOL , FILE_APPEND | LOCK_EX);


