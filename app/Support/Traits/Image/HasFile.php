<?php

namespace App\Support\Traits\Image;

use Illuminate\Support\Facades\Storage;

trait HasFile
{
    public function getFileUrlAttribute(): string
    {
        return $this->storage == 's3' ?
            Storage::disk($this->storage)->temporaryUrl($this?->path, now()->addDay())
            : Storage::disk($this->storage)->url($this?->path);
    }
}
