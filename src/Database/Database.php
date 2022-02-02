<?php
namespace Platform\Database;

use Assert\Assertion;
use Illuminate\Support\Collection;

class Database
{
    /** @var string */
    protected $name;

    public static $connections = null;

    public function __construct(string $name)
    {
        Assertion::inArray($name, static::$connections);

        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public static function registerConnections(array $connections)
    {
        static::$connections = $connections;
    }

    public static function databases() : Collection
    {
        return collect(static::$connections)->map(function (string $connection) {
            return new static($connection);
        });
    }
}
