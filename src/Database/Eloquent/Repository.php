<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Repository.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Database\Eloquent;

use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Traits\CacheableRepository;
use Prettus\Repository\Contracts\CacheableInterface;
use Prettus\Repository\Exceptions\RepositoryException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

abstract class Repository extends BaseRepository implements CacheableInterface
{
	use CacheableRepository;

	public function makeModel()
	{
		$model = $this->app->make($this->model());

		return $this->model = $model;
	}

	/**
	 * Find data by field and value
	 *
	 * @param string $field
	 * @param null $value
	 * @param array $columns
	 * @return mixed
	 */
	public function findOneByField(string $field, $value = null, array $columns = ['*'])
	{
		$model = $this->findByField($field, $value, $columns);

		return $model->first();
	}

	/**
	 * Find data by field and value
	 *
	 * @param array $where
	 * @param string[] $columns
	 * @return mixed
	 */
	public function findOneWhere(array $where, array $columns = ['*'])
	{
		$model = $this->findWhere($where, $columns);

		return $model->first();
	}

	/**
	 * Find data by id
	 *
	 * @param $id
	 * @param string[] $columns
	 * @return LengthAwarePaginator|Collection|mixed
	 * @throws RepositoryException
	 */
	public function find($id, $columns = ['*'])
	{
		$this->applyCriteria();
		$this->applyScope();
		$model = $this->model->find($id, $columns);
		$this->resetModel();

		return $this->parserResult($model);
	}

	/**
	 * Find data by id
	 *
	 * @param int $id
	 * @param array $columns
	 * @return mixed
	 * @throws RepositoryException
	 */
	public function findOrFail($id, $columns = ['*'])
	{
		$this->applyCriteria();
		$this->applyScope();
		$model = $this->model->findOrFail($id, $columns);
		$this->resetModel();

		return $this->parserResult($model);
	}

	/**
	 * Count results of repository
	 *
	 * @param array $where
	 * @param string $columns
	 * @return int
	 * @throws RepositoryException
	 */
	public function count(array $where = [], $columns = '*')
	{
		$this->applyCriteria();
		$this->applyScope();

		if ($where) {
			$this->applyConditions($where);
		}

		$result = $this->model->count($columns);
		$this->resetModel();
		$this->resetScope();

		return $result;
	}

	/**
	 * @param string $columns
	 * @return mixed
	 * @throws RepositoryException
	 */
	public function sum($columns)
	{
		$this->applyCriteria();
		$this->applyScope();

		$sum = $this->model->sum($columns);
		$this->resetModel();

		return $sum;
	}

    /**
     * @param string $columns
     * @return mixed
     * @throws RepositoryException
     */
	public function avg($columns)
	{
		$this->applyCriteria();
		$this->applyScope();

		$avg = $this->model->avg($columns);
		$this->resetModel();

		return $avg;
	}

	/**
	 * @return mixed
	 */
	public function getModel()
	{
		return $this->model;
	}

	// public function with($relationship)
	// {
	//     return $this->model->with($relationship)->get();
	// }
}
