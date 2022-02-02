<?php
namespace Tests\Encryption;

use Illuminate\Contracts\Encryption\Encrypter;

class FakeEncrypter implements Encrypter
{
    public $encrypted;
    public $decrypted;

    public function encrypt($value, $serialize = true): string
    {
        $this->encrypted = $value;

        return base64_encode($value);
    }

    public function decrypt($value, $unserialize = true): string
    {
        $this->decrypted = $value;

        return base64_decode($value);
    }
}