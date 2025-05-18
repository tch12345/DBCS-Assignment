<?php
$serverName = "localhost";
$connectionOptions = [
    "Database" => "dbcs",
    "CharacterSet" => "UTF-8",
    "UID" => "admin",
    "PWD" => 'Pa$$W0rd'
];

$conn = sqlsrv_connect($serverName, $connectionOptions);


