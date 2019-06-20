<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-mobile-first
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\MobileFirst;

use Closure;
use Illuminate\Support\Arr;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Redirect;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since  1.0.0
 */
class MobileRedirect
{
    /**
     * Mobile base url.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * Keep url path when redirect to `baseUrl`.
     *
     * @var bool
     */
    protected $keepPath;

    /**
     * Methods should redirect.
     *
     * @var array
     */
    protected $methods;

    /**
     * Redirect status code.
     *
     * @var int
     */
    protected $statusCode;

    /**
     * Create new MobileRedirect instance.
     *
     * @param string $baseUrl
     * @param bool $keepPath
     * @param int $statusCode
     */
    public function __construct(string $baseUrl, bool $keepPath, int $statusCode, array $methods)
    {
        $this->baseUrl = $baseUrl;
        $this->methods = $methods;
        $this->keepPath = $keepPath;
        $this->statusCode = $statusCode;
    }

    /**
     * Redirect to mobile site if end-user not using desktop device.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->shouldRedirect($request)) {
            return Redirect::to($this->getUrlFor($request), $this->statusCode);
        }

        return $next($request);
    }

    /**
     * Return should redirect or not by request given.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function shouldRedirect($request): bool
    {
        $agent = new Agent($request->server());

        return ! $agent->isDesktop() && in_array($request->getMethod(), $this->methods);
    }

    /**
     * Get url for request.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function getUrlFor($request): string
    {
        $url = $this->baseUrl;

        if ($this->keepPath) {
            $url = rtrim($url).$request->getPathInfo();

            if ($query = Arr::query($request->query())) {
                $url .= '?'.$query;
            }
        }

        return $url;
    }

}
