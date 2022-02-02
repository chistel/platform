<?php
/*
 * Copyright (C) 2022,  Chistel Brown,  - All Rights Reserved
 * @project                  Core component for laravel project
 * @file                           Hyperdrive.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     23/01/2022, 8:36 AM
 */

namespace Platform\Kessel;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Platform\Authorisation\Signatory;
use Platform\Kessel\Exceptions\ResourceNotFound;
use Platform\Kessel\Exceptions\ValidationFailed;

class Hyperdrive
{
    public const HEADER_KEY = 'X-KESSEL-KEY';
    public const HEADER_SIGNATURE = 'X-KESSEL-SIGNATURE';
    public const HEADER_ORIGIN = 'X-KESSEL-ORIGIN';

    /** @var Signatory */
    private Signatory $signatory;

    /** @var string */
    private $endpoint;

    public function __construct()
    {
        $this->signatory = app(config('kessel.system_consumer'));
    }

    /**
     * List or show resource(s)
     *
     * @param string $resource
     * @param array $parameters
     * @return array
     * @throws ResourceNotFound
     * @throws ValidationFailed|RequestException
     */
    public function get(string $resource, array $parameters = []): array
    {
        return json_decode($this->request('get', $resource, $parameters), true);
    }

    /**
     * @param string $resource
     * @param array $data
     * @return array
     * @throws ResourceNotFound
     * @throws ValidationFailed|RequestException
     */
    public function create(string $resource, array $data = []): array
    {
        return json_decode($this->request('post', $resource, $data), true);
    }

    /**
     * Update existing resource
     *
     * @param string $resource
     * @param array $data
     * @return void
     * @throws ResourceNotFound
     * @throws ValidationFailed|RequestException
     */
    public function update(string $resource, array $data = [])
    {
        $this->request('put', $resource, $data);
    }

    /**
     * Delete specific resource
     *
     * @param string $resource
     * @return void
     * @throws ResourceNotFound
     * @throws ValidationFailed
     */
    public function delete(string $resource)
    {
        $this->request('delete', $resource);
    }

    /**
     * Ping the requested endpoint.
     *
     * @return Response
     * @throws ResourceNotFound
     * @throws ValidationFailed
     */
    public function ping(): Response
    {
        return $this->request('get', 'ping');
    }

    /**
     * @param string $action
     * @param string $resource
     * @param array $data
     * @return Response
     * @throws ResourceNotFound
     * @throws ValidationFailed
     * @throws RequestException
     */
    private function request(string $action, string $resource, array $data = []): Response
    {
        return Http::send($action, $this->buildUrl($resource), $this->requestOptions($action, $data))->throw(function ($response, $exception) {
            match ($exception->getCode()) {
                422 => new ValidationFailed($exception),
                404 => new ResourceNotFound($exception),
                default => $exception,
            };
        });
    }

    /**
     * @param string $action
     * @param array $data
     * @return array
     */
    private function requestOptions(string $action, array $data): array
    {
        $dataKey = ($action == 'get') ? 'query' : 'json';

        return array_filter([
            $dataKey => $data,
            'headers' => [
                'Accept' => 'application/json',
                self::HEADER_KEY => config('kessel.key'),
                self::HEADER_SIGNATURE => (string)$this->signatory->signature(),
                self::HEADER_ORIGIN => config("kessel.origin")
            ],
        ]);
    }

    /**
     * @param string $resource
     * @return string
     */
    private function buildUrl(string $resource): string
    {
        return Str::finish($this->endpoint, '/') . $resource;
    }
}