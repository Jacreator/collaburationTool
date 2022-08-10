<?php

namespace App\Helpers\Routes;

class RouteHelper
{
    /**
     * Get a list of files from a folder.
     * 
     * @param string $folder - folder name
     * 
     * @return array - list of files
     */
    public static function includeRouteFiles(string $folder)
    {
        $dirIterator = new \RecursiveDirectoryIterator($folder);

        /**
         * Iterate over the directory and get all the files
         * 
         * @var \RecursiveDirectoryIterator | \RecursiveIteratorIterator $iterator 
         */
        $iterator = new \RecursiveIteratorIterator($dirIterator);

        while ($iterator->valid()) {
            if (!$iterator->isDot()
                && $iterator->isFile()
                && $iterator->current()->getExtension() === 'php'
                && $iterator->isReadable()
            ) {
                include $iterator->key();
            }
            $iterator->next();
        }
    }
}
