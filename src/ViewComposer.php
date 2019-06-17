<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-mobile-first
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\MobileFirst;

use Illuminate\View\View;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Str;
use InvalidArgumentException;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since  1.0.0
 */
class ViewComposer
{
    /**
     * Agent help to detect user device.
     *
     * @var Agent
     */
    protected $agent;

    /**
     * Composer config.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Resolved path cached.
     *
     * @var array
     */
    protected $resolvedPaths = [];

    /**
     * Create a new ViewComposer instance.
     *
     * @param \Jenssegers\Agent\Agent $agent
     * @param array $config
     */
    public function __construct(Agent $agent, array $config)
    {
        $this->agent = $agent;
        $this->config = $config;
    }

    /**
     * Compose given View instance.
     *
     * @param View $view
     */
    public function compose(View $view): void
    {
        if (($path = $view->getPath()) === $view->getName()) {
            return;
        }

        if (isset($this->resolvedPaths[$path])) {
            $view->setPath($this->resolvedPaths[$path]);
        } elseif ($resolvedPath = $this->resolvePath($view)) {
            $view->setPath($this->resolvedPaths[$path] = $resolvedPath);
        }
    }

    /**
     * Resolve path by end-user device.
     *
     * @param \Illuminate\View\View $view
     * @return string|null
     */
    protected function resolvePath(View $view): ?string
    {
        foreach ($this->config['device_sub_dirs'] as $device => $subDir) {
            if ($this->isDevice($device) && $path = $this->findSubPath($view, $subDir)) {
                return $path;
            }
        }

        return null;
    }

    /**
     * Checking current device of end-user by given value.
     *
     * @param $device
     * @return bool
     */
    protected function isDevice($device): bool
    {
        switch ($device) {
            case 'phone':
                return $this->agent->isPhone();
            case 'tablet':
                return $this->agent->isTablet();
            case 'mobile':
                return $this->agent->isMobile();
            default:
                return $this->agent->is($device);
        }
    }

    /**
     * Find sub path by view and sub dir given.
     *
     * @param View $view
     * @param string $subDir
     * @return string|null
     */
    protected function findSubPath(View $view, string $subDir): ?string
    {
        [$base, $name] = $this->parseNameSegments($view->getName());
        $name = $base.'.'.$subDir.'.'.$name;
        $factory = $view->getFactory();

        try {
            $path = $factory->getFinder()->find($name);
        } catch (InvalidArgumentException $exception) {
            return null;
        }

        if ($view->getEngine() === $factory->getEngineFromPath($path)) {
            return $path;
        }

        return null;
    }

    /**
     * Get the segments of a view name given.
     *
     * @param string $name
     * @return array|string[]
     */
    protected function parseNameSegments(string $name): array
    {
        if (! Str::contains($name, '.')) {
            return ['', $name];
        }

        $name = str_replace('.', '/', $name);

        return [dirname($name), basename($name)];
    }
}
