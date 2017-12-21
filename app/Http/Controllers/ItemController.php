<?php

namespace App\Http\Controllers;

use App\catalog;
use App\File;
use Illuminate\Http\Request;
use App\Classes\Items;
use View;
use App\Http\Requests\NewItem;
use Image;
use App\Classes\Common;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('login', ['except' => ['index', 'show']]);
        $this->middleware('admin', ['except' => ['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $site = Request()->get('_site');
        $Items = new Items;
        $data = $Items->getAll($site->id);
        return View::make('items.index')->with(['data'=>$data, 'edit'=>false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site = Request()->get('_site');
        $cats = [];
        $data = \App\Category::orderBy('orderOf')
          ->where('site', $site->id)
          ->orderBy('id')
          ->get();

        foreach ($data as $cat) {
            if (empty($cat->subCatOf)) {
                $cats[$cat->id] = $cat->name;

                foreach ($data as $subCat) {
                    if ($subCat->subCatOf == $cat->id) {
                        // $all[] = array($subCat->name, TRUE);
                        $cats[$subCat->id] = '- ' . $subCat->name;
                    }
                }
            }
        }

        $old = new catalog;
        $old->quantity = 1;

        // return View::make('items.edit')->with(['cat'=>$cats]);
        return View::make('items.edit')->with(['old' => $old, 'cat'=>$cats, 'files' => []]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewItem $request)
    {
        $site = Request()->get('_site');
        $item = new catalog;

        $item->description = $request->description;
        $item->details = Common::CleanEditorContent($request->details);
        $item->quantity = $request->quantity;
        $item->category = $request->category;
        $item->dayPrice = $request->dayPrice;
        $item->weekPrice = $request->weekPrice;
        $item->site = $site->id;

        if (isset($request->orderOf)) {
            $item->orderOf = $request->orderOf;
        }

        $item->save();


        if (!empty($request->image)) {
            $imageName = $item->id . '.' .
                $request->file('image')->getClientOriginalExtension();

            $request->file('image')->move(
                base_path() . '/public/images/catalog/', $imageName
            );

            $img = Image::make('images/catalog/' . $imageName)->resize(
                108, 108, function ($constraint) {
                    $constraint->aspectRatio();
                }
            );

            $img->save('images/catalog/thumb_' . $imageName);

            $item->image = $imageName;
            $item->save();
        }

        if (!empty($request->file('files'))) {
            foreach ($request->file('files') as $key => $doc) {
                if ($doc->isValid()) {

                    $file = new File;

                    $file->site = $site->id;
                    $file->item = $item->id;
                    $file->name = $doc->getClientOriginalName();

                    if (count($request->fileNames) > $key && $request->fileNames[$key] != null) {
                        $file->displayName = $request->fileNames[$key];
                    } else {
                        $file->displayName = $file->name;
                    }

                    $file->filename = uniqid() . str_random(5) . '.' . $doc->getClientOriginalExtension();

                    $doc->storeAs('files', $file->filename);

                    $file->save();
                }
            }
        }



        if ($request->next == 'Save and New') {
            return redirect('/' . $site->slug . '/items/create');
        } else {
            return redirect('/' . $site->slug . '/items');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\catalog $catalog
     * @return \Illuminate\Http\Response
     */
    public function show($s, catalog $catalog, $id)
    {
        $site = Request()->get('_site');
        $item = catalog::findOrFail($id);
        $files = File::where('site', $site->id)
                      ->where('item', $item->id)
                      ->get();
        if ($item->site == $site->id) {
            return View::make('items.view')->with(['item' => $item, 'files' => $files]);
        } else {
            abort(404);
        }
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\catalog $catalog
     * @return \Illuminate\Http\Response
     */
    public function edit($s, $id)
    {
        $site = Request()->get('_site');
        $cats = [];
        $data = \App\Category::where('site', $site->id)
              ->orderBy('orderOf')
              ->orderBy('id')
              ->get();

        foreach ($data as $cat) {
            if (empty($cat->subCatOf)) {
                $cats[$cat->id] = $cat->name;

                foreach ($data as $subCat) {
                    if ($subCat->subCatOf == $cat->id) {
                        // $all[] = array($subCat->name, TRUE);
                        $cats[$subCat->id] = '- ' . $subCat->name;
                    }
                }
            }
        }

        $old = catalog::findOrFail($id);

        $files = File::where('site', $site->id)
                      ->where('item', $old->id)
                      ->get();

        if ($old->orderOf == 999) {
            $old->orderOf = '';
        }

        return View::make('items.edit')->with(['old' => $old, 'cat'=>$cats, 'files' => $files]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\catalog             $catalog
     * @return \Illuminate\Http\Response
     */
    public function update($s, NewItem $request, catalog $catalog, $id)
    {
        $site = Request()->get('_site');
        $cat = catalog::where('site', $site->id)->findOrFail($id);

        if (!empty($request->image)) {
            // if (!isset($cat->image)){
            //     $imageName = $cat->id . '_0.' .
            //         $request->file('image')->getClientOriginalExtension();
            // }
            $imageName = $cat->id . '.' .
                $request->file('image')->getClientOriginalExtension();
            $cat->image = $imageName;
        }


        $cat->description = $request->description;
        $cat->details = Common::CleanEditorContent($request->details);
        $cat->quantity = $request->quantity;
        $cat->category = $request->category;
        $cat->dayPrice = $request->dayPrice;
        $cat->weekPrice = $request->weekPrice;

        if (isset($request->orderOf)) {
            $cat->orderOf = $request->orderOf;
        }

        $cat->save();


        if (!empty($request->image)) {
            $request->file('image')->move(
                base_path() . '/public/images/catalog/', $imageName
            );

            $img = Image::make('images/catalog/' . $imageName)->resize(
                108, 108, function ($constraint) {
                    $constraint->aspectRatio();
                }
            );

            $img->save('images/catalog/thumb_' . $imageName);
        }

        if (!empty($request->file('files'))) {
            foreach ($request->file('files') as $doc) {
                if ($doc->isValid()) {

                    $file = new File;

                    $file->site = $site->id;
                    $file->item = $cat->id;
                    $file->name = $doc->getClientOriginalName();

                    // if ($request->has('displayName') && !empty($request->displayName)) {
                    //     $file->displayName = $request->displayName;
                    // } else {
                    $file->displayName = $file->name;
                    // }

                    $file->filename = uniqid() . str_random(5) . '.' . $doc->getClientOriginalExtension();

                    $doc->storeAs('files', $file->filename);

                    $file->save();
                }
            }
        }

        $allFiles = File::where('site', $site->id)
                      ->where('item', $cat->id)
                      ->get();

        foreach ($allFiles as $key => $file){
            if(count($request->fileNames) > $key) {
                if($request->fileNames[$key] != null) {
                    $file->displayName = $request->fileNames[$key];
                } else {
                    $file->displayName = $file->name;
                }
                $file->save();
            }
        }

        return redirect('/' . $site->slug . '/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\catalog $catalog
     * @return \Illuminate\Http\Response
     */
    public function destroy($s, catalog $catalog, $id)
    {
        $site = Request()->get('_site');
        $cat = catalog::where('site', $site->id)->findOrFail($id);
        if ($cat->site == $site->id) {
            $cat->delete();
        }
        return redirect('/' . $site->slug . '/items');
    }
}
