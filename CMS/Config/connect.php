<?php
$serverName = "localhost";
$connectionOptions = [
    "Database" => "dbcs",
    "CharacterSet" => "UTF-8",
    #"UID" => "test_user",
    #"PWD" => "12345"
];

$conn = sqlsrv_connect($serverName, $connectionOptions);


