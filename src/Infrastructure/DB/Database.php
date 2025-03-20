<?php

declare(strict_types=1);

namespace App\Infrastructure\DB;


use MongoDB\Client;
use MongoDB\Database as Mongo;
use PDO;
use Dotenv\Dotenv;

class Database
{
    private static ?Mongo $mongoDb = null;
    private static ?PDO $pgDb = null;

    public static function getMongoConnection(): Mongo
    {
        if (self::$mongoDb === null) {
            $dotenv = Dotenv::createImmutable(__DIR__. '/../../../');
            $dotenv->load();

            $client = new Client($_ENV['MONGO_URI']);
            self::$mongoDb = $client->selectDatabase($_ENV['MONGO_DB']);
        }

        return self::$mongoDb;
    }

    public static function getPostgresConnection(): PDO
    {
        if (self::$pgDb === null) {
            $dotenv = Dotenv::createImmutable(__DIR__. '/../../../');
            $dotenv->load();

            $dsn = "pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DB']};user={$_ENV['PG_USER']};password={$_ENV['PG_PASSWORD']}";
            self::$pgDb = new PDO($dsn);
            self::$pgDb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return self::$pgDb;
    }
}