<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           core.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     26/01/2022, 9:27 PM
 */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\HtmlString;
use Platform\Services\Response\Builder;
use Platform\Support\Markdown\MarkdownCache;


if (!function_exists('getPercentOfNumber')) {
    /**
     * calculates the percentage of a given number.
     *
     * @param int $number The number you want a percentage of.
     * @param int $percent The percentage that you want to calculate.
     * @return float|int The final result.
     */
    function getPercentOfNumber(int $number, int $percent): float|int
    {
        return ($percent / 100) * $number;
    }
}

if (!function_exists('uses_trait')) {
    /**
     * Returns true if the class uses the trait
     *
     * @param object|string $class
     * @param string $trait
     * @return bool
     */
    function uses_trait(object|string $class, string $trait): bool
    {
        $class = is_object($class) ? get_class($class) : $class;

        return in_array($trait, class_uses_recursive($class));
    }
}

if (!function_exists('isInstanceOf')) {
    /**
     * @param $object
     * @param array $classnames
     * @return bool
     */
    function isInstanceOf($object, array $classnames)
    {
        foreach ($classnames as $classname) {
            if ($object instanceof $classname) {
                return true;
            }
        }
        return false;
    }
}
/**
 * @param $content
 * @param array $patterns
 * @param array $replacements
 * @return string|string[]
 */
function replaceTags($content, array $patterns = [], array $replacements = []): array|string
{
    $pattern = getTagsPattern($patterns);
    $replacement = applyQuote($replacements);

    return revertQuote(preg_replace($pattern, $replacement, $content));
}

/**
 * @param array $tags
 * @return array
 */
function getTagsPattern(array $tags = []): array
{
    $pattern = [];

    foreach ($tags as $tag) {
        $pattern[] = "/" . $tag . "/";
    }

    return $pattern;
}

if (!function_exists('applyQuote')) {
    /**
     * @param $vars
     * @return array
     */
    function applyQuote($vars): array
    {
        $new_vars = [];

        foreach ($vars as $var) {
            $new_vars[] = preg_quote($var);
        }

        return $new_vars;
    }
}

if (!function_exists('revertQuote')) {
    /**
     * @param $content
     * @return string|string[]
     */
    function revertQuote($content): array|string
    {
        return str_replace('\\', '', $content);
    }
}

if (!function_exists('paginatr')) {
    /**
     * The attributes that are mass assignable.
     *
     * @param $items
     * @param int $perPage
     * @param null $page
     * @param array $options
     * @return LengthAwarePaginator
     */
    function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

    }
}

if (!function_exists('api')) {

    /**
     * Start creating a idempotent api response
     *
     * @return Builder
     */
    function api(): Builder
    {
        return new Builder();
    }
}

if (!function_exists('getPercentageChange')) {
    /**
     * @param $previous
     * @param $current
     * @return float|int
     */
    function getPercentageChange($previous, $current): float|int
    {
        $decreaseValue = $current - $previous;
        if (!$previous) {
            return $current ? 100 : 0;
        }
        return ($decreaseValue / $previous) * 100;
    }
}


/**
 * @param $path
 * @param string $active
 * @return string
 */
if (!function_exists('active_class')) {
    function active_class($path, $active = 'active')
    {
        return call_user_func_array('Request::is', (array)$path) ? $active : '';
    }
}

/**
 * @param $path
 * @param string $active
 * @return string
 */
if (!function_exists('active_class_two')) {
    function active_class_two($path, $active = 'active')
    {
        return call_user_func_array('Request::routeIs', (array)$path) ? $active : '';
    }
}
/**
 * @param $path
 * @return string
 */
if (!function_exists('is_active_route')) {
    function is_active_route($path)
    {
        return call_user_func_array('Request::is', (array)$path) ? 'true' : 'false';
    }
}

/**
 * @param $path
 * @return string
 */
if (!function_exists('show_class')) {
    function show_class($path)
    {
        return call_user_func_array('Request::is', (array)$path) ? 'show' : '';
    }
}

if (!function_exists('extract_title')) {
    /**
     * Extract page title from breadcrumbs.
     *
     * @param HtmlString $breadcrumbs
     * @param string $separator
     * @return string
     */
    function extract_title(HtmlString $breadcrumbs, string $separator = ' » '): string
    {
        return strip_tags(Str::replaceLast($separator, '', str_replace('</li>', $separator, $breadcrumbs)));
    }
}
/**
 * @param $start
 * @param $end
 * @param $currentTime
 * @return bool
 */
