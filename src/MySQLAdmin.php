<?php

namespace redcathedral\phpMySQLAdminrest;

use mysqli;

class MySQLAdmin
{
    private $mysql_dbh;
    public function __construct(mysqli $mysql)
    {
        $this->mysql_dbh = $mysql;
    }

    public function __destruct()
    {
        if ($this->mysql_dbh) {
            $this->close();
        }
    }

    public function createDatabase(string $dbname): MySQLAdmin
    {
        $this->mysql_dbh->query(sprintf("CREATE DATABASE `%s`", $dbname));
        return $this;
    }

    public function hasDatabase(string $dbname): bool
    {
        $dbsFound = 0;
        $result = $this->mysql_dbh->query(
            sprintf("select 1 from information_schema.SCHEMATA WHERE SCHEMA_NAME = '%s'", $dbname)
        );

        if ($result) {
            $dbsFound = $result->num_rows;
            $result->free_result();
        }

        return $dbsFound == 1;
    }

    public function deleteDatabase(string $dbname): MySQLAdmin
    {
        $this->mysql_dbh->query(sprintf("DROP DATABASE `%s`", $dbname));
        return $this;
    }

    public function listDatabases(): array
    {

        $dbnames = array();
        $result = $this->mysql_dbh->query(sprintf("select SCHEMA_NAME from information_schema.SCHEMATA"));

        if ($result) {
            while (false != ( $row = $result->fetch_array(MYSQLI_ASSOC))) {
                array_push($dbnames, $row['SCHEMA_NAME']);
            }

            $result->free_result();
        }

        return $dbnames;
    }

    public function close()
    {
        $this->mysql_dbh->close();
        $this->mysql_dbh = null;
    }
}
