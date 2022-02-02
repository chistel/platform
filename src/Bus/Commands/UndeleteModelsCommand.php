<?php
namespace Platform\Bus\Commands;


use Platform\Database\Contracts\RepositoryContract;

class UndeleteModelsCommand
{
    public $selected;

    public $repository;

    public function __construct(array $selected, RepositoryContract $repository)
    {
        $this->selected = $selected;
        $this->repository = $repository;
    }
}
