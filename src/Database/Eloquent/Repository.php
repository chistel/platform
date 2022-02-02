<?php

namespace Platform\Database\Eloquent;

use Exception;
use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model as EloquentModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Platform\Database\Contracts\RepositoryContract;
use Platform\Database\Traits\MagicRepositoryMethods;

abstract class Repository implements RepositoryContract
{
    use MagicRepositoryMethods;

    /**
     * Stores the model object for querying.
     *
     * @var EloquentModel
     */
    protected $model;

    /**
     * Returns the number of models in the repository.
     *
     * @return integer
     */
    public function countAll()
    {
        return $this->getQuery()->count();
    }

    /**
     * Returns a collection of all records for this repository and the models or entities it respresents.
     *
     * @return Collection
     */
    public function getAll()
    {
        return $this->getQuery()->get();
    }

    /**
     * Acts as a generic method for retrieving a record by a given field/value pair.
     *
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getBy($field, $value)
    {
        return $this->getByQuery($field, $value)->get();
    }

    /**
     * Retrieves a single record based on the field and value provided.
     *
     * @param $field
     * @param $value
     * @return null
     */
    public function getOneBy($field, $value)
    {
        return $this->getByQuery($field, $value)->first();
    }


    /**
     * Creates a query object used for getBy and getOneBy methods.
     *
     * @param $field
     * @param $value
     * @return mixed
     */
    protected function getByQuery($field, $value)
    {
        $query = $this->getQuery();

        return $query->where($field, '=', $value);
    }

    /**
     * Returns a single record based on id.
     *
     * @param $id
     * @return null
     */
    public function getById($id)
    {
        $model = $this->getBy('id', $id);

        return !$model->isEmpty() ? $model[0] : null;
    }

    /**
     * Save 1-n resources.
     *
     * @param array $resources
     * @return mixed
     * @throws Exception
     */
    public function saveAll(...$resources)
    {
        if (count($resources) == 0) {
            throw new Exception('You must provide at least one $resource argument.');
        }

        foreach ($resources as $resource) {
            $this->save($resource);
        }
    }

    /**
     * Similar signature to saveAll, except here you can pass a collection or array of resources.
     *
     * @param array|Collection $resources
     * @return mixed
     * @codeCoverageIgnore
     */
    public function saveMany($resources)
    {
        DB::transaction(function () use ($resources) {
            foreach ($resources as $resource) {
                $this->save($resource);
            }
        });
    }

    /**
     * Searches for a resource with the field and value provided. If no resource is found that matches
     * the value, then it will throw a ModelNotFoundException.
     *
     * @param string $field
     * @param string $value
     * @return EloquentModel
     * @throws ModelNotFoundException
     */

    public function requireBy($field, $value)
    {
        $result = $this->getBy($field, $value);

        if ($result->isEmpty()) {
            throw with(new ModelNotFoundException())->setModel(get_class($this->model));
        }

        return $result->first();
    }

    /**
     * Returns the model that is being used by the repository.
     *
     * @return EloquentModel
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @return Connection
     * @codeCoverageIgnore
     */
    protected function getConnection()
    {
        return $this->getModel()->getConnection();
    }

    /**
     * Returns a new query object that can be used.
     *
     * @return mixed
     */
    abstract protected function getQuery();

    /**
     * Sets the model to be used by the repository.
     *
     * @param $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * Delete a specific resource. Returns the resource that was deleted.
     *
     * @param object $resource
     * @param bool $permanent
     * @return object
     */
    public function delete(object $resource, bool $permanent = false)
    {
        if ($permanent) {
            $resource->forceDelete();
        } else {
            $resource->delete();
        }

        return $resource;
    }

    /**
     * Delete all resources passed to the method.
     *
     * @param mixed ...$resources
     * @return mixed
     */
    public function deleteAll(...$resources)
    {
        $resources = array_flatten($resources);

        foreach ($resources as $resource) {
            if ($resource instanceof Collection) {
                return $this->deleteAll($resource->all());
            }

            if (!is_object($resource)) {
                $resource = $this->getById($resource);
            }

            if ($resource) {
                $this->delete($resource);
            }
        }
    }

    /**
     * Update a resource based on the id and data provided.
     *
     * @param object $resource
     * @param array $data
     * @return object
     */
    public function update($resource, $data = [])
    {
        if (is_array($data) && count($data) > 0) {
            $resource->fill($data);
        }

        $this->save($resource);

        return $resource;
    }

    /**
     * Saves the resource provided to the database.
     *
     * @param $resource
     *
     * @return resource
     */
    public function save($resource)
    {
        $attributes = $resource->getDirty();

        if (!empty($attributes) || !$resource->exists) {
            return $resource->save();
        }

        if ($resource->timestamps === true) {
            return $resource->touch();
        }
    }

