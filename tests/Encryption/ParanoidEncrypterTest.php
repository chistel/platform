<?php

namespace Tests\Encryption;

use Platform\Encryption\ParanoidEncrypter;
use Tests\TestCase;

class ParanoidEncrypterTest extends TestCase
{
    function test_key_not_retrieved_when_unused()
    {
        $key = function () {
            throw new \Exception('Key function should not be called!');
        };

        new ParanoidEncrypter($key);
    }

    function test_encrypt_decrypt()
    {
        $key = function () {
            return 'SomeRandomString';
        };

        $encrypter = new ParanoidEncrypter($key);
        $encrypted = $encrypter->encrypt('encrypt me');
        $decrypted = $encrypter->decrypt($encrypted);

        $this->assertEquals('encrypt me', $decrypted);
        $this->assertNotEquals($encrypted, $decrypted);
    }

    function test_key_only_called_once()
    {
        $counter = 0;
        $key = function () use (&$counter) {
            $counter += 1;
            return 'SomeRandomString';
        };

        $encrypter = new ParanoidEncrypter($key);
        $encrypted = $encrypter->encrypt('encrypt me');
        $encrypter->decrypt($encrypted);
        $encrypter->encrypt('another encrypt');

        $this->assertEquals(1, $counter);
    }
}