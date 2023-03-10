<?php

namespace redcathedral\phpMySQLAdminrest\Controller;

use redcathedral\phpMySQLAdminrest\MySQLAdmin;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\HtmlResponse;
use League\Route\Http\Exception\BadRequestException;
use League\Route\Http\Exception\PreconditionFailedException;
use redcathedral\phpMySQLAdminrest\Exception\ServerErrorException;
use PhpParser\Node\Expr\Cast\Object_;

/**
 * Databasecontroller
 *
 * @category Controller
 * @author   Newsworthy39 <newsworthy39@github.com>
 * @license  BSD-3 https://spdx.org/licenses/BSD-3-Clause.html
 */
class DatabaseController
{
    private $mysqladmin;

    /**
     * @brief construct
     * @param MySQLAdmin
     */
    public function __construct(MySQLAdmin $mysqladmin)
    {
        $this->mysqladmin = $mysqladmin;
    }

    /**
     * @brief       ListDatabasesAsJson
     * @description Outputs the databases found at the other end, w/o filters.
     *
     * @param  \Psr\Http\Message\ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function listDatabases(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $response = new HtmlResponse(json_encode($this->mysqladmin->listDatabases()));
        return $response->withAddedHeader('content-type', 'application/json')->withStatus(200);
    }

    /**
     * @CreateDatabase
     * @description Creates a database
     * @body        { name: nameofdatabase }
     */
    public function createDatabase(ServerRequestInterface $request, array $args): ResponseInterface
    {
        $data = (object) (json_decode($request->getBody()));
        if (!isset($data->name)) {
            throw new BadRequestException("Format: POST /api/databases body: { name: nameofdatabase }");
        }
        $name = trim($data->name);

        // Check if database allready exists
        if ($this->mysqladmin->hasDatabase(trim($name))) {
            throw new PreconditionFailedException(sprintf("Database %s exists", $name));
        }

        $result = $this->mysqladmin->createDatabase(trim($name));
        if (!$result) {
            throw new ServerErrorException();
        }

        $response = new HtmlResponse(json_encode(array('status_code' => 200, "reason" => "created")));
        return $response->withAddedHeader('content-type', 'application/json')->withStatus(200);
    }
    /**
     * @brief       deleteDatabase
     * @description delete a database
     * @body        { name: nameofdatabase }
     */
    public function deleteDatabase(ServerRequestInterface $request, array $args): ResponseInterface
    {
        // Check if database allready exists
        $name = trim($args['name']);

        // Check if database allready exists
        if ($this->mysqladmin->hasDatabase(trim($name))) {
            $result = $this->mysqladmin->deleteDatabase($name);
            if ($result) {
                $response = new HtmlResponse(json_encode(array('status_code' => 200, "reason" => "deleted")));
                return $response->withAddedHeader('content-type', 'application/json')->withStatus(200);
            }
        }

        $response = new HtmlResponse(json_encode(array('status_code' => 404, "reason" =>
                sprintf("Database %s doesn't not exists", $name))));

        return $response->withAddedHeader('content-type', 'application/json')->withStatus(404);
    }
}