function isWithInTime($start, $end, $currentTime): bool
{
    if (($currentTime >= $start) && ($currentTime <= $end)) {
        return true;
    }
    return false;
}

if (!function_exists('domain')) {
    /**
     * Return domain host.
     *
     * @return string
     */
    function domain(): string
    {
        return parse_url(config('app.url'))['host'];
    }
}

if (!function_exists('intend')) {
    /**
     * Return redirect response.
     *
     * @param array $arguments
     * @param int $status
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    function intend(array $arguments, int $status = 302): JsonResponse|Redirector|RedirectResponse|Application
    {
        $redirect = redirect(Arr::pull($arguments, 'url'), $status);

        if (request()->expectsJson()) {
            $response = collect($arguments['withErrors'] ?? $arguments['with']);

            return response()->json([$response->flatten()->first() ?? 'OK']);
        }

        foreach ($arguments as $key => $value) {
            $redirect = in_array($key, ['home', 'back']) ? $redirect->{$key}() : $redirect->{$key}($value);
        }

        return $redirect;
    }
}

if (!function_exists('mimetypes')) {
    /**
     * Get valid mime types.
     *
     * @see https://github.com/symfony/http-foundation/blob/3.0/File/MimeType/MimeTypeExtensionGuesser.php
     * @see http://svn.apache.org/repos/asf/httpd/httpd/trunk/docs/conf/mime.types
     *
     * @return array
     */
    function mimetypes(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../../resources/data/mimetypes.json'), true);
    }
}

if (!function_exists('timezones')) {
    /**
     * Get valid timezones.
     * This list is based upon the timezone database version 2017.2.
     *
     * @see http://php.net/manual/en/timezones.php
     *
     * @return array
     */
    function timezones(): array
    {
        return json_decode(file_get_contents(__DIR__ . '/../../resources/data/timezones.json'), true);
    }
}

if (!function_exists('array_search_recursive')) {
    /**
     * Recursively searches the array for a given value and returns the corresponding key if successful.
     *
     * @param mixed $needle
     * @param array $haystack
     *
     * @return mixed
     */
    function array_search_recursive($needle, array $haystack)
    {
        foreach ($haystack as $key => $value) {
            $current_key = $key;
            if ($needle === $value || (is_array($value) && array_search_recursive($needle, $value) !== false)) {
                return $current_key;
            }
        }

        return false;
    }
}

if (!function_exists('array_trim_recursive')) {
    /**
     * Recursively trim elements of the given array.
     *
     * @param $values
     * @param string $charList
     * @return array|mixed|string
     */
    function array_trim_recursive($values, string $charList = " \t\n\r\0\x0B"): mixed
    {
        if (is_array($values)) {
            return array_map('array_trim_recursive', $values);
        }

        return is_string($values) ? trim($values, $charList) : $values;
    }
}

if (!function_exists('array_filter_recursive')) {
    /**
     * Recursively filter empty strings and null elements of the given array.
     *
     * @param array $values
     * @param bool $strOnly
     *
     * @return mixed
     */
    function array_filter_recursive(array $values, bool $strOnly = true)
    {
        foreach ($values as &$value) {
            if (is_array($value)) {
                $value = array_filter_recursive($value);
            }
        }

        return !$strOnly ? array_filter($values) : array_filter($values, function ($item) {
            return !is_null($item) && !((is_string($item) || is_array($item)) && empty($item));
        });
    }
}


if (!function_exists('obfuscate')) {
    /**
     * Obfuscates the value into a string that can be passed to the browser, and passed through `clarify()`.
     *
     * @param mixed $raw
     * @return string
     */
    function obfuscate($raw): string
    {
        return base64_encode(json_encode($raw, JSON_NUMERIC_CHECK));
    }
}

if (!function_exists('formatTime')) {
    /**
     * Formats the time, in seconds, into a friendly string.
     *
     * @param int $time
     * @return string
     */
    function formatTime(int $time)
    {
        $hours = str_pad(floor($time / 3600), 2, '0', STR_PAD_LEFT);
        $minutes = str_pad(floor($time / 60 % 60), 2, '0', STR_PAD_LEFT);
        $seconds = str_pad(floor($time % 60), 2, '0', STR_PAD_LEFT);

        return "{$hours}:{$minutes}:{$seconds}";
    }
}

