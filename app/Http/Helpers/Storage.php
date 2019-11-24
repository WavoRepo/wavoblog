<?php

namespace App\Http\Helpers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Filesystem\FilesystemManager;

class Storage extends Directory
{
    protected $storage;

    protected $storagePath = [
        'public' => 'app/public/'
    ];

    public function __construct( FilesystemManager $storage, Filesystem $filesystem )
    {
        $this->storage = $storage;
        $this->filesystem = $filesystem;
    }

    /**
     * Save the user profile image.
     *
     * @param  integer $userId
     * @param  file $file
     * @return void
     */
    public function saveUserProfileImage($userId, $file)
    {
        $this->directory = 'user_' . $userId;
        $this->fileName = 'user_image_' . $userId . date("Ymdgi") . '.png';


        $this->cleanOrCreateDirectory('public');

        $this->upload('public', $file);
    }

    /**
     * Save the post featured image.
     *
     * @param  integer $postId
     * @param  file $file
     * @return void
     */
    public function savePostFeaturedImage($postId, $file)
    {
        $this->directory = 'post' . $postId;
        $this->fileName = 'features_image_' . $postId . date("Ymdgi") . '.' . $file->getClientOriginalExtension();


        $this->cleanOrCreateDirectory('public');

        $this->upload('public', $file);
    }

    /**
     * Get the public path f the saved image
     *
     * @return String
     */
    public function getPublicPath()
    {
        $filePath = DIRECTORY_SEPARATOR . 'storage'. DIRECTORY_SEPARATOR;
        $filePath .= $this->normalizePath( $this->directory . DIRECTORY_SEPARATOR . $this->fileName);
        return $filePath;
    }

    /**
     * Remove previous upload on the specified directory or create a directory.
     *
     * @param  string $directory
     * @return void
     */
    public function cleanOrCreateDirectory($disk)
    {
        $directory = $this->getPath($disk);

        if(!$this->cleanDirectory($directory)) {
            $this->createDirectory($directory);
        }
    }

    /**
     * upload
     *
     * @param  string $filepath
     * @return void
     */
    protected function upload($disk, $file)
    {
        $this->storage ->disk($disk)->putFileAs($this->directory, $file, $this->fileName);
    }

    /**
     * @param string|null $fileName
     *
     * @throws \Sven\ArtisanView\Exceptions\UnsupportedException
     *
     * @return string
     */
    public function getPath($disk, $fileName = null)
    {
        $path = storage_path($this->storagePath[$disk]) . $this->directory;

        // return $path;
        return $this->normalizePath($path . DIRECTORY_SEPARATOR . $fileName);
    }
}
