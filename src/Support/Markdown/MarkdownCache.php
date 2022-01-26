<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           MarkdownCache.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Support\Markdown;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Platform\Html\Markdown;
use Platform\Html\Parsedown;

class MarkdownCache extends Markdown
{
    public const CACHE_EXPIRY = 10080; // one week in minutes

    public function parse(string $markdown, $refresh = false): string
    {
        if ($this->isEditInterfaceTextString($markdown)) {
            return $markdown;
        }

        return $this->remember('parse', $markdown, function ($markdown) {
            return parent::parse($markdown);
        }, $refresh);
    }

    public function inline(string $markdown): string
    {
        if ($this->isEditInterfaceTextString($markdown)) {
            return $markdown;
        }

        return $this->remember('inline', $markdown, function ($markdown) {
            return parent::inline($markdown);
        });
    }

    public function pdf(string $markdown): string
    {
        if ($this->isEditInterfaceTextString($markdown)) {
            return $markdown;
        }

        return $this->remember('pdf', $markdown, function ($markdown) {
            return parent::pdf($markdown);
        });
    }

    protected function parsedown(): Parsedown
    {
        return (new Parsedown())
            ->setCacheExpiry(self::CACHE_EXPIRY * 2)
            ->setMarkupEscaped(true);
    }

    private function remember(string $method, string $markdown, $callback, $refresh = false): string
    {
        $tags = $this->tags();
        $key = $method . '-markdown:' . md5($markdown);

        if (!$refresh && $output = Cache::tags($tags)->get($key)) {
            return $output;
        }

        Cache::tags($tags)->put($key, $output = $callback($markdown), now()->addMinutes(self::CACHE_EXPIRY));

        return $output;
    }

    private function tags(): array
    {
        return ['markdown'];
    }

    private function isEditInterfaceTextString(string $markdown): bool
    {
        return Str::startsWith($markdown, '<edit-interface-text ');
    }
}