<?php
 class scan{
public $gitUrl;
public $check;
public function disp (){
$this->gitUrl=$this->gitUrl+1;
echo $this->gitUrl;


}
}

$sandeep= new scan;
$sandeep->gitUrl=19;
echo $sandeep->gitUrl;
$sandeep->disp();
