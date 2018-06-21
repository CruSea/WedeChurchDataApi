<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use App\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        try{
            $role = new Role();
            $role->name = $request->input('name');
            $role->save();
        }
        catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['error' => 'Duplicate Entry']);
            }
        }

        return response()->json("role successfully created");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $data = $request->all();
    //     $role = Role::find($id);
    //     if(! $role){
    //         return response()->json(['role does not exist'], 404);
    //     }
    //     $role->fill($data);
    //     $role->save();
        
    //     return response()->json("role updated");
    // }
public function update() {
        try{
            $credential = request()->only('name', 'id');
            $roles = Role::where('id', '=', $credential['id'])->first();

                $roles->name = isset($credential['name'])? $credential['name']: $roles->name;
                if($roles->update()){
                    return response()->json(['status'=> true, 'message'=> 'role successfully updated', 'role'=>$roles],200);
                } else {
                    return response()->json(['status'=> false, 'message'=> 'unable to update role information', 'error'=>'something went wrong! please try again'],200);
                }
            
        }catch (\Exception $exception){
            return response()->json(['status'=>false, 'message'=> 'Whoops! something went wrong', 'error'=>$exception->getMessage()],500);
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
        $role = Role::find($id);
        if(! $role){
            return response()->json(['role does not exist'], 404);
        }
        $role ->delete();
        return response()->json("role successfully deleted");
    }
    public function getRoles(){
        $roles = Role::all();
        return $roles;
    }
    public function paginatedRoles(){
        $roles = Role::paginate(5);
        return $roles;
    }
}
