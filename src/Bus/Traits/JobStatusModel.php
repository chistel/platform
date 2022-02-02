<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           JobStatusModel.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:12 PM
 */

namespace Platform\Bus\Traits;

use function object_get;

trait JobStatusModel
{
    /**
     * @param string $status
     */
    public function setJobStatus($status)
    {
        if (empty($this->deletedAt)) {
            $this->jobStatus = $status;

            $this->save();
        }
    }

    /**
     * @return string
     */
    public function getJobStatus(): string
    {
        return $this->jobStatus;
    }

    /**
     * @return bool
     */
    public function hasFinishedProcessing(): bool
    {
        return !$this->jobStatus;
    }

    /**
     * @return string
     */
    public function getBroadcastKey(): string
    {
        return strtolower(class_basename($this));
    }

    /**
     * @return string
     */
    public function getBroadcastName(): string
    {
        return object_get($this, 'title') ?: object_get($this, 'slug');
    }

    public function getBroadcastRefreshToken(): string
    {
        return 'broadcast-'.md5($this->getBroadcastKey().'-'.$this->getKey());
    }
}
