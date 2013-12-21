<?php
/**
 * User: inpu
 * Date: 21.12.13
 * Time: 1:26
 */

namespace aascms\tests\Config;

use aascms\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase {

    const ConfigFile = './cfg/config.json';
    private $configs;

    public function testBaseConfig() {
        $cfg = new Config(self::ConfigFile);
        $this->assertInstanceOf('aascms\\Config\\Config', $cfg);
        $this->assertEquals($this->configs, $cfg->getAll());
    }

    protected function setUp() {
        $this->configs = array(
            'item1' => 'value',
            'array' => array(
                'item1', 'item2', 'item3'
            )
        );
        file_put_contents(self::ConfigFile, json_encode($this->configs));
    }

    protected function tearDown() {
        unlink(self::ConfigFile);
    }
}
 