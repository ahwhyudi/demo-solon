<?php

namespace App\Http\Controllers;

use App\Models\FileJobDivisi;
use App\Models\JobDivisi;
use App\Services\FileStoreServis;
use Illuminate\Http\Request;

class FileController extends Controller
{

    public function __construct(protected FileStoreServis $fileStoreServis) {}

    public function uploadFile(Request $request)
    {
        $request->validate([
            "file" => "required|max:7168", // max 7mb
            "nama" => "required",
            "job_divisi_id" => "required",
        ]);

        $jobDivisi = JobDivisi::find($request->job_divisi_id, "*");

        $file = $request->file("file");
        $path = "job_divisi/$request->nama";
        $this->fileStoreServis->uploadFile($file, $path, $jobDivisi, $request->nama);

        return redirect()->back()->with("success", "File $request->nama berhasil diupload");
    }

    public function destroy(string $id)
    {
        $this->fileStoreServis->deleteFile(FileJobDivisi::find($id, "*"));
        return redirect()->back()->with("success", "File berhasil dihapus");
    }
}
