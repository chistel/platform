<?php
namespace Tests\Encryption;

use Platform\Encryption\Encrypter;
use Tests\TestCase;

class EncrypterTest extends TestCase
{
    function test_encrypt_data()
    {
        $encrypter = new Encrypter($standard = new FakeEncrypter, $maximum = new FakeEncrypter);

        $response = $encrypter->encrypt('encrypt me');

        $this->assertNotNull($response);
        $this->assertStringNotContainsString('encrypt me', $response);
        $this->assertEquals('encrypt me', $standard->encrypted);
        $this->assertNull($maximum->encrypted);
    }

    function test_decrypt_data()
    {
        $encrypter = new Encrypter($standard = new FakeEncrypter, $maximum = new FakeEncrypter);
        $payload = $encrypter->encrypt('decrypt me');

        $response = $encrypter->decrypt($payload);

        $this->assertEquals('decrypt me', $response);
        $this->assertEquals(base64_encode('decrypt me'), $standard->decrypted);
        $this->assertNull($maximum->decrypted);
    }

    function test_encrypt_maximum_data()
    {
        $encrypter = new Encrypter($standard = new FakeEncrypter, $maximum = new FakeEncrypter);

        $response = $encrypter->maximum('encrypt me');

        $this->assertNotNull($response);
        $this->assertStringNotContainsString('encrypt me', $response);
        $this->assertEquals('encrypt me', $maximum->encrypted);
        $this->assertNull($standard->encrypted);
    }

    function test_decrypt_maximum_data()
    {
        $encrypter = new Encrypter($standard = new FakeEncrypter, $maximum = new FakeEncrypter);
        $payload = $encrypter->maximum('decrypt me');

        $response = $encrypter->decrypt($payload);

        $this->assertEquals('decrypt me', $response);
        $this->assertEquals(base64_encode('decrypt me'), $maximum->decrypted);
        $this->assertNull($standard->decrypted);
    }
}
