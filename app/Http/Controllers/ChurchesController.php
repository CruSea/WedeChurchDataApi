<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Church;
use App\Denomination;
use App\User;
use App\Verify_church;

use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
// use App\Http\Controllers\CheckUserService;

class ChurchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // protected $checkuserService;
    //  public function __construct(CheckUserService $checkuserService)
    //   {
    //      $this->checkuserService = $checkuserService;
    //   }
    public function index()
    {
        $churches = Church::paginate(5);
        return $churches;
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
            'church_name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'denomination' => 'required',
        ]);
   
     try{
        $church = new Church();
        $church->church_name = $request->input('church_name');
        $church->description = $request->input('description');
        $church->location = $request->input('location');
        $church->latitude = $request->input('latitude');
        $church->longitude = $request->input('longitude');
        $church->phone_number = $request->input('phone_number');
        $church->email = $request->input('email');
        $church->denomination = $request->input('denomination');
        // $denomination = Denomination::where('name', '=', $request->input('denomination'))->first();
        // $church->denomination_id = $denomination->id;
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['permission to add church denied'], 404);
            }
        $user_id = $user->id;
        $church->user_id = $user_id;
        $church->save();
     }
    catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['error' => 'Duplicate Entry']);
            }
    }
        return response()->json("church created successfully");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $church = Church::find($id);
        if(! $church){
            return response()->json(['church does not exist'], 404);
        }
        return response()->json($church);
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
    public function update(Request $request, $id)
    {
        if (! $user = JWTAuth::parseToken()->authenticate()) {
            return response()->json(['permission to update church denied'], 404);
        }
        $church = Church::find($id);
        if(! $church){
            return response()->json(['church does not exist'], 404);
        }
        $user_id = $user->id;
        if($church->user_id == $user_id || $user->hasRole('admin')){
            $data = $request->all();
            $church->fill($data);
            if($request->input('denomination')){
                    $church->denomination = $request->input('denomination');
            }
        // if($request->input('denomination')){
        //     $denomination = Denomination::where('name', '=', $request->input('denomination'))->first();
        //     $church->denomination_id = $denomination->id;
        // }
        $church->save();
        
        return response()->json("church successfully updated");
        }
        else{
            return response()->json(['permission to update church denied'], 404);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $church = Church::find($id);
        if(! $church){
            return response()->json(['church does not exist'], 404);
        }
        $church ->delete();
        return response()->json("church successfully deleted");
    }
    public function verify($id){
        $church = Church::find($id);
        // $church = Church::where('name', '=', $request->input('church'))->first();
        // $verify->church_id= $church->id;
        $church->verified = true;

        $church->save();
        return response()->json("church verified");
    }
    
}
