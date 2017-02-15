<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CAuth;
use App\catalog;
use App\Category;
use App\Http\Requests\NewItem;
use Image;

class adminController extends Controller
{
    //
    public function __construct() {
        $this->middleware('admin');
    }

    public function newItem(Request $request){
        $cats = [];
        $data = \App\Category::orderBy('orderOf')->get();

        foreach ($data as $cat){
            if(empty($cat->subCatOf)){
                $cats[$cat->id] = $cat->name;
                
                foreach ($data as $subCat){
                    if ($subCat->subCatOf == $cat->id){
                        // $all[] = array($subCat->name, TRUE);
                        $cats[$subCat->id] = '- ' . $subCat->name;
                    }
                }
                
            }
        }
        
        return view('newItem')->with(['cat'=>$cats]);
    }

    public function addItem(NewItem $request){
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




    }
}