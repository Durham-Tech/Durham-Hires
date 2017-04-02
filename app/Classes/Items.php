<?php
namespace App\Classes;

use DB;
use \App\booked_items;
use App\Bookings;

class Item
{
    public $id;
    public $description;
    public $image;
    public $dayPrice;
    public $weekPrice;
    public $details;
    public $quantity;
    public $available;
    public $booked;

    public function __construct($item)
    {
        $this->description = $item->description;
        $this->details = $item->details;
        $this->image = $item->image;
        $this->quantity = $item->quantity;
        $this->booked = 0;
        $this->id = $item->id;
        $this->dayPrice = $item->dayPrice;
        $this->weekPrice = $item->weekPrice;
    }
}

class Category
{
    public $all = [];
    public $name;
    public $sub;
    public $id;

    public function __construct($id, $name, $sub)
    {
        $this->id = $id;
        $this->name = $name;
        $this->sub = $sub;
    }
}

class Items
{
    public function getAll()
    {
        $all = [];

        $cats = \App\Category::orderBy('orderOf')->get();
        $catalog = \App\catalog::orderBy('orderOf')->get();

        foreach ($cats as $cat) {
            if (empty($cat->subCatOf)) {
                $all[$cat->id] = new Category($cat->id, $cat->name, false);
                // $all[] = array($cat->name, FALSE);

                foreach ($cats as $subCat) {
                    if ($subCat->subCatOf == $cat->id) {
                        // $all[] = array($subCat->name, TRUE);
                        $all[$subCat->id] = new Category($subCat->id, $subCat->name, true);
                    }
                }
            }
        }

        foreach ($catalog as $item) {
            if (isset($all[$item->category])) {
                $all[$item->category]->all[] = new Item($item);
            }
        }
        return $all;

        // print_r($this->all);
    }

    public function getAvalible($currentBooking)
    {
        $id = $currentBooking->id;
        $start = strtotime($currentBooking->start);
        $end = strtotime($currentBooking->end);
        $max = \App\catalog::max('id') + 1;
        $unavalible = array_fill(0, $max, 0);
        $times=[];
        $timeDB = DB::table('bookings')
          ->select(DB::raw('bookings.id, UNIX_TIMESTAMP(start) as start, UNIX_TIMESTAMP(end) as end'))
          ->where('id', '!=', $id)
          ->whereRaw("UNIX_TIMESTAMP(start) < " . $end)
          ->whereRaw("UNIX_TIMESTAMP(end) > " . $start)
          ->get();
        $booked = DB::table('bookings')
          ->select(DB::raw('bookings.id, UNIX_TIMESTAMP(start) as start, UNIX_TIMESTAMP(end) as end, item, number'))
          ->where('bookings.id', '!=', $id)
          ->whereRaw("UNIX_TIMESTAMP(start) < " . $end)
          ->whereRaw("UNIX_TIMESTAMP(end) > " . $start)
          ->join('booked_items', 'bookings.id', '=', 'booked_items.bookingID')
          ->get();

        $currentBooked = DB::table('booked_items')
            ->where('bookingID', $id)
            ->get()
            ->keyBy('item')
            ->toArray();

        $times[] = $start;
        foreach ($timeDB as $x) {
            if ($x->start > $start) {
                $times[] = $x->start;
            }
            if ($x->end < $end) {
                $times[] = $x->end;
            }
        }
        $times[] = $end;
        $times = array_unique($times);
        sort($times);

        for ($i = 0; $i < count($times) - 1; $i++) {
            $tempMax = [];
            foreach ($booked as $x) {
                if ($x->start <= $times[$i] && $x->end >= $times[$i + 1]) {
                    $tempMax[$x->item] = isset($tempMax[$x->item]) ? $tempMax[$x->item] + $x->number : $x->number;
                }
            }
            foreach ($tempMax as $item => $number) {
                $unavalible[$item] = $unavalible[$item] < $number ? $number : $unavalible[$item];
            }
        }

        $all = $this->getAll();
        foreach ($all as $cat) {
            foreach ($cat->all as $item) {
                $item->available = $item->quantity - $unavalible[$item->id];
                $item->booked = isset($currentBooked[$item->id]) ? $currentBooked[$item->id]->number : 0;
            }
        }

        return $all;
    }

    public function getAllArray()
    {
        $all = [];

        $catalog = \App\catalog::all();

        foreach ($catalog as $item) {
            $all[$item->id] = new Item($item);
        }
        return $all;
    }

    public function getAvalibleArray($currentBooking)
    {
        $id = $currentBooking->id;
        $start = strtotime($currentBooking->start);
        $end = strtotime($currentBooking->end);
        $max = \App\catalog::max('id') + 1;
        $unavalible = array_fill(0, $max, 0);
        $times=[];
        $timeDB = DB::table('bookings')
          ->select(DB::raw('bookings.id, UNIX_TIMESTAMP(start) as start, UNIX_TIMESTAMP(end) as end'))
          ->where('id', '!=', $id)
          ->whereRaw("UNIX_TIMESTAMP(start) < " . $end)
          ->whereRaw("UNIX_TIMESTAMP(end) > " . $start)
          ->get();
        $booked = DB::table('bookings')
          ->select(DB::raw('bookings.id, UNIX_TIMESTAMP(start) as start, UNIX_TIMESTAMP(end) as end, item, number'))
          ->where('bookings.id', '!=', $id)
          ->whereRaw("UNIX_TIMESTAMP(start) < " . $end)
          ->whereRaw("UNIX_TIMESTAMP(end) > " . $start)
          ->join('booked_items', 'bookings.id', '=', 'booked_items.bookingID')
          ->get();

        $currentBooked = DB::table('booked_items')
            ->where('bookingID', $id)
            ->get()
            ->keyBy('item')
            ->toArray();

        $times[] = $start;
        foreach ($timeDB as $x) {
            if ($x->start > $start) {
                $times[] = $x->start;
            }
            if ($x->end < $end) {
                $times[] = $x->end;
            }
        }
        $times[] = $end;
        $times = array_unique($times);
        sort($times);

        for ($i = 0; $i < count($times) - 1; $i++) {
            $tempMax = [];
            foreach ($booked as $x) {
                if ($x->start <= $times[$i] && $x->end >= $times[$i + 1]) {
                    $tempMax[$x->item] = isset($tempMax[$x->item]) ? $tempMax[$x->item] + $x->number : $x->number;
                }
            }
            foreach ($tempMax as $item => $number) {
                $unavalible[$item] = $unavalible[$item] < $number ? $number : $unavalible[$item];
            }
        }

        $all = $this->getAllArray();
        foreach ($all as $item) {
            $item->available = $item->quantity - $unavalible[$item->id];
            $item->booked = isset($currentBooked[$item->id]) ? $currentBooked[$item->id]->number : 0;
        }

        return $all;
    }
}
