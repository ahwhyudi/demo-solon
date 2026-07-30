<?php

namespace App\Services;

use App\Models\FileJobDivisi;
use App\Models\JobDivisi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStoreServis
{
    public function uploadFile(UploadedFile $file, string $path, JobDivisi | null $jobDivisi, string $nama)
    {
        $filePath = $file->store($path, "public");

        return  FileJobDivisi::create([
            "job_divisi_id" => $jobDivisi->id ?? null,
            "tipe" => "file",
            "nama" => $nama,
            "path" => $filePath,
            "user_id" => Auth::user()->id,
        ]);
    }

    public function deleteFile(FileJobDivisi $fileJobDivisi)
    {
        if (Storage::disk('public')->exists($fileJobDivisi->path)) {
            Storage::disk('public')->delete($fileJobDivisi->path);
        }

        // 2. Hapus data dari database
        return $fileJobDivisi->delete(null);
    }
}
