<?php
/**
 * User: inpu
 * Date: 22.12.13
 * Time: 1:25
 */

namespace aascms\Config;

class ConfigManager {

    /**
     * @var Config[]
     */
    private static $configs = array();

    /**
     * @param string $file
     * @return Config
     */
    public static function get($file) {
        if(!array_key_exists($file, self::$configs)) {
            self::$configs[$file] = new Config($file);
        }
        return self::$configs[$file];
    }

} 