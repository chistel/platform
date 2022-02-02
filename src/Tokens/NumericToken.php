<?php
namespace Platform\Tokens;

trait NumericToken
{
    /**
     * Generates a 6 digit numerical token.
     *
     * @return int
     * @throws \Exception
     */
    public function generate()
    {
        return random_int(100000, 999999);
    }
}
