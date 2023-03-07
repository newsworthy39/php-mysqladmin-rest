# Authentication, signing-workflow

php-mysqladmin-rest comes with a builtin jwt-issuer-service, following standard authorization-workflows. An example that is currently employed is:

> 1. Authenticate against api/authenticate using basic authorization, issuing a JWT-token
> 2. use the JWT-token against the api, using the JWTAuthMiddleware, checking
the issuer against a issuer-trusted-list.

## Example with curl
    TOKEN=$(curl -s -u user:password http://localhost/api/authenticate | jq .jwt)

    curl -H "Authorization: Bearer ${TOKEN}" http://localhost/api/databases

## Issuer-trusted-list

This workflow at the same time, allows to have JWTs authorized elsewehere, by verifying the jwt-signature against the issuers public-key or HMAC.

## builtin user database [file]

A built-in user-database, can be used with the `FileUserDatabase::class` that can be used alongside the resource-provider `FileUserDatabaseProvider::class`.

