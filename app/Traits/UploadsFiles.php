<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadsFiles
{
	public function uploadFile(UploadedFile $uploadFile, $folder = null, $disk = 'public', $filename = null)
	{
		$name = !is_null($filename) ? $filename : str_random(25);

		$file = $uploadFile->storeAs($folder, $name . '.' . $uploadFile->getClientOriginalExtension(), $disk);

		return $file;
	}

	public function deleteFile($folder, $disk = 'public', $filename = null)
	{
		Storage::disk($disk)->delete($filename);
	}
}