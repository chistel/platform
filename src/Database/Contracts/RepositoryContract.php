<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Repository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     02/02/2022, 11:45 AM
 */

namespace Platform\Database\Contracts;

use Illuminate\Support\Collection;
use Platform\Database\Eloquent\Model;

/**
 * Nearly all repositories will require the following methods. This is to ensure we're dealing with a
 * common interface for all our repositories. Each repository should implement its own interface that extends
 * this, and if there are any changes in the requirements, they can define them there.
 */
interface RepositoryContract
{
    /**
     * Delete a specific resource. Returns the resource that was deleted.
     *
     * @param object $resource
     * @param bool $permanent
     * @return resource
     */
    public function delete(object $resource, bool $permanent = false);

    /**
     * Delete all resources passed to the method.
     *
     * @param ...$resources
     * @return mixed
     */
    public function deleteAll(...$resources);

    /**
     * Returns the number of models in the repository.
     *
     * @return integer
     */
    public function countAll();

    /**
     * Returns a collection of all records for this repository and the models or entities it respresents.
     *
     * @return Collection
     */
    public function getAll();


    /**
     * Acts as a generic method for retrieving a record by a given field/value pair.
     *
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getBy($field, $value);

    /**
     * Returns a single record based on id.
     *
     * @param $id
     * @return null
     */
    public function getById($id);

    /**
     * Similar to getBy, but returns only a single record, rather than a collection of fields.
     *
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getOneBy($field, $value);

    /**
     * Acts as a generic method for requiring a record by a given field/value pair.
     *
     * @param $field
     * @param $value
     * @return mixed
     */
    public function requireBy($field, $value);

    /**
     * @param $resource
     * @param array $data
     * @return resource
     */
    public function update($resource, $data = []);

    /**
     * Saves the provided resource.
     *
     * @param $resource
     * @return mixed
     */
    public function save($resource);

    /**
     * Save 1-n resources.
     *
     * @param array $resources
     * @return mixed
     */
    public function saveAll(...$resources);

    /**
     * Similar signature to saveAll, except here you can pass a collection or array of resources.
     *
     * @param array|Collection $resources
     * @return mixed
     */
    public function saveMany($resources);

    /**
     * Checks to see whether a given record exists based on the id. This should also take into
     * account the account filtering/clause to ensure that checks are done based on the id AND
     * the account.
     *
     * @param integer $id
     * @return mixed
     */
    public function exists($id);

    /**
     * Creates copies of the specified models.
     *
     * @param array $ids
     * @return Collection
     */
    public function copyByIds(array $ids);

    /**
     * Returns a collection of models given an array of IDs.
     *
     * @param array $ids
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getByIds(array $ids);

    /**
     * Returns an array of all of the existing model IDs.
     *
     * @return array
     */
    public function getIds();

    /**
     * Returns an array of all of the trashed existing model IDs.
     *
     * @return array
     */
    public function getTrashedIds();

    /**
     * Restores a number of resources based on their ids.
     *
     * @param array $resources
     * @return mixed
     */
    public function restore($resources);

    /**
     * Same as getByIds, but returns all trashed/deleted resources.
     *
     * @param array $ids
     * @return mixed
     */
    public function getTrashedByIds(array $ids);

    /**
     * Returns the list of IDs filtered by only those that are actually valid.
     * Useful for filtering user input into only the legitimate IDs without needing validation or exception handling.
     *
     * @param array $ids
     * @return Collection
     */
    public function filterIds(array $ids);

    /**
     * Returns all matching records, with trashed.
     *
     * @param array $ids
     * @return \Platform\Database\Eloquent\Collection
     */
    public function getByIdsWithTrashed(array $ids);

    /**
     * Returns true if any records exist.
     * This should be faster than `COUNT` as no data needs to be processed,
     * the database should return as soon as the first record is found.
     *
     * @return bool
     */
    public function hasAny(): bool;

    /**
     * Executes the callback with the model locked for editing for the duration of the callback,
     * and then returns the model for any further work (such as dispatching events).
     *
     * @param int $id
     * @param callable $callback
     * @throws \Throwable
     * @return Model
     */
    public function getWithLock(int $id, callable $callback): Model;

    /**
     * Checks if all of the $ids are valid ids in the database.
     *
     * @param array|int[] $ids
     * @return bool
     */
    public function hasAllIds(array $ids): bool;
}