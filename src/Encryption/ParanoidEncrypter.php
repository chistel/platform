<?php
namespace Platform\Encryption;

use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;
use Illuminate\Encryption\Encrypter;

class ParanoidEncrypter implements EncrypterContract
{
    /** @var callable */
    private $key;

    /** @var string */
    private $cipher;

    /** @var Encrypter */
    private $encrypter;

    public function __construct(callable $key, $cipher = 'AES-128-CBC')
    {
        $this->key = $key;
        $this->cipher = $cipher;
    }

    public function encrypt($value, $serialize = true)
    {
        return $this->encrypter()->encrypt($value, $serialize);
    }

    public function decrypt($payload, $unserialize = true)
    {
        return $this->encrypter()->decrypt($payload, $unserialize);
    }

    private function encrypter(): Encrypter
    {
        if ($this->encrypter) {
            return $this->encrypter;
        }

        return $this->encrypter = new Encrypter(($this->key)(), $this->cipher);
    }
}
