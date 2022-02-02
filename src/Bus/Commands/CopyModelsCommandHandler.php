<?php
namespace Platform\Bus\Commands;


use Illuminate\Contracts\Container\BindingResolutionException;
use Platform\Database\Traits\EventDispatcher;

class CopyModelsCommandHandler
{
    use EventDispatcher;

    /**
     * @throws BindingResolutionException
     */
    public function handle(CopyModelsCommand $command)
    {
        $models = $command->repository->copyByIds($command->ids);

        $events = $models->reduce(function ($events, $model) {
            return array_merge($events, $model->releaseEvents());
        }, []);

        $this->dispatch($events);
    }
}
