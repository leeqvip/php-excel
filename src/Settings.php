<?php

namespace TechOne\Excel;

use TechOne\Excel\Exceptions\ExcelException;
use TechOne\Support\Config;

class Settings
{
    public static $instance;

    public $config;

    public function __construct($config = [])
    {
        if ($config instanceof Config) {
            $this->config = $config;
        } elseif (is_array($config)) {
            $config = array_merge(require_once __DIR__.'/../config/excel.php', $config);
            $this->config = new Config($config);
        } else {
            throw new ExcelException('Unsupported settings format.');
        }
    }

    public static function init($config = [])
    {
        return self::getInstance($config);
    }

    public static function getInstance($config = [])
    {
        if (!is_null(self::$instance)) {
            return self::$instance;
        }

        self::$instance = new static($config);

        return self::$instance;
    }

    public static function get($key, $default = null)
    {
        return self::getInstance()->config->get($key, $default);
    }

    public static function set($key, $value = null)
    {
        return self::getInstance()->config->set($key, $value);
    }

    public static function all()
    {
        return self::getInstance()->config->all();
    }
}