    /**
     * Checks to see whether a given record exists based on the id. This should also take into
     * account the account filtering/clause to ensure that checks are done based on the id AND
     * the account.
     *
     * @param integer $id
     * @return boolean
     */
    public function exists($id)
    {
        return (bool)$this->getById($id);
    }

    /**
     * Creates copies of the specified models.
     *
     * @param array $ids
     * @return \Illuminate\Support\Collection
     */
    public function copyByIds(array $ids)
    {
        $models = $this->getByIds($ids);

        return $models->map(function (EloquentModel $model) {
            $copy = $model->copy();
            $this->save($copy);

            return $copy;
        });
    }

    /**
     * Returns a collection of models given an array of IDs.
     *
     * @param array $ids
     * @return \Illuminate\Support\Collection
     */
    public function getByIds(array $ids)
    {
        return $this->getQuery()->whereIn('id', $ids)->get();
    }

    /**
     * Returns an array of all of the existing model IDs.
     *
     * @return array
     */
    public function getIds()
    {
        return $this->getQuery()->pluck('id')->toArray();
    }

    /**
     * Returns an array of all of the trashed existing model IDs.
     *
     * @return array
     */
    public function getTrashedIds()
    {
        return $this->getQuery()->onlyTrashed()->pluck('id')->toArray();
    }

    /**
     * Restores a number of resources based on their ids.
     *
     * @param array $resources
     * @return mixed
     */
    public function restore($resources)
    {
        foreach ($resources as $resource) {
            $resource->restore();

            if (isset($resource->undeletedEvent)) {
                $event = $resource->undeletedEvent;
                $resource->raise(new $event($resource));
            }
        }
    }

    /**
     * Same as getByIds, but returns all trashed/deleted resources.
     *
     * @param array $ids
     * @return mixed
     */
    public function getTrashedByIds(array $ids)
    {
        return $this->getQuery()->onlyTrashed()->whereIn('id', $ids)->get();
    }

    /**
     * Returns the list of IDs filtered by only those that are actually valid.
     * Useful for filtering user input into only the legitimate IDs without needing validation or exception handling.
     *
     * @param array $ids
     * @return \Illuminate\Support\Collection
     */
    public function filterIds(array $ids)
    {
        $query = $this->getQuery();

        return $query->whereIn('id', $ids)->pluck('id')->toArray();
    }

    /**
     * Deletes the records identified by the array of IDs from the database,
     * as well as matching records from the pivot tables.
     * ['pivot_table' => 'pivot_key']
     *
     * @param array $ids
     * @param array $pivots
     * @codeCoverageIgnore
     */
    protected function deleteWithPivots($ids, array $pivots)
    {
        if (!count($ids)) {
            return;
        }

        $this->getQuery()
            ->whereIn('id', $ids)
            ->delete();

        foreach ($pivots as $table => $key) {
            $this->getConnection()
                ->table($table)
                ->whereIn($key, $ids)
                ->delete();
        }
    }

    /**
     * Returns all matching records, with trashed.
     *
     * @param array $ids
     * @return Collection
     */
    public function getByIdsWithTrashed(array $ids)
    {
        return $this->getQuery()
            ->withTrashed()
            ->whereIn('id', $ids)
            ->get();
    }

    /**
     * Returns true if the model is soft deletable.
     *
     * @param Model $model
     * @return bool
     */
    private function softDeletable(Model $model): bool
    {
        return in_array(SoftDeletes::class, class_uses($model));
    }

    /**
     * Returns true if any records exist.
     * This should be faster than `COUNT` as no data needs to be processed,
     * the database should return as soon as the first record is found.
     *
     * @return bool
     */
    public function hasAny(): bool
    {
        return $this->getQuery()->exists();
    }

    /**
     * Executes the callback with the model locked for editing for the duration of the callback,
     * and then returns the model for any further work (such as dispatching events).
     *
     * @param int $id
     * @param callable $callback
     * @return Model
     * @throws \Throwable
     */
    public function getWithLock(int $id, callable $callback): Model
    {
        return $this->getConnection()->transaction(function () use ($id, $callback) {
            $model = $this->getQuery()->lockForUpdate()->find($id);
            $callback($model);

            return $model;
        });
    }

    /**
     * Checks if all of the $ids are valid ids in the database.
     *
     * @param array|int[] $ids
     * @return bool
     */
    public function hasAllIds(array $ids): bool
    {
        $count = $this->getQuery()->whereIn('id', $ids)->count();

        return $count == count($ids);
    }
}