<?php
$serverName = "localhost";
$connectionOptions = [
    "Database" => "dbcs",
    "UID" => "users_php",
    "PWD" => 'Pa$$w0rd', 
   "CharacterSet" => "UTF-8"
];
#current user is admin
$conn = sqlsrv_connect($serverName, $connectionOptions);





