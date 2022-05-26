<?php
namespace Platform\Encryption;

use Illuminate\Contracts\Encryption\Encrypter as EncrypterContract;

class Encrypter implements EncrypterContract
{
    public const PREFIX_STANDARD = 's:';
    public const PREFIX_MAXIMUM = 'm:';

    /** @var EncrypterContract */
    private $standard;

    /** @var EncrypterContract */
    private $maximum;

    public function __construct(EncrypterContract $standard, EncrypterContract $maximum)
    {
        $this->standard = $standard;
        $this->maximum = $maximum;
    }

    /**
     * Encrypt payload at the standard security level.
     *
     * @param string $payload
     * @param bool $serialize
     * @return string
     */
    public function encrypt($payload, $serialize = true): string
    {
        return self::PREFIX_STANDARD.$this->standard->encrypt($payload, $serialize);
    }

    /**
     * Encrypt payload at the maximum security level.
     *
     * @param $payload
     * @param bool $serialize
     * @return string
     */
    public function maximum($payload, bool$serialize = true): string
    {
        return self::PREFIX_MAXIMUM.$this->maximum->encrypt($payload, $serialize);
    }

    /**
     * @param string $payload
     * @param bool $unserialize
     * @throws UnableToDecryptPayload
     * @return string
     */
    public function decrypt($payload, $unserialize = true)
    {
        $prefix = substr($payload, 0, 2);
        $payload = substr($payload, 2);

        return match ($prefix) {
            self::PREFIX_STANDARD => $this->standard->decrypt($payload, $unserialize),
            self::PREFIX_MAXIMUM => $this->maximum->decrypt($payload, $unserialize),
            default => throw new UnableToDecryptPayload('Unknown security level'),
        };
    }
}
