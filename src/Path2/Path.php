<?php declare(strict_types=1);

namespace Path2;

final class Path
{
    /**
     * Current working directory.
     *
     * By default, it's preceding before
     * the path that needs to be normalized.
     *
     * @var string
     */
    public readonly string $cwd;

    /**
     * Cache for normalized paths.
     *
     * @var array
     */
    private array $cache;

    /**
     * @param false|string $cwd
     *
     * @return void
     */
    public function __construct(false|string $cwd = '')
    {
        $this->cwd = ((string) $cwd) ?: (string) getcwd();
        $this->cache = [];
    }

    /**
     * Return correct path to needed place
     * at file system. Based on current OS.
     *
     * Usage:
     *
     * ```php
     * $path = new Path();
     *
     * $kinky_path = '/\/src/\\\Path2/\/\/\Path.php';
     * $normalized = $path->to($kinky_path);
     * ```
     *
     * Note you don't need to cache normalized path(s) in variables.
     * Path2 automatically caches all previously normalized paths
     * inside the Path::class instance. Therefore, next time you'll
     * call Path::to() with a kinky path that already was handled,
     * one receives a corresponding normalized path.
     *
     * @param string $path any path to the file or dir
     * @param string $from any path preceding before the main path
     *
     * @return string
     */
    public function to(string $path, string $from = ''): string
    {
        if (! $path) return $path;

        if (! isset($this->cache[$key = $path])) {
            [$cwd, $path, $from] = [$this->cwd, trim($path), trim($from)];

            if ($from && str_contains($from, $cwd)) {
                $from = substr($from, mb_strpos($from, $cwd) + mb_strlen($cwd));
                $from = $this->normalize($from);
            }

            $from = $this->suffix(sprintf("$cwd%s", $from));
            $path = $this->suffix($this->normalize($path));

            $this->cache[$key] = (! str_contains($path, $from)) ? $from . $path : $path;
        }

        return $this->cache[$key];
    }

    /**
     * Usage:
     *
     * ```php
     * $path = new Path();
     *
     * $kinky_path = '/\/src/\\\Path2/\/\/\Path.php';
     * $path_type = $path->isFile($kinky_path);
     * ```
     *
     * @param string $path
     *
     * @see https://bit.ly/3iWnupn
     *
     * @return bool
     */
    public function isFile(string $path): bool
    {
        return (bool) pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * Usage:
     *
     * ```php
     * $path = new Path();
     *
     * $kinky_path = '/\/src/\\\Path2/\/\/\Path.php';
     * $path_type = $path->isDir($kinky_path);
     * ```
     *
     * @param string $path
     *
     * @return bool
     */
    public function isDir(string $path): bool
    {
        return ! $this->isFile($path);
    }

    /**
     * Remove excess slashes from the path and replace
     * them with corresponding ones to the current OS.
     *
     * @param string $path
     *
     * @return string
     */
    private function normalize(string $path): string
    {
        $path = str_replace(["/", "\\"], DIRECTORY_SEPARATOR, $path);
        $path = preg_replace('#[\\\/]+#', DIRECTORY_SEPARATOR, $path);

        return trim($path, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $path
     *
     * @return string
     */
    private function suffix(string $path): string
    {
        return ($this->isDir($path) && ! $this->isFile($path))
            ? $path . DIRECTORY_SEPARATOR : $path;
    }
}