if (!function_exists('formatSize')) {
    /**
     * Formats the file size, in bytes, into a friendly string.
     *
     * Inspired by / stolen from https://gist.github.com/grena/5977137
     *
     * @param int $size
     * @return string
     */
    function formatSize(int $size): string
    {
        $units = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB'];
        $power = floor(log($size, 1024));
        $precision = $size >= 1048576 ? 1 : 0;
        $number = $size > 0 ? $size / (1024 ** $power) : 0;

        return number_format($number, $precision) . ' ' . $units[$power];
    }
}

if (!function_exists('redirectTo')) {
    /**
     * @param Request $request
     * @return string|null
     */
    function redirectTo(Request $request): ?string
    {
        $target = null;
        $redirectTo = $request->query('redirect_to');
        $httpReferer = $request->headers->get('referer');
        if (str_contains($httpReferer, '?redirect_to=')) {
            $params = explode('?', $httpReferer);
            $target = urldecode(explode('=', $params[1])[1]);
        } elseif (!is_null($redirectTo)) {
            $refData = parse_url($redirectTo);
            $siteDomain = parse_url(env('APP_URL'));
            if (isset($refData['host']) && ($refData['host'] == $siteDomain['host'])) {
                $target = urldecode($redirectTo);
            }
        }

        return $target;
    }
}

if (!function_exists('external_url')) {
    /**
     * Saves the provided URL in the session and returns an external redirect URL for the user.
     *
     * Uses md5 on the URL to generate a repeatable token - to save spamming the session on each page load.
     * We don't need to worry about security as it's being stored in a whitelist.
     * Collisions shouldn't be an issue, due to URL length... and I'm not sure why an attacker would bother...
     *
     * @param string $url
     * @param int|null $expiry
     * @param string $route
     * @return string
     */
    function external_url(string $url, int $expiry = null, $route = 'redirect.token'): string
    {
        $token = md5($url);
        $key = 'redirect-external:' . $token;

        if ($expiry) {
            // Bump expiry time on cache entry (otherwise it could expire within a second!)
            Cache::forget($key);
            Cache::put($key, $url, now()->addMinutes($expiry));
        } else {
            Session::put($key, $url);
        }

        if (Route::has($route)) {
            return route($route, $token);
        }

        return $url;
    }
}

if (!function_exists('obfuscate')) {
    /**
     * Obfuscates the value into a string that can be passed to the browser, and passed through `clarify()`.
     *
     * @param mixed $raw
     * @return string
     */
    function obfuscate($raw): string
    {
        return base64_encode(json_encode($raw, JSON_NUMERIC_CHECK));
    }
}

if (!function_exists('prefix')) {
    /**
     * Provides a function that will prefix a string with the provided value, if it's not already there.
     *
     * @param string $string
     * @param string $prefix
     * @return string
     */
    function prefix(string $string, string $prefix): string
    {
        if (stripos($string, $prefix) === 0) {
            return $string;
        }

        return $prefix . $string;
    }
}

if (!function_exists('query_parameters')) {
    /**
     * Returns true if any of the provided query parameters are set (and not empty) in the request.
     *
     * @param array $parameters
     * @return bool
     */
    function query_parameters(array $parameters): bool
    {
        return count(array_filter(Request::only($parameters), 'strlen')) > 0;
    }
}


if (!function_exists('uses_trait')) {
    /**
     * Returns true if the class uses the trait
     *
     * @param object|string $class
     * @param string $trait
     * @return bool
     */
    function uses_trait(object|string $class, string $trait): bool
    {
        $class = is_object($class) ? get_class($class) : $class;

        return in_array($trait, class_uses_recursive($class));
    }
}

if (!function_exists('markdown')) {
    /**
     * Converts a bunch of text in markdown, to its HTML equivalent.
     *
     * @param string $markdown
     * @return string
     */
    function markdown(string $markdown): string
    {
        return $markdown ? app(MarkdownCache::class)->parse($markdown) : '';
    }
}

if (!function_exists('markdown_inline')) {
    /**
     * Converts text in markdown to its HTML inline equivalent. (No <p> tags)
     *
     * @param string $markdown
     * @return string
     */
    function markdown_inline(string $markdown): string
    {
        return $markdown ? app(MarkdownCache::class)->inline($markdown) : '';
    }
}

if (!function_exists('markdown_pdf')) {
    /**
     * Converts text in markdown to its HTML inline equivalent. (Simple output, no embedded videos)
     *
     * @param string $markdown
     * @return string
     */
    function markdown_pdf(string $markdown): string
    {
        return $markdown ? app(MarkdownCache::class)->pdf($markdown) : '';
    }
}
