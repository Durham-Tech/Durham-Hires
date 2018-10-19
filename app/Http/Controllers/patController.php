<?php

namespace App\Http\Controllers;

use Validator;
use Response;
use App\patItem;
use App\patRecord;
use Illuminate\Http\Request;
use App\Http\Requests\NewPatTest;
use View;
use App\Classes\CAuth;

class patController extends Controller
{

    public function __construct()
    {
        $this->middleware('login');
        $this->middleware('admin');
    }

    /**
     * Testing Section
     */

    public function index()
    {
        $site = Request()->get('_site');
        return view('pat.testing.index');
    }

    public function add(Request $request)
    {
        $this->validate($request, [
          'id' => 'required|alpha_dash|max:255'
        ]);

        $site = Request()->get('_site');
        return redirect()->route('pat.newRecord', ['site' => $site->slug, 'item' => $request->id]);
    }

    public function recordIndex($s, $id){
      $site = Request()->get('_site');
      $item = patItem::where('site', $site->id)->where('patID', $id)->first();

      if ($item == NULL){
        $item = new patItem();
        $item->patID = $id;
      }

      return view('pat.testing.record')
                  ->with(['item' => $item]);
    }

    public function record(NewPatTest $request)
      {
        $site = Request()->get('_site');
        $item = patItem::where('site', $site->id)->where('patID', $request->id)->first();

        if ($item == NULL){
          $item = new patItem;
          $item->site = $site->id;
          $item->patID = $request->id;
        }

        $item->description = $request->description;
        $item->fuse = $request->fuse;
        $item->last_test = $request->date;

        $record = new patRecord;
        $record->date = $request->date;
        $record->insulation_resistance = $request->insulation_resistance;
        $record->test_current = $request->test_current;
        $record->earth_resistance = $request->earth_resistance;
        $record->touch_current = $request->touch_current;
        $record->load_current = $request->load_current;
        $record->load_power = $request->load_power;
        $record->leakage_current = $request->leakage_current;
        $record->notes = $request->notes;


        if ($request->next == 'Pass') {
          $record->pass = true;
        } else{
          $request->pass = false;
        }


        $item->save();

        $record->patID = $item->id;
        $record->save();

        return redirect()->route('pat.testing', ['site' => $site->slug]);
    }

    public function exportCSV()
    {
        $site = Request()->get('_site');
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=PAT_results.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );

        $items = patItem::where('site', $site->id)->get();
        $columns = array( 'PAT ID',
                          'Description',
                          'Last Test Date',
                          'Pass/Fail',
                          "Insulation Test Current (A)",
                          "Min Insulation Resistance (MOhm)",
                          "Max Earth Resistance (Ohm)" ,
                          "Fuse Rating (A)",
                          "Touch Current (mA)" ,
                          "Load Current (A)",
                          "Load Power (VA)",
                          "Leakage Current (mA)",
                          "Notes"
                      );

        $callback = function() use ($items, $columns)
        {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach($items as $item) {
                $details = patRecord::where('patID', $item->id)->orderBy('created_at', 'desc')->first();
                if ($details != NULL){
                  fputcsv($file, array($item->patID,
                        $item->description,
                        date('d/m/Y', strtotime($details->date)),
                        $details->pass ? "PASS" : "FAIL",
                        $details->test_current,
                        $details->insulation_resistance,
                        $details->earth_resistance,
                        $item->fuse,
                        $details->touch_current,
                        $details->load_current,
                        $details->load_power,
                        $details->leakage_current,
                        $details->notes
                  ));
                }
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }
}
