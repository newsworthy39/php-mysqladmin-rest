<?php

namespace redcathedral\phpMySQLAdminrest\Controller;

use redcathedral\phpMySQLAdminrest\MySQLAdmin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

/**
 * @brief databasecontroller
 */
class DatabaseController {

    private $mysqladmin;

    public function __construct(MySQLAdmin $mysqladmin) {
        $this->mysqladmin = $mysqladmin;
    }

    /**
     * @brief ListDatabasesAsJson
     * @description Outputs the databases found at the other end, w/o filters.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listDatabases(ServerRequestInterface $request, array $args): ResponseInterface {
        $response = new HtmlResponse(json_encode($this->mysqladmin->listDatabases()));
        return $response->withAddedHeader('content-type', 'application/json')->withStatus(200);
    }

    /**
     * @brief createDatabase
     * @description Creates a database
     */
    public function createDatabase(ServerRequestInterface $request, array $args): ResponseInterface {
        $data = $request->getBody();
        $response = new HtmlResponse(json_encode($this->mysqladmin->listDatabases()));
        return $response->withAddedHeader('content-type', 'application/json')->withStatus(200);
    }
}