<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-mobile-first
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\MobileFirst\Tests;

use VXM\MobileFirst\MobileRedirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Http\Kernel as KernelContract;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since  1.0.0
 */
class MiddlewareTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupRoutes();
    }

    protected function setupRoutes(): void
    {
        Route::get('/', function () {

            return 'hello world';
        });
    }

    protected function getEnvironmentSetUp($application)
    {
        $application->make(KernelContract::class)->pushMiddleware(MobileRedirect::class);
    }

    public function testDesktopNotRedirect()
    {
        $response = $this->get('/');
        $this->assertEquals('hello world', $response->content());
        $this->assertFalse($response->headers->get('location', false));
    }

    public function testMobileRedirect()
    {
        $response = $this->get('/', [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
        ]);
        $this->assertContains('Redirecting to', $response->content());
        $this->assertNotFalse($response->headers->get('location', false));
    }

    public function testMobileShouldNotRedirect()
    {
        $response = $this->post('/', [
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 12_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Mobile/15E148',
        ]);
        $this->assertNotContains('Redirecting to', $response->content());
        $this->assertFalse($response->headers->get('location', false));
    }
}
