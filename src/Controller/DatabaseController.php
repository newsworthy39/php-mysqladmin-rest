<?php

namespace redcathedral\phpMySQLAdminrest\Controller;

use redcathedral\phpMySQLAdminrest\MySQLAdmin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;

class DatabaseController {

    private $mysqladmin;

    public function __construct(MySQLAdmin $mysqladmin) {
        $this->mysqladmin = $mysqladmin;
    }

    /**
     * Controller.
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listDatabasesAsJson(ServerRequestInterface $request, array $args): ResponseInterface {
        $response = new HtmlResponse(json_encode($this->mysqladmin->listDatabases()));
        return $response->withAddedHeader('content-type', 'application/json')->withStatus(200);
    }

}