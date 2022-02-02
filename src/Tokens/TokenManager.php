<?php
namespace Platform\Tokens;

use Illuminate\Contracts\Cache\Repository as Cache;
use Psr\SimpleCache\InvalidArgumentException;

class TokenManager
{
    const PREFIX = 'token:';

    /**
     * @var Cache
     */
    protected $cache;

    /**
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $this->cache = $cache;
    }

    /**
     * @param Token $type
     * @return string
     */
    public function create(Token $type): string
    {
        $token = $type->generate();

        $this->cache->put(self::PREFIX.$token, $type, now()->addMinutes($type->expires()));

        return $token;
    }

    /**
     * @param Token $type
     * @return string
     */
    public function forever(Token $type): string
    {
        $token = $type->generate();

        $this->cache->forever(self::PREFIX.$token, $type);

        return $token;
    }

    /**
     * @param string $key
     * @param string $type
     * @return bool
     * @throws InvalidArgumentException
     */
    public function has(string $key, string $type): bool
    {
        $token = $this->cache->get(self::PREFIX.$key);

        return $token && !$this->invalidType($token, $type);
    }

    /**
     * @param string $key
     * @param string $type
     * @return Token
     */
    public function pull(string $key, string $type): Token
    {
        /** @var Token $token */
        $token = $this->cache->pull(self::PREFIX.$key);

        if (!$token || $this->invalidType($token, $type)) {
            throw new TokenNotFoundException("Unknown token '{$key}' requested from Token Repository.");
        }

        return $token;
    }

    /**
     * @param string $key
     * @param string $type
     * @return Token
     * @throws InvalidArgumentException
     */
    public function get(string $key, string $type): Token
    {
        /** @var Token $token */
        $token = $this->cache->get(self::PREFIX.$key);

        if (!$token || $this->invalidType($token, $type)) {
            throw new TokenNotFoundException("Unknown token '{$key}' requested from Token Repository.");
        }

        return $token;
    }

    /**
     * @param Token $token
     * @param string $type
     * @return bool
     */
    protected function invalidType(Token $token, string $type): bool
    {
        return $type !== get_class($token);
    }
}
