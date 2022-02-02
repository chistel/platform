<?php

namespace Platform\Bus\Commands;

use Platform\Database\Traits\EventDispatcher;

class UndeleteModelsCommandHandler
{
    use EventDispatcher;

    /**
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function handle(UndeleteModelsCommand $command)
    {
        $toRestore = $command->repository->getTrashedByIds($command->selected);

        $command->repository->restore($toRestore);

        $events = $toRestore->reduce(function ($events, $model) {
            return array_merge($events, $model->releaseEvents());
        }, []);

        $this->dispatch($events);
    }
}
