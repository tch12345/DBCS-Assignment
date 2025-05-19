<?php
$serverName = "localhost";
$connectionOptions = [
    "Database" => "dbcs",
    "UID" => "users_php",
    "PWD" => 'Pa$$w0rd', 
   "CharacterSet" => "UTF-8"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);





