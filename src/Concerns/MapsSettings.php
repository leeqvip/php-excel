<?php

namespace TechOne\Excel\Concerns;

trait MapsSettings
{
    /**
     * @param array $config
     */
    public function applySettings(array $config)
    {
        if (isset($config['time_limit'])) {
            set_time_limit($config['time_limit']);
        }
        if (isset($config['memory_limit'])) {
            ini_set('memory_limit', $config['memory_limit']);
        }
    }
}
