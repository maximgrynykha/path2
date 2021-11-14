<?php

namespace Ismaxim\Path2;

final class Path
{
    /**
     * Return correct path to needed place 
     * at file system. Based on current OS.
     * 
     * ⚠️ This process can take a long time. Not use it in cycles!
     * 
     * @param string $path
     * @param string $from
     * 
     * @return string
     */
    public static function to(string $path, string $from = ""): string
    {
        $from = self::process($from ?: getcwd());
        $path = self::process($path);

        return (strpos($path, $from) === false) ? $from.$path : $path; 
    }

    /**
     * Remove excess slashes from the path and  
     * replace them appropriate to the current OS.
     * 
     * @param string $path
     * 
     * @return string
     */
    private static function process(string $path): string
    {
        $path = str_replace(["/", "\\"], DIRECTORY_SEPARATOR, $path);
        $path = preg_replace('#[\\\/]+#', DIRECTORY_SEPARATOR, $path);
        $path = trim($path, DIRECTORY_SEPARATOR);
        
        return (self::isDir($path) && ! self::isFile($path)) 
            ? $path.DIRECTORY_SEPARATOR
            : $path;
    }

    /**
     * @param string $path
     * 
     * @return bool
     */
    private static function isDir(string $path): bool
    {
        // https://bit.ly/3iWnupn
        return ! (bool) pathinfo($path, PATHINFO_EXTENSION);
    }

    /**
     * @param string $path
     * 
     * @return bool
     */
    private static function isFile(string $path): bool
    {
        // https://bit.ly/3iWnupn
        return (bool) pathinfo($path, PATHINFO_EXTENSION);
    }
}