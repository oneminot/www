<?php
/**
 * CommandListShellTest file
 *
 * CakePHP :  Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace Cake\Test\TestCase\Shell;

use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOutput;
use Cake\Core\App;
use Cake\Core\Plugin;
use Cake\Shell\CommandListShell;
use Cake\Shell\Task\CommandTask;
use Cake\TestSuite\TestCase;

/**
 * Class TestStringOutput
 */
class TestStringOutput extends ConsoleOutput
{

    public $output = '';

    protected function _write($message)
    {
        $this->output .= $message;
    }
}

/**
 * Class CommandListShellTest
 *
 */
class CommandListShellTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        Plugin::load(['TestPlugin', 'TestPluginTwo']);

        $this->out = new TestStringOutput();
        $io = new ConsoleIo($this->out);

        $this->Shell = $this->getMock(
            'Cake\Shell\CommandListShell',
            ['in', 'err', '_stop', 'clear'],
            [$io]
        );

        $this->Shell->Command = $this->getMock(
            'Cake\Shell\Task\CommandTask',
            ['in', '_stop', 'err', 'clear'],
            [$io]
        );
    }

    /**
     * tearDown
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->Shell);
        Plugin::unload();
    }

    /**
     * test that main finds core shells.
     *
     * @return void
     */
    public function testMain()
    {
        $this->Shell->main();
        $output = $this->out->output;

        $expected = "/\[.*TestPlugin.*\] example/";
        $this->assertRegExp($expected, $output);

        $expected = "/\[.*TestPluginTwo.*\] example, welcome/";
        $this->assertRegExp($expected, $output);

        $expected = "/\[.*CORE.*\] i18n, orm_cache, plugin_assets, server/";
        $this->assertRegExp($expected, $output);

        $expected = "/\[.*app.*\] sample/";
        $this->assertRegExp($expected, $output);
    }

    /**
     * test xml output.
     *
     * @return void
     */
    public function testMainXml()
    {
        $this->assertFalse(defined('HHVM_VERSION'), 'Remove when travis updates to hhvm 2.5');
        $this->Shell->params['xml'] = true;
        $this->Shell->main();

        $output = $this->out->output;

        $find = '<shell name="sample" call_as="sample" provider="app" help="sample -h"/>';
        $this->assertContains($find, $output);

        $find = '<shell name="orm_cache" call_as="orm_cache" provider="CORE" help="orm_cache -h"/>';
        $this->assertContains($find, $output);

        $find = '<shell name="welcome" call_as="TestPluginTwo.welcome" provider="TestPluginTwo" help="TestPluginTwo.welcome -h"/>';
        $this->assertContains($find, $output);
    }
}
