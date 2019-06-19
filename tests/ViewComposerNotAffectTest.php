<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-mobile-first
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\MobileFirst\Tests;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since  1.0.0
 */
class ViewComposerNotAffectTest extends TestCase
{
    public function testNormalRender()
    {
        $result = (string) $this->app['view']->make('test');
        $this->assertContains('normal hello world', $result);
    }

    protected function getEnvironmentSetUp($application): void
    {
        $application['view']->getFinder()->addLocation(__DIR__.'/resources/views');
    }
}
