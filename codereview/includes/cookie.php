<?php

echo "<br><br><b><u>Below are the instance where cookies are set, httpOnly flag to be checked </u></b><br><br>";

$data = $finalData = array();

$command = "grep -ri --include=*php 'setcookie(' $codePath  | grep -iv 'smarty' | grep -iv 'symfony'";
exec($command, $data);

$finalData = array();
foreach ($data as $line) {
    list($file, $code) = explode('.php:', $line);
    $file = realpath("$file.php");

    if (!$file || stristr($file, 'symfony') || stristr($file, 'smarty')) {
        continue;
    }

    $code = strtolower($code);
    list($codeOutsideComments) = explode('//', $code);
    list($tmp, $values) = explode('setcookie(', $codeOutsideComments);


    if (isset($tmp) && isset($values)) {
        $cookieVals = explode(',', $values);

        if (isset($cookieVals[6]) && !stristr($cookieVals[6], 'true')) {
            $finalData[$file][] = $code;
        }
    }
}

foreach ($finalData as $file => $code) {
    echo "<b><i>File: $file<br></b></i>";

    foreach ($code as $line) {
        echo "$line<br>";
    }

    echo "<br><br>";
}

