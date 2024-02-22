<?php

namespace App\Config;

class ConfigEnv
{
    public $appName;
    public $appEnv;
    public $basePath;
    public $dbHost;
    public $dbDatabase;
    public $dbUsername;
    public $dbPassword;
    public $secretKey;

    public function __construct()
    {
        $this->appName = $_ENV['APP_NAME'] ?? 'General Services';
        $this->appEnv = $_ENV['APP_ENV'] ?? 'local';
        $this->basePath = $_ENV['BASE_PATH'] ?? '/general_services2.0';
        $this->dbHost = $_ENV['DB_HOST'] ?? 'localhost';
        $this->dbDatabase = $_ENV['DB_DATABASE'] ?? 'general_services';
        $this->dbUsername = $_ENV['DB_USERNAME'] ?? 'root';
        $this->dbPassword = $_ENV['DB_PASSWORD'] ?? '';
        $this->secretKey = $_ENV['SECRET_KEY'] ?? '4d1a9c7ed0c6d91702f9';
    }
}
