<?php
//shell_exec('cd /apps/manafer/current/web/');
$ab="retire --path=/apps/demo/demo/om10/DVWA-master --outputpath=/home/daemon/ab2.txt";
shell_exec($ab);
$res = file_get_contents('/home/daemon/ab2.txt');
//exec('retire --path=/apps/manager/current/web', $res);
echo str_ireplace("\n", '<br>', $res);


