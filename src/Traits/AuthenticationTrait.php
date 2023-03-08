<?php

namespace redcathedral\phpMySQLAdminrest\Traits;

use Psr\Http\Message\ServerRequestInterface;

trait AuthenticationTrait
{
    /** 
     * Get header Authorization
     * */
    function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        return $headers;
    }

    /** 
     * Get header Authorization
     * */
    function getAuthorizationHeaderFromRequest(ServerRequestInterface $request)
    {
        $headers = $request->getHeader('Authorization');
        if (!empty($headers)) {
            $headers = trim($headers[0]);
        }
        return $headers;
    }

    /**
     * get access token from header
     * @param ServerRequestInterface $request the request
     * */
    function getBearerToken(ServerRequestInterface $request)
    {
        $headers = $this->getAuthorizationHeader();
        if (empty($headers)) {
            $headers = $this->getAuthorizationHeaderFromRequest($request);
        }
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

     /**
     * get basic access token from header
     * @param ServerRequestInterface $request the request
     * */
    function getBasicToken(ServerRequestInterface $request)
    {
        $headers = $this->getAuthorizationHeader();
        if (empty($headers)) {
            $headers = $this->getAuthorizationHeaderFromRequest($request);
        }
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Basic\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }
}
