<?php
namespace Platform\Tokens;

trait StringToken
{
    /**
     * Generate random token string.
     *
     * @return string|int
     */
    public function generate()
    {
        return str_random(32);
    }
}
