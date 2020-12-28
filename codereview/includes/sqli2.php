<?php
//echo "i reached here";
 include_once('PDOParser.class.php');


   $obj = new PDOParser;
   $obj->parseFiles(" /tmp/sandeep");

die('END');


