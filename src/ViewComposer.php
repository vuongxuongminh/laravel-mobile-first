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
use InvalidArgumentException;
use Illuminate\Filesystem\Filesystem;

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
    protected $deviceSubDirs;

    /**
     * Resolved path cached.
     *
     * @var array
     */
    protected $resolvedPaths = [];

    /**
     * Helpful resolve path.
     *
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new ViewComposer instance.
     *
     * @param Agent $agent
     * @param array $deviceSubDirs
     * @param Filesystem $files
     */
    public function __construct(Agent $agent, array $deviceSubDirs, Filesystem $files)
    {
        $this->agent = $agent;
        $this->deviceSubDirs = $deviceSubDirs;
        $this->files = $files;
    }

    /**
     * Compose given View instance.
     *
     * @param View $view
     */
    public function compose(View $view): void
    {
        $path = $view->getPath();

        if (isset($this->resolvedPaths[$path])) {
            $resolvedPath = $this->resolvedPaths[$path];
        } else {
            $resolvedPath = $this->resolvePath($view);
        }

        if ($resolvedPath) {
            $view->setPath($this->resolvedPaths[$path] = $resolvedPath);
        } else {
            $this->resolvedPaths[$path] = false;
        }
    }

    /**
     * Resolve path by end-user device.
     *
     * @param View $view
     * @return string|null
     */
    protected function resolvePath(View $view): ?string
    {
        foreach ($this->deviceSubDirs as $device => $subDir) {
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
     * Find sub path.
     *
     * @param View $view
     * @param string $subDir
     * @return string|null
     */
    protected function findSubPath(View $view, string $subDir): ?string
    {
        if ($view->getName() !== $view->getPath()) {
            return $this->findSubViewPath($view, $subDir);
        } else {
            return $this->findSubNativePath($view, $subDir);
        }
    }

    /**
     * Find sub view path.
     *
     * @param View $view
     * @param string $subDir
     * @return string|null
     */
    protected function findSubViewPath(View $view, string $subDir): ?string
    {
        $rawPath = str_replace('.', '/', $view->getName());
        $base = $this->files->dirname($rawPath);
        $name = $this->files->name($rawPath);
        $path = ltrim($base.'.'.$subDir.'.'.$name, '.');
        $factory = $view->getFactory();

        try {
            $path = $factory->getFinder()->find($path);
        } catch (InvalidArgumentException $exception) {
            return null;
        }

        if ($view->getEngine() === $factory->getEngineFromPath($path)) {
            return $path;
        }

        return null;
    }

    /**
     * Find sub native path.
     *
     * @param View $view
     * @param string $subDir
     * @return string|null
     */
    protected function findSubNativePath(View $view, string $subDir): ?string
    {
        $path = $view->getPath();
        $dirname = $this->files->dirname($path);
        $basename = $this->files->basename($path);

        if ($this->files->exists($path = $dirname.'/'.$subDir.'/'.$basename)) {
            return $path;
        }

        return null;
    }
}
