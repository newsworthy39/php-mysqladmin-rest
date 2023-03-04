<?php

namespace redcathedral\phpmysqladminrest;

use mysqli;

class mysqladmin
{
    private $mysql_dbh;
    public function __construct(mysqli $mysql)
    {
        $this->mysql_dbh = $mysql;
    }

    public function __destruct()
    {
        if($this->mysql_dbh)
            $this->close();
    }

    public function createDatabase(string $dbname) : mysqladmin
    {
        $this->mysql_dbh->query(sprintf("CREATE DATABASE `%s`", $dbname));
        return $this;
    }

    public function hasDatabase(string $dbname): bool
    {
        $dbsFound = 0;
        $result = $this->mysql_dbh->query(sprintf("select 1 from information_schema.SCHEMATA WHERE SCHEMA_NAME = '%s'", $dbname));
        
        if ($result) {
            $dbsFound = $result->num_rows;
            $result->free_result();
        }

        return $dbsFound == 1;
    }

    public function deleteDatabase(string $dbname) : mysqladmin
    {
        $this->mysql_dbh->query(sprintf("DROP DATABASE `%s`", $dbname));
        return $this;
    }

    public function close()
    {
        $this->mysql_dbh->close();
        $this->mysql_dbh = null;
    }
}
