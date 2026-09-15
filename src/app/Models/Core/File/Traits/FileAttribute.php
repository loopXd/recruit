<?php


namespace App\Models\Core\File\Traits;


use App\Helpers\Core\Traits\FileHandler;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

trait FileAttribute
{
    use FileHandler;


    protected function fullUrl(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => Storage::disk(config('filesystems.default'))->exists($this->path)
                ? Storage::disk(config('filesystems.default'))->url($this->path) : asset($this->path),
        );
    }
}
