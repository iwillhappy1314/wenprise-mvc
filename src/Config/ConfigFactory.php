<?php

namespace Wenprise\Mvc\Config;

class ConfigFactory implements IConfig
{
    /**
     * Config file finder instance.
     *
     * @var ConfigFinder
     */
    protected $finder;

    /**
     * Cache for loaded config files.
     *
     * @var array
     */
    protected $cache = [];

    public function __construct(ConfigFinder $finder)
    {
        $this->finder = $finder;
    }

    /**
     * Return all or specific property from a config file.
     *
     * @param string $name    The config file name or its property full name.
     * @param mixed  $default Default value if not found.
     *
     * @return mixed
     */
    public function get($name, $default = null)
    {
        $parts = explode('.', $name);
        $file  = array_shift($parts);

        if ( ! isset($this->cache[$file])) {
            try {
                $path               = $this->finder->find($file);
                $this->cache[$file] = include $path;
            } catch (\Exception $e) {
                return $default;
            }
        }

        $config = $this->cache[$file];

        if (empty($parts)) {
            return $config;
        }

        foreach ($parts as $part) {
            if (isset($config[$part])) {
                $config = $config[$part];
            } else {
                return $default;
            }
        }

        return $config;
    }
}
