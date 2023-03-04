<?php

namespace redcathedral\phpMySQLAdminrest;

use League\Container\ServiceProvider\AbstractServiceProvider;
use League\Container\ServiceProvider\BootableServiceProviderInterface;
use Dotenv\Dotenv;
use mysqli;

class MySQLConfigurationBootableProvider extends AbstractServiceProvider implements BootableServiceProviderInterface
{
    private $host, $user, $pass, $dotenv;

    public function __construct()
    {
        $this->dotenv = Dotenv::createImmutable(dirname(__DIR__, 1));
    }

    public function boot(): void
    {
        try {
            $this->dotenv->load();

            $this->dotenv->required(['DATABASE_HOST', 'DATABASE_USER', 'DATABASE_PASS']);

            $this->host = $_ENV['DATABASE_HOST'];
            $this->user = $_ENV['DATABASE_USER'];
            $this->pass = $_ENV['DATABASE_PASS'];
        } catch (\Dotenv\Exception\InvalidPathException | \Dotenv\Exception\InvalidEncodingException | \Dotenv\Exception\InvalidFileException $ex) {
            print("The required .env-file, is missing.");
        }
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
        $this->getContainer()->add(mysqli::class)->addArgument($this->host)->addArgument($this->user)->addArgument($this->pass);
    }
}
