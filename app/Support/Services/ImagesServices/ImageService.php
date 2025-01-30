<?php

namespace App\Support\Services\ImagesServices;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ImageService
{

    public function checkDirectory($folder): void
    {
        if (!File::isDirectory(storage_path("app/public/$folder"))) {
            File::makeDirectory(storage_path("app/public/$folder"), 755, true);
        }
    }

    public function storeImage($img, string $path, $type, $disk = 'spaces', $imageType = 'webp', $oldPath = null, $order = null): array
    {
        if (in_array($img->getMimeType(), [
            'application/pdf',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'text/csv'])) {
            $image = $img;
            $imageType = $img->getMimeType();
        } else {

            $image = ImageManager::gd()->read($img);
        }
        // checkDir
        $this->checkDirectory($path);
        // HandleImage
        $name = $img->hashName();

        $oldPath ? $this->destroyImg($oldPath, $disk) : null;
        return $this->saveInDir($image, $img, $name, $path, $type, $disk, $imageType, $order);
    }


    public function saveInDir($image, $request_file, $name, $path, $type, $disk = 'spaces', $imageType = 'webp', $order = null): array
    {
        if ($imageType == 'webp') {
            $image = $image->toWebp();
            $name = explode('.', $name)[0] . '.webp';
        }
        Storage::disk($disk)->put("$path/$name", $image);
        return [
            'original_name' => explode('.', $request_file->getClientOriginalName())[0] ,
            'file_name' => $name,
            'path' => $path . '/' . $name,
            'url' => Storage::disk($disk)->url("$path/$name"),
            'storage' => $disk,
            'type' => $type,
            'size' => number_format($request_file->getSize() / 1048576, 2),
            'mime_type' => $imageType,
            'order_column' => $order
        ];
    }

    public function destroyImg($imgPath, string $disk = 'spaces'): void
    {
//        dd(Storage::disk($disk));
//        dd($imgPath);
        Storage::disk($disk)->delete("$imgPath");
    }

}
