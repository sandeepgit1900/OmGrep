<?php

#require_once __DIR__ . "/Database.php";
die;
class FalsePositive
{

    public function isFalsePositive($codeSnippet) {
        $data= file_get_contents("fp.txt");

        #$conn = Database::getDBConnection();
        $hash = md5($codeSnippet);
        #$query = $conn->prepare("select * from FALSE_POSITIVE where hash = ?");
        #$query->execute(array($hash));

         if (strpos($data, $codeSnippet) !== false) {
         return true;


         }
        return false;
    }

    public function markFalsePositive($codeSnippet) {
        #$conn = Database::getDBConnection();
        echo "I reacherd here";
        $hash = md5($codeSnippet);
        #$sth = $conn->prepare("insert into FALSE_POSITIVE(hash, data, added) values (:hash, :code, :added)");
        #$sth->bindValue('hash', $hash, PDO::PARAM_STR);
        #$sth->bindValue('code', $codeSnippet, PDO::PARAM_STR);
        #$sth->bindValue('added', date('Y-m-d H:i:s'), PDO::PARAM_STR);
        #return $sth->execute();
        $data = $hash;
        $txt = "user id date";
        $myfile = file_put_contents('/tmp/fp.txt', $txt.PHP_EOL , FILE_APPEND | LOCK_EX);    
}

}

