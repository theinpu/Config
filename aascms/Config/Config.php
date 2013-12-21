<?php
/**
 * User: inpu
 * Date: 20.12.13
 * Time: 17:36
 */

namespace aascms\Config;

class Config {

    /**
     * @var array
     */
    private $config = array();

    public function __construct($configFile) {
        if(!file_exists($configFile)) {
            throw new \InvalidArgumentException("Config file not found");
        }
        $cfg = json_decode(file_get_contents($configFile), true);
        if(is_null($cfg)) {
            throw new \RuntimeException("Corrupt config file");
        }
        $this->config = $cfg;
    }

    public function getAll() {
        return $this->config;
    }
}