<?php

namespace App\Http\Helpers;

use Illuminate\Filesystem\Filesystem;

class Directory
{
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * Remove all files of the specified directory.
     *
     * @param  string $directory
     * @return void
     */
    public function cleanDirectory($directory)
    {
        if ($this->filesystem->isDirectory($directory)) {
            $this->filesystem->cleanDirectory($directory);
            return true;
        }
        return false;
    }

    /**
     * Create the specified directory.
     *
     * @param  string $directory
     * @return void
     */
    public function createDirectory($directory)
    {
        if (!$this->filesystem->isDirectory($directory)) {
            $this->filesystem->makeDirectory($directory, 0777, true, true);
        }
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function normalizePath($path)
    {
        $withoutBackslashes = str_replace('\\', DIRECTORY_SEPARATOR, $path);

        return str_replace('/', DIRECTORY_SEPARATOR, $withoutBackslashes);
    }
}
