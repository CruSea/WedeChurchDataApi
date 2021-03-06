<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Denomination;

use Validator;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Exceptions\CreateModelException;

class DenominationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $denominations = Denomination::sortByDesc('name')->paginate(5);
        $denominations = Denomination::paginate(5);
        // $denominations = Denomination::all();

         return $denominations;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credential = request()->only('name', 'description');
        $rules = [
            'name' => 'required|string|max:30',
            'description' => 'required|string|max:255'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['error'=> $error],500);
        }
        try{
            $denomination = new Denomination();
            $denomination->name =  $credential['name'];
            $denomination->description =  $credential['description'];
            if($denomination->save()){
                return response()->json(['status'=> true, 'message'=> 'denomination successfully added', 'denomination'=>$denomination],200);
            }

        }
        catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['status'=> false, 'message'=>'Duplicate Entry']);
            }
        }
        return response()->json("Denomination created successfully");
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->json(Denomination::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $data = $request->all();
        $denomination = Denomination::find($id);
        if(! $denomination){
            return response()->json(['denomination does not exist'], 404);
        }
        $denomination->fill($data);
        $denomination->save();
        
        return response()->json("denomination updated");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $denomination = Denomination::find($id);
        if(! $denomination){
            return response()->json(['denomination does not exist'], 404);
        }
        $denomination ->delete();
        return response()->json("denomination successfully deleted");
    }
    public function alldeno(){
        $denomination = Denomination::all();
        return response()->json(['denominations' => $denomination]);
    }
}
