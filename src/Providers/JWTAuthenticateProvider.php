<?php

namespace redcathedral\phpMySQLAdminrest\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Dotenv\Dotenv;

class JWTAuthenticateProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    private $privkey, $pubkey, $issuerdomain, $dotenv;

    public function __construct(Dotenv $env)
    {
        $this->dotenv = $env;
    }

    public function boot(): void
    {
        # TODO Make JWT into a Facade, hiding the key stuff.
        $this->dotenv->required(['JWT_PUB_KEY', 'JWT_PRIV_KEY','JWT_ISSUER_DOMAIN'])->notEmpty();
        $this->pubkey = file_get_contents($_ENV['JWT_PUB_KEY']);
        $this->privkey = file_get_contents($_ENV['JWT_PRIV_KEY']);
        $this->issuerdomain = $_ENV['JWT_ISSUER_DOMAIN'];
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
            ->addArgument($this->privkey)->addArgument($this->pubkey)->addArgument($this->issuerdomain);
    }
}
