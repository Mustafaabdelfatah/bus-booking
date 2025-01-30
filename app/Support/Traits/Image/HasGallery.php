<?php

namespace App\Support\Traits\Image;


use App\Support\Enum\FileTypesEnum;
use Illuminate\Support\Facades\Storage;

trait HasGallery
{

    public function getLogoUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {


            $logo = $this->files()->whereType(FileTypesEnum::LOGO->value)->latest()?->first();
            if ($logo) return $logo?->file_url;


        }


    }

    public function getProfileUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {
            $logo = $this->files()->whereType(FileTypesEnum::PROFILE->value)->latest()?->first();
            if ($logo) return $logo?->file_url;
        }
    }


    public function getStoreLogoUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {
            $logo = $this->files()->whereType(FileTypesEnum::STORE_LOGO->value)->latest()?->first();
            if ($logo) return $logo?->file_url;
        }
    }

    public function getStoreCoverUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {
            $cover = $this->files()->whereType(FileTypesEnum::STORE_COVER->value)->latest()?->first();
            if ($cover) return $cover?->file_url;
        }
    }

    public function getItemImageUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {

            $logo = $this->files()->whereType(FileTypesEnum::ITEM->value)->latest()?->first();
            if ($logo) return $logo->storage == 'spaces' ? Storage::disk($logo->storage)->temporaryUrl($logo?->path, now()->addDay())
            : Storage::disk($logo->storage)->url($logo?->path);

        }
        return '';

    }

    public function getTicketImageUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {

            $logo = $this->files()->whereType(FileTypesEnum::TICKET->value)->latest()?->first();
            if ($logo) return $logo?->file_url;

        }


    }

    public function getBannerUrlAttribute()
    {
        if ($this->files()->exists() && count($this->files) > 0) {

            $logo = $this->files()->whereType(FileTypesEnum::BANNER->value)->latest()?->first();
            if ($logo) return $logo?->file_url;

        }


    }


}
