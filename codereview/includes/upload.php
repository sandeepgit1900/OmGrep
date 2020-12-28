<?php

require_once __DIR__ . '/utility.php';
$fpdata= file_get_contents("fp.txt");

$uploadLibrary= file_get_contents("upload.txt");
echo "<br><br><b><u>Below are the instance where files are uploaded without using NCUPLOADER </u></b><br>";

$files = array();
$command = "grep -wr \"file\" $codePath | grep -i '<input ' | grep -iw 'type' | grep -iv 'symfony' | grep -iv '/test/' | grep -v '\.js' | gawk 'BEGIN{FS=\":\"} ; {print $1}'";
exec($command, $files);

$displayData=[];
#$falsePositive = new FalsePositive();
$files = array_values(array_unique($files));
foreach ($files as $file) {
    $file = realpath($file);

    if ($file && !stristr($file, 'symfony')) {
        $fileContent = file_get_contents($file);

        if (!stristr($fileContent, $uploadLibrary)) {
             if(strpos($fpdata, md5($file)) !== false) {
              continue;
              }
                  $displayData[] = [
                      'file' => $file,
                      'code' => $file,
                  ];
              }
        }
    }

if (count($displayData) > 0) {
    renderRecordsFile($displayData);
}


