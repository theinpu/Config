<?php
/**
 * User: inpu
 * Date: 21.12.13
 * Time: 1:26
 */

namespace bc\tests\Config;

use bc\config\Config;
use bc\config\ConfigManager;

class ConfigTest extends \PHPUnit_Framework_TestCase {

    const ConfigFile = './config/config.json';
    const ConfigPHPFile = './config/config.php';
    const ConfigSuggestPHPFile = './config/config2.php';
    const UnsupportedConfig = './config/unsupported.cfg';
    const CorruptConfig = "./config/corrupt";

    private $configs;

    public function testBaseJsonConfig() {
        $cfg = new Config(self::ConfigFile);
        $this->assertInstanceOf('bc\\config\\Config', $cfg);
        $this->assertEquals($this->configs, $cfg->getAll());
    }

    public function testBasePHPConfig() {
        $cfg = new Config(self::ConfigPHPFile);
        $this->assertInstanceOf('bc\\config\\Config', $cfg);
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
    public function testCorruptConfigJSON()
    {
        new Config(self::CorruptConfig . '.json');
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testCorruptConfigPHP()
    {
        new Config(self::CorruptConfig . '.php');
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
        $this->assertInstanceOf('bc\\config\\Config', $cfg);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testUnsupportedFormat() {
        new Config(self::UnsupportedConfig);
    }

    public function testSuggestSuffix()
    {
        $cfg = new Config("./config/config");
        $this->assertEquals($this->configs, $cfg->getAll());
        $cfg = new Config("./config/config2");
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
        file_put_contents(self::ConfigPHPFile, "<?php\n\n return " . var_export($this->configs, true) . ";\n");
        file_put_contents(self::ConfigSuggestPHPFile, "<?php\n\n return " . var_export($this->configs, true) . ";\n");
        touch(self::UnsupportedConfig);
        touch(self::CorruptConfig . '.json');
        touch(self::CorruptConfig . '.php');
    }

    protected function tearDown() {
        unlink(self::ConfigFile);
        unlink(self::ConfigPHPFile);
        unlink(self::ConfigSuggestPHPFile);
        unlink(self::CorruptConfig . '.json');
        unlink(self::CorruptConfig . '.php');
        unlink(self::UnsupportedConfig);
    }
}
 