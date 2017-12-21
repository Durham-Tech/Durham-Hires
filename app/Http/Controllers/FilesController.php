<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use View;
use App\File;
use App\catalog;
use App\Http\Requests\NewFile;

class FilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('login', ['except' => ['download']]);
        $this->middleware('admin', ['except' => ['download']]);
    }

    public function download($s, File $file)
    {
        $site = Request()->get('_site');
        if ($file->site == $site->id) {
            return response()->file(base_path() . '/storage/app/files/' . $file->filename, ['Content-Disposition' => 'attachment; filename="'.$file->name.'"']);
        } else {
            abort(404);
        }

    }

    // public function store($site, NewFile $request)
    // {
    //     $site = Request()->get('_site');
    //     $item = catalog::findOrFail($request->item);
    //     if ($item->site != $site->id) {
    //         abort(403);
    //     }
    //
    //     if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
    //         abort(400);
    //     }
    //     $doc = $request->file('file');
    //
    //     $file = new File;
    //
    //     $file->site = $site->id;
    //     $file->item = $item->id;
    //     $file->name = $doc->getClientOriginalName();
    //
    //     if ($request->has('displayName') && !empty($request->displayName)) {
    //         $file->displayName = $request->displayName;
    //     } else {
    //         $file->displayName = $file->name;
    //     }
    //
    //     $file->filename = uniqid() . str_random(5) . '.' . $doc->getClientOriginalExtension();
    //
    //     $doc->storeAs('files', $file->filename);
    //
    //     $file->save();
    //
    //     return 'OK';
    // }

    public function destroy($site, $id)
    {
        $site = Request()->get('_site');
        $file = File::findOrFail($id);
        if ($file->site == $site->id) {
            $file->delete();
        }
        Storage::delete('files/' . $file->filename);
    }
}
