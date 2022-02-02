<?php
namespace Platform\Bus\Commands;


use Platform\Database\Contracts\RepositoryContract;

class CopyModelsCommand
{
    /**
     * @var array
     */
    public $ids;

    /**
     * @var RepositoryContract
     */
    public $repository;

    /**
     * @param array $ids
     * @param RepositoryContract $repository
     */
    public function __construct(array $ids, RepositoryContract $repository)
    {
        $this->ids = $ids;
        $this->repository = $repository;
    }
}
