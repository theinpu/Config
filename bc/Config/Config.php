<?php
/**
 * User: inpu
 * Date: 20.12.13
 * Time: 17:36
 */

namespace bc\Config;

class Config {

    /**
     * @var array
     */
    private $config = array();
    private $configFile = '';

    /**
     * @param string $configFile
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     */
    public function __construct($configFile) {
        $this->checkConfigFile($configFile);
        if(strpos($configFile, '.json') !== false) {
            $this->readJsonConfig();
        }
        elseif(strpos($configFile, '.php') !== false) {
            $this->readPhpConfig();
        }
        else {
            throw new \RuntimeException("Unsupported config file");
        }
    }

    public function getAll() {
        return $this->config;
    }

    private function checkConfigFile($configFile) {
        if(!file_exists($configFile)) {
            throw new \InvalidArgumentException("Config file not found");
        }
        $this->configFile = $configFile;
    }

    private function readJsonConfig() {
        $cfg = json_decode(file_get_contents($this->configFile), true);
        if(is_null($cfg)) {
            throw new \RuntimeException("Corrupt config file");
        }
        $this->config = $cfg;
    }

    private function readPhpConfig() {
        $cfg = require_once $this->configFile;
        if(is_null($cfg)) {
            throw new \RuntimeException("Corrupt config file");
        }
        $this->config = $cfg;
    }

    /**
     * @param string $key
     * @throws \InvalidArgumentException
     * @return mixed
     */
    public function get($key) {
        if(!array_key_exists($key, $this->config)) {
            throw new \InvalidArgumentException("Key not exists");
        }
        return $this->config[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value) {
        $this->config[$key] = $value;
    }

    /**
     * @return bool
     */
    public function save() {
        return (file_put_contents($this->configFile, json_encode($this->config)) > 0);
    }
}