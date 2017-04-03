<?php

namespace App\Http\Controllers;

use App\catalog;
use Illuminate\Http\Request;
use App\Classes\Items;
use View;
use App\Http\Requests\NewItem;
use Image;

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
        $Items = new Items;
        $data = $Items->getAll();
        return View::make('items.index')->with(['data'=>$data, 'edit'=>false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats = [];
        $data = \App\Category::orderBy('orderOf')->get();

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
        return View::make('items.edit')->with(['old' => $old, 'cat'=>$cats]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewItem $request)
    {
        $item = new catalog;

        if (!empty($request->image)) {
            $imageName = $item->id . '_0.' .
                $request->file('image')->getClientOriginalExtension();
            $item->image = $imageName;
        }

        $item->description = $request->description;
        $item->details = $request->details;
        $item->quantity = $request->quantity;
        $item->category = $request->category;
        $item->dayPrice = $request->dayPrice;
        $item->weekPrice = $request->weekPrice;

        if (isset($request->orderOf)) {
            $item->orderOf = $request->orderOf;
        }

        $item->save();


        if (!empty($request->image)) {
            $request->file('image')->move(
                base_path() . '/public/images/catalog/', $imageName
            );

            $img = Image::make('images/catalog/' . $imageName)->resize(60, 60, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save('images/catalog/thumb_' . $imageName);
        }
        if ($request->next == 'Save and New') {
            return redirect('/items/create');
        } else {
            return redirect('/items');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function show(catalog $catalog, $id)
    {
        $item = catalog::findOrFail($id);
        return View::make('items.view')->with(['item' => $item]);
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cats = [];
        $data = \App\Category::orderBy('orderOf')->get();

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

        if ($old->orderOf == 999) {
            $old->orderOf = '';
        }

        return View::make('items.edit')->with(['old' => $old, 'cat'=>$cats]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function update(NewItem $request, catalog $catalog, $id)
    {
        $cat = catalog::findOrFail($id);

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
        $cat->details = $request->details;
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

            $img = Image::make('images/catalog/' . $imageName)->resize(60, 60, function ($constraint) {
                $constraint->aspectRatio();
            });

            $img->save('images/catalog/thumb_' . $imageName);
        }

        return redirect('/items');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\catalog  $catalog
     * @return \Illuminate\Http\Response
     */
    public function destroy(catalog $catalog, $id)
    {
        $cat = catalog::findOrFail($id);
        $cat->delete();
        return redirect('/items');
    }
}
