<?php

namespace src\Repositories;

use PDO;
use PDOException;
require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

class Repository
{
	protected PDO $pdo;
	private string $hostname;
	private string $username;
	private string $databaseName;
	private string $databasePassword;
	private string $charset;
	private string $env;


	 public function __construct()
    {
        // Load environment variables from .env file
        $dotenv = Dotenv::createImmutable(realpath(__DIR__ . '/../../'));
        $dotenv->load();

        // Get environment and appropriate DB name
        $env = $_ENV['APP_ENV'] ?? 'dev';
        $dbName = $env === 'test' ? $_ENV['DB_NAME_TEST'] : $_ENV['DB_NAME'];

        // Load all DB credentials
        $host = $_ENV['DB_HOSTNAME'];
        $user = $_ENV['DB_USERNAME'];
        $pass = $_ENV['DB_PASS'];
        $charset = $_ENV['DB_CHARSET'];

        // Set DSN and options
        $dsn = "mysql:host=$host;dbname=$dbName;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Create PDO instance
        try {
            $this->pdo = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            throw new PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

	public function db()
	{
		return $this->pdo;
	}
}
