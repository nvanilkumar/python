<?php 
//echo phpinfo();exit;
   //Connecting to Redis server on localhost 

 
   $redis = new Redis(); 
   $status=$redis->connect('139.59.73.111', 6379); 
    $redis->auth('ces88in##redis');
   echo "<pre>";
   echo "Connection to server sucessfully"; 
   //check whether server is running or not 
   echo "Server is running: ".$redis->ping(); 
  


 // Get the stored keys and print it 
   $arList = $redis->keys("*"); 
   echo "Stored keys in redis:: " ;
   print_r($arList); 
?>