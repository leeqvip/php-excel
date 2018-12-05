<?php

namespace TechOne\Excel\Concerns;

use Redis;
use Cache\Adapter\Redis\RedisCachePool;
use Cache\Bridge\SimpleCache\SimpleCacheBridge;
use PhpOffice\PhpSpreadsheet\Settings;

trait MapsCacheSettings
{
    protected $cacheDriver = 'memory';

    protected $cacheConfig = [];

    /**
     * @param array $config
     */
    public function applyCacheSettings(array $config)
    {
        $this->cacheDriver = array_get($config, 'driver', $this->cacheDriver);

        $this->cacheConfig = array_get($config, $this->cacheDriver, $this->cacheConfig);

        switch ($this->cacheDriver) {
            case 'redis':
                $this->applyRedisCacheSettings();
                break;

            default:
                break;
        }
    }

    protected function applyRedisCacheSettings()
    {
        $client = new Redis();
        $client->connect(array_get($this->cacheConfig, 'host', '127.0.0.1'), array_get($this->cacheConfig, 'port', '6379'));
        if (!empty(array_get($this->cacheConfig, 'password', null))) {
            $client->auth(array_get($this->cacheConfig, 'password'));
        }
        if (!empty(array_get($this->cacheConfig, 'database', 0))) {
            $client->select(array_get($this->cacheConfig, 'database'));
        }
        $pool = new RedisCachePool($client);
        $simpleCache = new SimpleCacheBridge($pool);

        Settings::setCache($simpleCache);
    }
}
