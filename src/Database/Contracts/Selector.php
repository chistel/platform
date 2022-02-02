<?php
namespace Platform\Database\Contracts;

use Platform\Database\Database;

interface Selector
{
    /**
     * The database connection currently in use.
     *
     * @return mixed
     */
    public function current(): Database;

    /**
     * Set the given database as the default connection for the request/command.
     *
     * @param Database $database
     * @return mixed
     */
    public function select(Database $database);
}
