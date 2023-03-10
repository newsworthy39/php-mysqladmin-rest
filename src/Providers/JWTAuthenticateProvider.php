<?php

namespace redcathedral\phpMySQLAdminrest\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Dotenv\Dotenv;
use RuntimeException;

class JWTAuthenticateProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    private $privkey;
    private $pubkey;
    private $issuerdomain;
    private $dotenv;

    public function __construct(Dotenv $env)
    {
        $this->dotenv = $env;
    }

    public function boot(): void
    {
        $this->dotenv->required(
            ['JWT_PUB_KEY',
            'JWT_PRIV_KEY',
            'JWT_ISSUER']
        )->notEmpty();
        if (!(file_exists($_ENV['JWT_PUB_KEY']) && file_exists($_ENV['JWT_PRIV_KEY']))) {
            throw new RuntimeException("JWT files are missing. Please regenerate them, " .
                                       "using `composer generatejwtkeys` or run devops/build.sh");
        }
        $this->pubkey = file_get_contents($_ENV['JWT_PUB_KEY']);
        $this->privkey = file_get_contents($_ENV['JWT_PRIV_KEY']);
        $this->issuerdomain = $_ENV['JWT_ISSUER'];
    }

    public function provides(string $id): bool
    {
        $services = array(
            \redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class
        );

        return in_array($id, $services);
    }

    public function register(): void
    {
        $this->getContainer()->add(\redcathedral\phpMySQLAdminrest\Facades\JWTFacade::class)
            ->addArgument($this->privkey)
            ->addArgument($this->pubkey)
            ->addArgument($this->issuerdomain);
    }
}
