<?php
        $uname="phpmyadmin";
        $pass="febkAH3wuQHZ";
        $servername="localhost";
        $dbname="bandhan";


        $con = mysqli_connect("localhost",$uname,$pass,$dbname);
        if (mysqli_connect_errno()){
            //echo "Failed to connect to MySQL: " . mysqli_connect_error();
            //response(NULL, NULL, 200,"0 records");  
                $data1 = array("response_code" => 200, "response_desc" => "Check DB Credentials");   
                $json_response = json_encode($data1);
                echo $json_response;        
            die();
            }
?>
        