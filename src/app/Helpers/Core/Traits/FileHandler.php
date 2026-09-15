<?php

namespace App\Helpers\Core\Traits;

use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;

trait FileHandler
{
    protected string $storagePrefix = '';
    protected bool $keepOriginalName = false;
    public function getStorageDisk(): \Illuminate\Contracts\Filesystem\Filesystem
    {
        return Storage::disk(config('filesystems.default'));
    }
    private function withOriginalName($flag = true)
    {
        $this->keepOriginalName = $flag;
        return $this;
    }
    private function getDefaultName(): string
    {
        return Str::random(40);
    }
    private function getOriginalFileName(UploadedFile $file): string
    {
        return $file->getClientOriginalName(). "." . $file->getClientOriginalExtension();
    }
    private function generateUploadingFileName(UploadedFile $file): string
    {
        $name = $this->getDefaultName();
        if ($this->keepOriginalName) {
            $name = Str::of($file->getClientOriginalName())
                ->replaceMatches("/[.].*/", '')
                ->snake()
                ->limit(30, '');
            $name = $name . "-" . uniqid();
        }
        return $name . "." . $file->getClientOriginalExtension();
    }
    public function makeImage(UploadedFile $file, $height = 300): \Intervention\Image\Image
    {
        return Image::make($file)->resize(null, $height, function ($constraint) {
            $constraint->aspectRatio();
        })->save();
    }
    public function saveImage(UploadedFile $file, $subdirectory = 'logo', $height = 300): object
    {
        $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
        $file_path =  $subdirectory . '/' . $file_name;
        // Storage::disk('s3')->putFileAs('/' . $subdirectory . '/', $file, $file_name);

        try {
            $file = $this->makeImage($file, $height)->stream(); //resizing image
            $this->getStorageDisk()->put( '/' . $file_path, $file);
        } catch (Exception $exception) {
            $this->getStorageDisk()->putFileAs('/' . $subdirectory, $file, $file_name);
        }

        $uploadedImagePath = $file_path;
        return (object)[
            "success" => true,
            "message" => "File has been uploaded successfully",
            "path" => $uploadedImagePath
        ];
    }
    public function uploadImage(UploadedFile $uploadedFile = null, $folder = "logo", $height = 300)
    {
        if (is_null($uploadedFile))
            return null;
        $file = $this->saveImage($uploadedFile, $folder, $height);
        if ($file->success){
            return $file->path;
        }
        return null;
    }
    public function storeFile(UploadedFile $file, $folder = 'avatar'): string
    {
        $name = $this->generateUploadingFileName($file);
        $file->storeAs("{$this->storagePrefix}/{$folder}", $name, ['disk' => config('filesystems.default')]);
        return $folder . '/' . $name;
    }
    public function isFile(string $path = null): bool
    {
        if(!$path) return false;
        if($path === '') return false;
        return $this->getStorageDisk()->exists($path);
    }
    public function deleteFile(string $path = null): bool
    {
        if ($this->isFile($path)){
            return $this->getStorageDisk()->delete($path);
        }
        return false;
    }
    public function deleteImage(string $path = null): bool
    {
        return $this->deleteFile($path);
    }
    public function deleteMultipleFile($paths)
    {
        foreach ($paths as $path) {
            $this->deleteFile($path);
        }
        return true;
    }
    public function filePath(string $path = null): ?string
    {
        if ($this->isFile($path))
            return $this->getStorageDisk()->url($path);
        return null;
    }

    public function storeFileMoveToRoot(UploadedFile $file, $folder = 'avatar'): string
    {
        $name = $file->getClientOriginalName();

        $file->storeAs("{$this->storagePrefix}/{$folder}", $name);

        if (env('APP_ENV') === 'production') {

            $path = base_path();
            $path = str_replace('src', '', $path);

            File::move(storage_path() . '/app/public/' . $folder . '/' . $name, $path . $name);

        } else {
            File::move(storage_path() . '/app/public/' . $folder . '/' . $name, base_path() . '/' . $name);
        }


        return Storage::url($folder . '/' . $name);
    }
}