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

class ChurchesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Church::all());
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

        $church = new Church();
        $church->church_name = $request->input('church_name');
        $church->description = $request->input('description');
        $church->location = $request->input('location');
        $church->latitude = $request->input('latitude');
        $church->longitude = $request->input('longitude');
        $church->phone_number = $request->input('phone_number');
        $church->email = $request->input('email');
        $denomination = Denomination::where('name', '=', $request->input('denomination'))->first();
        $church->denomination_id = $denomination->id;

        try {
            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['permission to add church denied'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        
        $user_id = $user->id;
        $church->user_id = $user_id;
        $church->save();

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
        if($church->user_id != $user_id){
            return response()->json(['permission to update church denied'], 404);
        }
        $data = $request->all();
        $church->fill($data);
        if($request->input('denomination')){
            $denomination = Denomination::where('name', '=', $request->input('denomination'))->first();
            $church->denomination_id = $denomination->id;
        }
        $church->save();
        
        return response()->json("church successfully updated");
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
    public function verify(Request $request, $id){
        $verify = new Verify_church();
        // $church = Church::where('name', '=', $request->input('church'))->first();
        // $verify->church_id= $church->id;
        $verify->church_id= $id;
        $verify->verified = 1;

        // if($request->input('value')=='true'){
        //     $verify->verified = 1;
        // }
        // else{
        //     $verify->verified = 0;
        // }
        $verify->save();
        return response()->json("church verified");
    }
    
}
