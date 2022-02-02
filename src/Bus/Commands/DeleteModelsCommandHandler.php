<?php
namespace Platform\Bus\Commands;


use Illuminate\Contracts\Container\BindingResolutionException;
use Platform\Database\Traits\EventDispatcher;

class DeleteModelsCommandHandler
{
    use EventDispatcher;

    /**
     * Deletes the selected ids
     *
     * @param DeleteModelsCommand $command
     * @throws BindingResolutionException
     */
    public function handle(DeleteModelsCommand $command)
    {
        $ids = array_unique($command->ids);

        foreach ($ids as $id) {
            $model = $command->repository->getById($id);

            if ($model) {
                $command->repository->delete($model);

                $this->dispatch($model->releaseEvents());
            }
        }
    }
}
