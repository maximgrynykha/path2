<?php declare(strict_types=1);

namespace Path2;

final class Path
{
    /**
     * @var string
     */
    public readonly string $cwd;

    /**
     * @param bool|string $cwd
     *
     * @return void
     */
    public function __construct(bool|string $cwd = '')
    {
        $this->cwd = ((string) $cwd) ?: (string) getcwd();
    }

    /**
     * Return correct path to needed place
     * at file system. Based on current OS.
     *
     * Usage:
     *
     * ```php
     * $kinky_path = '/\/src/\\\Path2/\/\/\Path.php';
     * $normalized = Path::to($kinky_path);
     * ```
     *
     * @param string $path any path to the file or dir
     * @param string $from any path preceding before the main path
     *
     * @return string
     */
    public function to(string $path, string $from = ''): string
    {
        if (! $path) return $path;

        [$cwd, $path, $from] = [$this->cwd, trim($path), trim($from)];

        if ($from && str_contains($from, $cwd)) {
            $from = substr($from, mb_strpos($from, $cwd) + mb_strlen($cwd));
            $from = $this->normalize($from);
        }

        $from = $this->suffix(sprintf("$cwd%s", $from));
        $path = $this->suffix($this->normalize($path));

        return (! str_contains($path, $from)) ? $from . $path : $path;
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
