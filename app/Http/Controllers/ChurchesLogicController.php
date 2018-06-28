<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Church;
use App\Denomination;
use App\Http\Controllers\DB;

class ChurchesLogicController extends Controller
{
    public function countChurch(){
        $allChurch = Church::count();
        $verifiedChurch = Church::where('verified', '=', true)->count();
        $unverifiedChurch = Church::where('verified', '=', false)->count();
        return response()->json(['status' => true, 'allChurches' => $allChurch,
            'verifiedChurches' => $verifiedChurch,
            'unverifiedChurches' => $unverifiedChurch]);
    }

    public function countChurchByDenomination(){
        $denominationMap = [];
        $denominations = \DB::table('denominations')->get();

        foreach ($denominations as $denomination) {
            $denominationMap[] = 
                    self::getDenominationStats($denomination->name,$denomination->id);
        }  

        return response()->json(['denominationMap' => $denominationMap], 200);

        // $deno = Denomination::all('id');
        // return $deno->toArray();
            // $allChurch = Church::with('denominations')->select('denomination_id', \DB::raw('count(*) as total'))->groupBy('denomination_id')->pluck('total','denomination_id');
            // return $allChurch;
            
        // $allChurch = Church::where('denomination_id', '=', $id)->count();
        // $verifiedChurch = Church::where('denomination_id', '=', $id)->where('verified', '=', true)->count();
        // $unverifiedChurch = Church::where('denomination_id', '=', $id)->where('verified', '=', false)->count();
        // return response()->json(['status' => true, 'allChurches' => $allChurch,
        //     'verifiedChurches' => $verifiedChurch,
        //     'unverifiedChurches' => $unverifiedChurch]);
    }

    private function getDenominationStats($denominationName,$denominationId)
    {
        // $denoRuery = \DB::table('denominations')->where('id', $denominationId);
        $query = \DB::table('churches')->where('denomination_id', $denominationId);
        $churchesCount = intval($query->count('*'));
        $verifiedCount = $query->where('verified', true)->count('*');

        $data = [ 'denomination_name' => $denominationName, 'churches' => $churchesCount, 'verified' => $verifiedCount,
                'unverified' => ($churchesCount - $verifiedCount)];
        // $data = ['data' => $verifiedCount];
        return $data;
    }
    public function getDenoNames(){
        $deno = Denomination::all();
        return $deno;
    }
}
