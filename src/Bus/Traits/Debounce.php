<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Debounce.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 10:11 PM
 */

namespace Platform\Bus\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use function now;

trait Debounce
{
    /** @var int */
    private int $debounceOverdue = 5;

    /** @var string */
    private string $debounceToken;

    /**
     * Unique debounce key, used to detect duplicate commands.
     *
     * @return string
     */
    abstract protected function debounceKey(): string;

    /**
     * Merge the current payload with the existing debounced payload, called before cmd is debounced or handled.
     * This allows complex commands to compound on each other, making them more efficient by processing
     * the superset debounced, rather than multiple subset commands.
     * (Override method in command to manage shared payload)
     *
     * @param array $existing
     * @return array
     */
    protected function debouncePayload(array $existing): array
    {
        return $existing;
    }

    /**
     * Generates and saves the random debounce token in cache, so it can be used to detect duplicates.
     */
    public function queueDebounce()
    {
        Cache::put($this->hashedKey('key'), $this->debounceToken = Str::random(), $this->debounceExpiry());

        $key = $this->hashedKey('payload');
        Cache::put($key, $this->debouncePayload(Cache::get($key, [])), $this->debounceExpiry());
    }

    /**
     * Checks the debounce token in cache and returns true if the command is outdated and should be bounced.
     *
     * @return bool
     */
    public function debounceOutdated(): bool
    {
        return $this->debounceToken != Cache::get($this->hashedKey('key'));
    }

    /**
     * Checks if the debounced command is overdue for processing, regardless of outdated status.
     *
     * @return bool
     */
    public function debounceOverdue(): bool
    {
        $last = Cache::get($this->hashedKey('last'), Carbon::yesterday());

        return $last->lt(Carbon::now()->subMinutes($this->debounceOverdue));
    }

    /**
     * Sets a lock for the debounce key, to prevent parallel processing.
     */
    public function lockDebounce()
    {
        Cache::put($this->hashedKey('locked'), true, $this->debounceExpiry());
    }

    /**
     * Remove the lock on the debounce key, to allow subsequent processing.
     */
    public function unlockDebounce()
    {
        Cache::forget($this->hashedKey('locked'));
        Cache::put($this->hashedKey('last'), Carbon::now(), $this->debounceExpiry());
    }

    /**
     * Returns true if the debounce key is currently locked for processing.
     *
     * @return bool
     */
    public function debounceLocked(): bool
    {
        return Cache::get($this->hashedKey('locked'), false);
    }

    /**
     * Sets a delay on the command and returns self for dispatching back into the queue.
     * Intended for use when the debounce key is locked, this will allow it to be
     * re-processed once the lock is cleared.
     *
     * @return Debounce
     */
    public function retryDebounce(): self
    {
        $this->delay = 30;  // Retry in 30 seconds

        return $this;
    }

    /**
     * Forgets the cached debounce payload, designed to be called before the command is processed.
     * This only removes the payload from cache, it doesn't affect the command data
     */
    public function forgetDebouncePayload()
    {
        Cache::forget($this->hashedKey('payload'));
    }

    /**
     * Returns a prefixed and hashed debounce key.
     *
     * @param string $type
     * @return string
     */
    private function hashedKey(string $type): string
    {
        return "debounce.{$type}:".md5(__CLASS__.':'.$this->debounceKey());
    }

    /**
     * @return \Illuminate\Support\Carbon
     */
    private function debounceExpiry()
    {
        return now()->addMinutes(180);
    }
}
