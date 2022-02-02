<?php

namespace Platform\Bus\Commands;

use Platform\Database\Contracts\RepositoryContract;

class DeleteModelsCommand
{
    public $ids;

    public $repository;

    public function __construct(array $ids, RepositoryContract $repository)
    {
        $this->ids = $ids;
        $this->repository = $repository;
    }
}
