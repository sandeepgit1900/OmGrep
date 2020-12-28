<?php
require_once __DIR__ . '/utility.php';
#require_once __DIR__ . '/../FalsePositive.php';
$fpdata= file_get_contents("fp.txt");

echo "<br><b><u>Below are the instance where functions like exec, passthru are used  </u></b><br><br>";

$data = $finalData = array();
$vulnerableFuncs = array('shell_exec', 'exec', 'passthru', 'eval','system','proc_open','popen','curl_multi_exec','pcntl_exec');

$command = "grep -ri --include=*php '" . implode('(\|', $vulnerableFuncs) . "(' $codePath | grep -iv 'symfony'| grep -iv 'smarty' | grep -iv curl | grep -v '/vendor/' | grep -iv 'thrift' | grep -vi 'swift' | grep -vi 'function ' | grep '\$'";
exec($command, $data);


foreach ($data as $fileData) {
    list($file, $code) = explode('.php:', $fileData);
    $file = realpath("$file.php");

    if ($file && !stristr($file, 'symfony') && !stristr($file, 'smarty')) {
        list($codeOutsideComments) = explode('//', $code);

	foreach ($vulnerableFuncs as $function) {
            $vars = explode("$function(", $codeOutsideComments);

            if (isset($vars[1]) && stristr($vars[1], '$')) {
                $finalData[$file][] = $code;
                break;
            }
        }


    }
}

$displayData = [];
#$falsePositive = new FalsePositive();
foreach ($finalData as $file => $code) {
    foreach ($code as $line) {
        $codesni = $file.$line;
        #$isfalse = $falsePositive->isFalsePositive($codesni);
         if (strpos($fpdata, md5($codesni)) !== false){
         continue;
           }
             $displayData[] = [
            'file' => $file,
            'code' => $codesni,
            'display' => $line
        ];
       }
    }


if (count($displayData) > 0) {
    renderRecordsCommand($displayData);
}

