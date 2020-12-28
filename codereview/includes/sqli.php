<?php
$fpdata= file_get_contents("fp.txt");
#cho $fpdata;
require_once __DIR__ . '/utility.php';
echo "<b><u><i> Below are the Queries where BindValue was not Used V1.00 </i> </u> </b><br> <br> ";

$queryId = -1;
$data = $queryLines = $fileNames = $finalData = array();

$command = "grep -ri --include=*php 'insert \|select \|update \|delete \|replace ' $codePath | grep -iv 'DbFramework' | grep -iv 'symfony'";
exec($command, $data);

//echo $command;
//print_r($data);

foreach ($data as $line) {
    $queryStarted = false;

    list($file) = explode('.php:', $line);
    $file = realpath("$file.php");

    if (!$file || stristr($file, 'symfony') || stristr($file, 'dbframework')) {
        continue;
    }

    $fileContent = file($file);
    for ($i = 0; $i < count($fileContent); $i++) {
        if (stristr($fileContent[$i], '/*') || stristr($fileContent[$i], '*/')) {
            continue;
        }

        list($codeOutsideComments) = explode('//', $fileContent[$i]);
        $codeOutsideComments = trim($codeOutsideComments);

        if ($codeOutsideComments != '') {
            if (queryStarts($fileContent, $i) && !in_array($codeOutsideComments, $queryLines)) {
                $queryId++;
                $queryStarted = true;
                $queryLines[] = $codeOutsideComments;
                $fileNames[$queryId] = $file;

                $finalData[$queryId][] = $codeOutsideComments;
            } elseif ($queryStarted) {
                $finalData[$queryId][] = $codeOutsideComments;
            }
        }
    }
}

$displayData = [];
#$falsePositive = new FalsePositive();
foreach ($finalData as $queryId => $fd) {
    $str = implode('<br>', $fd);

    if (!stristr($str, 'bindValue') && !stristr($str, 'bindParam')) {
        $codeSnippet = strip_tags(implode('<br>', array_slice($fd, 0, 25)));
        #echo $codeSnippet;
        
        if (strpos($fpdata, md5($codeSnippet)) !== false) {
         echo "sandeep saxena";   
         continue;
        }

        $displayData[] = [
            'file' => $fileNames[$queryId],
            'code' => ($codeSnippet)
        ];

    }
}

if (count($displayData) > 0) {
    renderRecords($displayData);
   #echo md5($displayData);
}

function queryStarts($fileContent, $start) {
    $subFileContent = array_slice($fileContent, $start, 4);

    $data = array(
        'select' => 'where ',
        'update' => 'where ',
        'delete' => 'where ',
        'insert' => array('into ', '('),
        'replace' => array('into ', '(')
    );

    if (trim($subFileContent[0]) != '') {
        $cnt = count($subFileContent);

        foreach ($data as $queryType => $keywords) {
            if (stristr($subFileContent[0], "$queryType ")) {
                for ($i = 0; $i < $cnt; $i++) {
                    if (isset($subFileContent[$i]) && checkForKeywords($subFileContent[$i], $keywords)) {
                        return true;
                    }
                }
            }
        }
    }

    return false;
}

function checkForKeywords($string, $keywords) {
    foreach ((array) $keywords as $keyword) {
        if (!stristr($string, $keyword)) {
            return false;
        }
    }

    return true;
}
