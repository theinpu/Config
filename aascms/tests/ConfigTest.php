<?php
/**
 * User: inpu
 * Date: 21.12.13
 * Time: 1:26
 */

namespace aascms\tests\Config;

use aascms\Config\Config;
use aascms\Config\ConfigManager;

class ConfigTest extends \PHPUnit_Framework_TestCase {

    const ConfigFile = './cfg/config.json';
    private $configs;

    const CorruptConfig = "./cfg/corrupt.json";

    public function testBaseConfig() {
        $cfg = new Config(self::ConfigFile);
        $this->assertInstanceOf('aascms\\Config\\Config', $cfg);
        $this->assertEquals($this->configs, $cfg->getAll());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongConfigFile() {
        new Config("wrong");
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCorruptConfig() {
        new Config(self::CorruptConfig);
    }

    public function testSingleConfigItem() {
        $cfg = new Config(self::ConfigFile);
        $this->assertEquals($this->configs['item1'], $cfg->get('item1'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNonExistsConfigItem() {
        $cfg = new Config(self::ConfigFile);
        $cfg->get("wrong key");
    }

    public function testUpdateConfig() {
        $cfg = new Config(self::ConfigFile);
        $cfg->set('item1', 'updated');
        $cfg->set('new item', 'test');
        $this->assertNotEquals($this->configs['item1'], $cfg->get('item1'));
        $oldCfg = new Config(self::ConfigFile);
        $this->assertNotEquals($cfg->get('item1'), $oldCfg->get('item1'));
        $this->assertTrue($cfg->save());
        $savedConfig = new Config(self::ConfigFile);
        $this->assertEquals($cfg->get('item1'), $savedConfig->get('item1'));
        $this->assertEquals($cfg->get('new item'), $savedConfig->get('new item'));
    }

    public function testConfigManager() {
        $cfg = ConfigManager::get(self::ConfigFile);
        $this->assertInstanceOf('aascms\\Config\\Config', $cfg);
    }

    protected function setUp() {
        $this->configs = array(
            'item1' => 'value',
            'array' => array(
                'item1', 'item2', 'item3'
            )
        );
        file_put_contents(self::ConfigFile, json_encode($this->configs));
        touch(self::CorruptConfig);
    }

    protected function tearDown() {
        unlink(self::ConfigFile);
        unlink(self::CorruptConfig);
    }
}
 