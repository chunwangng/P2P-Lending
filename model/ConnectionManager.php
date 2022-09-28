<?php

class ConnectionManager
{
    public function getConnection()
    {
        $servername = 'localhost';
        $dbname = 'dbea_project';
        $username = 'root';

        $password = '';
        $port = 3306;
        
        /*
        $password = 'root';
        $port = 8889;
        */

        $url = "mysql:host=$servername;dbname=$dbname;port=$port";

        $conn = new PDO($url, $username, $password);

        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn;
    }
}
