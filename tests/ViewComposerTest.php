<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-mobile-first
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\MobileFirst\Tests;

class ViewComposerTest extends TestCase
{
    public function testViewAutoSwitchRender()
    {
        $this->app['request']->server->add([
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
        ]);
        $result = (string) $this->app['view']->make('test');
        $this->assertContains('mobile hello world', $result);
    }

    public function testNativeAutoSwitchRender()
    {
        $this->app['request']->server->add([
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
        ]);
        $result = (string) $this->app['view']->file(__DIR__.'/resources/views/test.blade.php');
        $this->assertContains('mobile hello world', $result);
    }

    protected function getEnvironmentSetUp($application): void
    {
        $application['config']->set('mobilefirst.auto_switch_view_by_device', true);
        $application['view']->getFinder()->addLocation(__DIR__.'/resources/views');
    }
}
