<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           CachedJobStatus.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/09/2021, 6:04 PM
 */

namespace Platform\Bus;

use Illuminate\Support\Facades\Cache;
use Platform\Bus\Contracts\Messenger;

class CachedJobStatus implements Messenger
{
    /**
     * @var string
     */
    private string $key;

    /**
     * @var string
     */
    private string $rawKey;

    /**
     * @var string
     */
    private string $finishMessage;

    /**
     * @param string $key
     */
    public function __construct($key)
    {
        $this->rawKey = $key;

        $this->generateKey($key);
    }

    /**
     * @param string $status
     */
    public function setJobStatus($status)
    {
        if ($status) {
            Cache::put($this->key, $status, now()->addHours(6));
        } else {
            Cache::forget($this->key);
        }
    }

    /**
     * @return string
     */
    public function getJobStatus(): string
    {
        return Cache::get($this->key);
    }

    /**
     * @return bool
     */
    public function hasFinishedProcessing(): bool
    {
        return !$this->getJobStatus();
    }

    /**
     * @return string
     */
    public function getBroadcastKey(): string
    {
        return $this->rawKey;
    }

    public function getBroadcastRefreshToken(): string
    {
        return 'broadcast-'.md5($this->key);
    }

    /**
     * @param string $message
     */
    public function setFinishMessage(string $message)
    {
        $this->finishMessage = $message;
    }

    /**
     * @return string|null
     */
    public function finishMessage(): ?string
    {
        return $this->finishMessage;
    }

    /**
     * @param string $key
     */
    private function generateKey(string $key)
    {
        $this->key = 'jobstatus.'.$key;
    }
}
