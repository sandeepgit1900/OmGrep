<?php
chdir('/tmp/NodeJsScan');
shell_exec('python NodeJsScan.py -d /tmp/cr/');
//sleep(120);
echo file_get_Contents('Report.html');
//shell_exec('cd /tmp/NodeJsScan/; cp Report.html /apps/manager/current/web/demo/demo/om/');
//header("Location: Report.html");



