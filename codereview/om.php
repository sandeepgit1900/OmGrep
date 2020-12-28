<!DOCTYPE html>
<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8">

<meta charset="utf-8">
<title>OM</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta content="" name="description">
<meta content="" name="author">
<!-- BEGIN PLUGIN CSS -->
<!-- END PLUGIN CSS -->
<!-- BEGIN CSS TEMPLATE -->
<link href="css/style.css" rel="stylesheet" type="text/css">
<link href="css/responsive.css" rel="stylesheet" type="text/css">
<link href="css/custom-icon-set.css" rel="stylesheet" type="text/css">
<!-- END CSS TEMPLATE -->

<script type="text/javascript" src="js/jquery-1.js"></script>
<script type="text/javascript" src="js/falsePositive.js"></script>

</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="">

<div class="page-content loginCont">

<div class="appId" style="padding:20px 0px 0px 20px;">
<?php
ini_set('display_errors', 'On');
ini_set('memory_limit', '1G');
ini_set('max_execution_time', 0);

#if (!isset($_POST['check'])) {
#	header('Location: index.php');
#	exit(0);
#}

$upload = $_POST["upload"];
$gitUrl =  $_POST["gitUrl"];
file_put_contents('upload.txt', $upload.PHP_EOL , FILE_APPEND | LOCK_EX);
$allowedChecks = array('sqli', 'command', 'cookie', 'upload','js','ssrf');
$checksToBeFired = $allowedChecks;
$cmd="git clone ";
$cmd .=$gitUrl;
$cmd .=" /tmp/codebase";
echo $cmd;

if($gitUrl!=''){
echo "i reacher here";
$out=shell_exec($cmd);
echo $out;



}



#$currDir = dirname(__FILE__);
//shell_exec("rm -rf /home/daemon");

//original$codePath= '/home/daemon/';
$codePath= '/tmp/codebase';
//shell_exec("cd /home/daemon;git clone http://gitdeployer:gitdeployer@$gitUrl");
//to be changedchdir('/home/daemon/JsMain/web'); //to be changed
#chdir('/tmp/DVWA-master'); 
#chdir($currDir);

echo "<b> Code Path : $codePath </b>" ;
echo "<br> <br>";


$finalOutput = '';
foreach ($checksToBeFired as $checkToBeFired) {
	ob_start();
	require_once "./includes/$checkToBeFired.php";
	$finalOutput .= ob_get_contents();
	ob_clean();
}


	echo $finalOutput;



//shell_exec("cd /tmp;rm -rf cr");

