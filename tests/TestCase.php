<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-mobile-first
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\MobileFirst\Tests;

use Jenssegers\Agent\AgentServiceProvider;
use VXM\MobileFirst\MobileFirstServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since  1.0.0
 */
abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [AgentServiceProvider::class, MobileFirstServiceProvider::class];
    }
}
