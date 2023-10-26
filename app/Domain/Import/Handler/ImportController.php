<?php

namespace App\Domain\Import\Handler;

use App\Domain\Import\Job\ImportCsv;
use App\Domain\Import\Request\Import;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportController extends Controller
{
    public function __invoke(Import $request)
    {
        $file = $request->file("file");
        $path = $file->store("import/" . $file->hashName());
        $uuid = Str::uuid()->toString();
        ImportCsv::dispatch(Storage::path($path), $uuid);

        return response()->json(["uuid" => $uuid]);
    }
}
