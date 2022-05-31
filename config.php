<?php
$host="localhost";
$password="";
$user="root";
$db_name="user_form";

try{
$connection=new PDO("mysql:host=$host;dbname=$db_name",$user,$password);
}catch(PDOException $error){
 echo "not connected: ".$error->getMessage();
}
