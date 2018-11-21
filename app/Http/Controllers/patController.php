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
          'id' => array(
                    'nullable',
                    'regex:/^[A-z1-9\-][A-z0-9\-]*$|\d+\+/',
                    'max:255'
                  )
        ],
        [
          'id.regex' => 'Only numbers and letters are allowed & cannot start with zero.',
        ]);

        $site = Request()->get('_site');

        if ($request->id != NULL && substr($request->id, -1) == "+"){
          $testNum = (int)substr($request->id, 0, -1);
          $request->id = NULL;
        } else {
          $testNum = 1;
        }

        if ($request->id == NULL){
          $currentItems = patItem::where('site', $site->id)->get();

          $found = False;
          while(True){
            $test = (string)$testNum;
            foreach ($currentItems as $item) {
              if ($item->patID == $test){
                $found = True;
                break;
              }
            }
            if (!$found){
              break;
            } else {
              $found = False;
              $testNum++;
            }
          }

          return redirect()->route('pat.newRecord', ['site' => $site->slug, 'item' => (string)$testNum]);
        } else {
          return redirect()->route('pat.newRecord', ['site' => $site->slug, 'item' => $request->id]);
        }
    }

    public function recordIndex($s, $id){
      $site = Request()->get('_site');
      $patID = str_replace("-", "", strtoupper($id));
      $item = patItem::where('site', $site->id)->where('patID', $patID)->first();

      if ($item == NULL){
        $item = new patItem();
        $item->patID = $patID;
      }

      return view('pat.testing.record')
                  ->with(['item' => $item]);
    }

    public function record(NewPatTest $request)
      {
        $this->validate($request, [
          'id' => array(
                    'required',
                    'regex:/^[A-Z1-9][A-Z0-9]*$/',
                    'max:255'
                  )
        ]);

        $site = Request()->get('_site');
        $item = patItem::where('site', $site->id)->where('patID', $request->id)->first();

        if ($item == NULL){
          $item = new patItem;
          $item->site = $site->id;
          $item->patID = strtoupper($request->id);
        }

        $item->description = $request->description;
        $item->fuse = $request->fuse;
        $item->cable_length = $request->cable_length;
        $item->last_test = $request->date;
        $item->type = $request->type;

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

        return redirect()->route('pat.testing', ['site' => $site->slug])
            ->with('successMsg', 'PAT record ' . $item->patID . ' successfully saved.');
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
                        $item->description . (($item->cable_length != NULL && $item->cable_length > 0) ? (" (" . $item->cable_length . "m)") : ""),
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
