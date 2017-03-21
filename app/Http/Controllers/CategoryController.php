<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use View;

class CategoryController extends Controller
{
    public function __construct() {
        $this->middleware('login');
        $this->middleware('admin');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cats = [];
        $data = Category::orderBy('orderOf')->get();

        foreach ($data as $cat){
            if(empty($cat->subCatOf)){
                $cats[$cat->id] = [$cat->name, NULL];

                foreach ($data as $subCat){
                    if ($subCat->subCatOf == $cat->id){
                        // $all[] = array($subCat->name, TRUE);
                        $cats[$subCat->id] = ['&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- ' . $subCat->name, $cat->id];
                    }
                }

            }
        }

        return view('categories.view')->with(['cats'=>$cats]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cats = [];
        // $data = \App\Category::orderby('orderof')->get();
        $data = Category::orderby('orderof')->get();

        $cats[''] = 'None';
        foreach ($data as $cat){
            if(empty($cat->subCatOf)){
                $cats[$cat->id] = $cat->name;
            }
        }

        return View::make('categories.edit')
            ->with(['cats' => $cats]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $item = new catalog;

        if (!empty($request->image)){
            $imageName = $item->id . '.' .
                $request->file('image')->getClientOriginalExtension();
            $item->image = 'thumb_' . $imageName;
        }

        $item->description = $request->description;
        $item->details = $request->details;
        $item->quantity = $request->quantity;
        $item->category = $request->category;

        $item->save();


        if (!empty($request->image)){
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
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $cats = [];
        $data = Category::orderBy('orderOf')->get();

        $cats[''] = 'None';
        foreach ($data as $cat){
            if(empty($cat->subCatOf)){
                $cats[$cat->id] = $cat->name;
            }
        }

        $old = Category::findOrFail($id);

        return View::make('categories.edit')->with(['old' => $old, 'cats' => $cats]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
       $category->fill($request->all());
       $category->save();

       return redirect('/categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
       $category->delete();
       return redirect('/categories');
    }
}
