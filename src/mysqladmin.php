<?php

namespace redcathedral\phpmysqladminrest;

use mysqli;

class mysqladmin
{

    private $mysql_dbh;
    public function __construct()
    {
        $this->mysql_dbh = mysqli_connect('localhost', 'mysqladmin', 'superadmin');
    }

    public function __destruct()
    {
        $this->close();
    }

    public function createDatabase(string $dbname)
    {
        $this->mysql_dbh->query(sprintf("CREATE DATABASE `%s`", $dbname));
    }

    public function hasDatabase(string $dbname): bool
    {
        $dbsFound = 0;
        $result = $this->mysql_dbh->query(sprintf("select 1 from information_schema.SCHEMATA WHERE SCHEMA_NAME = '%s'", $dbname));
        
        if ($result) {
            $dbsFound = 1;
            $result->free_result();
        }

        return $dbsFound === 1;
    }

    public function deleteDatabase(string $dbname)
    {
        $this->mysql_dbh->query(sprintf("DROP DATABASE `%s`", $dbname));
    }

    public function close()
    {
        $this->mysql_dbh->close();
    }
}
