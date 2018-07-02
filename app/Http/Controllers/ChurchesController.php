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

use Validator;

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
        $credential = request()->only('church_name','description', 'location',
                                    'latitude', 'longitude', 'phone_number' ,'email', 'denomination_id');
        $rules = [
            'church_name' => 'required|string|max:30',
            'description' => 'required|string|max:255',
            'location' => 'required|string|max:30',
            'latitude' => ['required','regex:/^[-]?(([0-8]?[0-9])\.(\d+))|(90(\.0+)?)$/'], 
            'longitude' => ['required','regex:/^[-]?((((1[0-7][0-9])|([0-9]?[0-9]))\.(\d+))|180(\.0+)?)$/'],
            'phone_number' => 'required|string|unique:churches,phone_number|regex:/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/',
            'email' => 'required|email|unique:churches,email',
            'denomination_id' => 'required|exists:denominations,id',
        ];
        $validator = Validator::make($credential, $rules);

        if($validator->fails()) {
                $error = $validator->messages();
                return response()->json(['status'=> false ,'message'=> $error],500);
        }

     try{
        $church = new Church();
        $church->church_name =  $credential['church_name'];
        $church->description = $credential['description'];
        $church->location = $credential['location'];
        $church->latitude = $credential['latitude'];
        $church->longitude = $credential['longitude'];
        $church->phone_number = $credential['phone_number'];
        $church->email = $credential['email'];
        $church->denomination_id = $credential['denomination_id'];

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['permission to add church denied'], 404);
            }
        $user_id = $user->id;
        $church->user_id = $user_id;
         if($church->save()){
             return response()->json(['status'=> true, 'message'=> 'church successfully added', 'church'=>$church],200);
         }
     }
    catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['status'=> false, 'message'=>'Duplicate Entry']);
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
