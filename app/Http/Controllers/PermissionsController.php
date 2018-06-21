<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Permission;

class PermissionsController extends Controller
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
            $viewUsers = new Permission();
        $viewUsers->name = $request->input('name');
        $viewUsers->save();
        }
        catch (\Illuminate\Database\QueryException $e){
            $errorCode = $e->errorInfo[1];
            if($errorCode == 1062){
                return response()->json(['error' => 'Duplicate Entry']);
            }
        }

        return response()->json("permission successfully created");
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
    public function update()
    {
        try{
            $credential = request()->only('name', 'id');
            $permission = Permission::where('id', '=', $credential['id'])->first();

                $permission->name = isset($credential['name'])? $credential['name']: $permission->name;
                if($permission->update()){
                    return response()->json(['status'=> true, 'message'=> 'permission successfully updated', 'permission'=>$permission],200);
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
        $permission = Permission::find($id);
        if(! $permission){
            return response()->json(['permission does not exist'], 404);
        }
        $permission ->delete();
        return response()->json("permission successfully deleted");
    }
    public function paginatedPermissions(){
        $permissions = Permission::paginate(5);
        return $permissions;
    }
}
