<?php
namespace Platform\Tokens;

interface Token
{
    /**
     * The number of minutes until the token expires.
     *
     * @return int
     */
    public function expires(): int;

    /**
     * Generate random token string.
     *
     * @return string|int
     */
    public function generate();
}
