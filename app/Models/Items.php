<?php
namespace App\Models;

use DB;

class Item {
    public $description;
    public $image;
    public $dayPrice;
    public $weekPrice;
    public $details;
    public $quantity;
    
    public function __construct($item) {
        $this->description = $item->description;
        $this->details = $item->details;
        $this->image = $item->image;
        $this->quantity = $item->quantity;
        $this->id = $item->id;
        $this->dayPrice = $item->dayPrice;
        $this->weekPrice = $item->weekPrice;
    }
}

class Category {
    public $all = [];
    public $name;
    public $sub;
    public $id;
    
    function __construct($id, $name, $sub) {
        $this->id = $id;
        $this->name = $name;
        $this->sub = $sub;
    }
}

class Items {
    public $all = [];
    
    public function getAll() {

        #$cats = DB::select('select * from categories ORDER BY orderOf');
        $cats = \App\Category::orderBy('orderOf')->get();
        $catalog = \App\catalog::all();
        #$catalog = DB::select('select * from catalog');
        
        foreach ($cats as $cat){
            if(empty($cat->subCatOf)){
                $this->all[$cat->id] = new Category($cat->id, $cat->name, FALSE);
                // $all[] = array($cat->name, FALSE);
                
                foreach ($cats as $subCat){
                    if ($subCat->subCatOf == $cat->id){
                        // $all[] = array($subCat->name, TRUE);
                        $this->all[$subCat->id] = new Category($subCat->id, $subCat->name, TRUE);
                    }
                }
                
            }
        }
        
        foreach ($catalog as $item){
            if (isset($this->all[$item->category])){
                $this->all[$item->category]->all[] = new Item($item);
            }
        }

        // print_r($this->all);

    }
}


?>
