<?php declare(strict_types=1);

namespace Path2;

final class Path
{
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
    public static function to(string $path, string $from = ''): string
    {
        if (! $path) return $path;

        [$cwd, $path, $from] = [getcwd(), trim($path), trim($from)];

        if ($from && str_contains($from, $cwd)) {
            $from = substr($from, mb_strpos($from, $cwd) + mb_strlen($cwd));
        }

        $from = (! $from) ? '' : self::normalize($from);
        $from = self::suffix(sprintf("$cwd%s", $from));

        $path = self::suffix(self::normalize($path));

        return (! str_contains($path, $from)) ? $from . $path : $path;
    }

    /**
     * Remove excess slashes from the path and replace
     * them with corresponding ones to the current OS.
     *
     * @param string $path
     *
     * @return string
     */
    private static function normalize(string $path): string
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
    private static function suffix(string $path): string
    {
        return (self::isDir($path) && ! self::isFile($path))
            ? $path . DIRECTORY_SEPARATOR : $path;
    }

    /**
     * @param string $path
     *
     * @see https://bit.ly/3iWnupn
     *
     * @return bool
     */
    private static function isFile(string $path): bool
    {
        return (bool) pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * @param string $path
     *
     * @return bool
     */
    private static function isDir(string $path): bool
    {
        return ! self::isFile($path);
    }
}
