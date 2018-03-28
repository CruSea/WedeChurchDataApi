<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Denomination;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class DenominationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Denomination::all());
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
        //validation
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);
        $denomination = new Denomination();
        $denomination->name = $request->input('name');
        $denomination->description = $request->input('description');
        $denomination->save();

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
}
