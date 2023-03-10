<?php

namespace redcathedral\phpMySQLAdminrest\Providers;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Dotenv\Dotenv;
use mysqli;

class MySQLConfigurationBootableProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    private $host;
    private $user;
    private $pass;
    private $dotenv;

    public function __construct(Dotenv $env)
    {
        $this->dotenv = $env;
    }

    public function boot(): void
    {
        $this->dotenv->required(
            ['DATABASE_HOST',
             'DATABASE_USER',
            'DATABASE_PASS']
        )->notEmpty();

        $this->host = $_ENV['DATABASE_HOST'];
        $this->user = $_ENV['DATABASE_USER'];
        $this->pass = $_ENV['DATABASE_PASS'];
    }

    public function provides(string $id): bool
    {
        $services = array(
            mysqli::class
        );

        return in_array($id, $services);
    }

    public function register(): void
    {
        $this->getContainer()->add(mysqli::class)
            ->addArgument($this->host)
            ->addArgument($this->user)
            ->addArgument($this->pass);
    }
}
