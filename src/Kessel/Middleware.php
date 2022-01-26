<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Middleware.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Kessel;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Middleware
{
    /** @var array  */
    private array $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param Closure $next
     * @throws HttpException
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        Log::info('KESSEL ROUTE: ' . $request->url());
        Log::info('KESSEL PAYLOAD: ' . json_encode($request->except('password')));

        if ($this->invalidToken($request)) {
            throw new HttpException(401, 'Unauthorised');
        }

        if ($this->invalidSignature($request)) {
            throw new HttpException(400, 'Invalid signature');
        }

        return $next($request);
    }

    /**
     * @param Request $request
     * @return bool
     */
    protected function invalidToken(Request $request): bool
    {
        return $request->header(Hyperdrive::HEADER_KEY) !== $this->config['key'];
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function invalidSignature(Request $request): bool
    {
        return ! $request->header(Hyperdrive::HEADER_SIGNATURE);
    }
}