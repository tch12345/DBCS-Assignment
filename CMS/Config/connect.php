<?php
$serverName = "localhost";
$connectionOptions = [
    "Database" => "dbcs",
    "CharacterSet" => "UTF-8",
    "UID" => "admin",
    "PWD" => 'Pa$$W0rd'
];
#current user is admin
$conn = sqlsrv_connect($serverName, $connectionOptions);


