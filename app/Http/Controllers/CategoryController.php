<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use View;
use App\Http\Requests\NewCat;

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

        return view('settings.categories.view')->with(['cats'=>$cats]);
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

        return View::make('settings.categories.edit')
            ->with(['cats' => $cats]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewCat $request)
    {
      $cat = new Category;

      $cat->name = $request->name;
      $cat->subCatOf = $request->subCatOf;

      if (isset($request->orderOf)){
        $cat->orderOf = $request->orderOf;
      }

      $cat->save();
      return redirect('/categories');
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

        if ($old->orderOf == 999){
          $old->orderOf = '';
        }

        return View::make('settings.categories.edit')->with(['old' => $old, 'cats' => $cats]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(NewCat $request, Category $category)
    {
      $category->name = $request->name;
      $category->subCatOf = $request->subCatOf;

      if (isset($request->orderOf)){
      $category->orderOf = $request->orderOf;
      }

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
