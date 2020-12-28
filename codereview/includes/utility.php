<?php

function renderRecords(array $data) {
    echo "<table>";
    
    foreach ($data as $val) {
        echo "<tr><td><b><i>File: $val[file]</i></b></td></tr>";
        $md = md5($val['code']);
        echo <<<EOD
        <tr>
            <td style="width:78%;word-wrap:break-word;padding-bottom:49px">Code: <br/><div style="width:500px; word-wrap:break-word;">$val[code]</div></td>
            <td><button onclick="window.location = 'http://vaserver.infoedge.com/om10/fp.php?param= + $md'">Supress</button></td>
        </tr>
EOD;
    }
    
    echo "</table>";
}

function renderRecordsCommand(array $data) {
    echo "<table>";

    foreach ($data as $val) {
        echo "<tr><td><b><i>File: $val[file]</i></b></td></tr>";
        $md = md5($val['code']);
        echo <<<EOD
        <tr>
            <td style="width:78%;word-wrap:break-word;padding-bottom:49px">Code: <br/><div style="width:500px; word-wrap:break-word;">$val[display]</div></td>
            <td><button onclick="window.location = 'http://vaserver.infoedge.com/om10/fp.php?param= + $md'">Supress</button></td> 
       </tr>
EOD;
    }

    echo "</table>";
}

function renderRecordsFile(array $data) {
    echo "<table>";

    foreach ($data as $val) {
        $md = md5($val['code']);
        echo <<<EOD
        <tr>
            <td style="width:78%;word-wrap:break-word;padding-bottom:49px"><b><i>File: <br/><div style="width:500px; word-wrap:break-word;">$val[file]</div></i></b></td>
        <td><button onclick="window.location = 'http://vaserver.infoedge.com/om10/fp.php?param= + $md'">Supress</button></td>
 
       </tr>
EOD;
    }

    echo "</table>";
}


