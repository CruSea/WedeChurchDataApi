<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Hash;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Log;

class JwtAuthenticateController extends Controller
{
    
    public function index()
    {
        $user = Auth::user();
        $email = $user->email;
        $users = User::whereNotIn('email',[$email])->paginate(5);
        // $users = User::paginate(5);
        return $users;
        // return response()->json(['auth'=>Auth::user(), 'users'=>User::all()]);
    }
   
    public function isAuthenticated(Request $request) {
        $token = JWTAuth::getToken();
        if(! $token){
            return response()->json('token not provided');
        }
        // if (! $token = JWTAuth::parseToken()) {
        //     return response()->json('no');
        // }
        $user = JWTAuth::toUser($token);
        if($user){
            return response()->json('u cant access');
        }
        return response()->json($user);
    }
    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            // verify the credentials and create a token for the user
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        } catch (JWTException $e) {
            // something went wrong
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        // if no errors are encountered we can return a JWT
        return response()->json(compact('token'));
    }
    
    public function createUser(Request $request){
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'region' => 'required',
            'city' => 'required',
            'sex' => 'required',
            'phone_number' => 'required',
            'email' => 'required',
            'user_name' => 'required',
            'password' => 'required',
        ]);
            $user = new User();
            $user->first_name = $request->input('first_name');
            $user->last_name = $request->input('last_name');
            $user->region = $request->input('region');
            $user->city = $request->input('city');
            $user->sex = $request->input('sex');
            $user->phone_number = $request->input('phone_number');
            $user->user_name = $request->input('user_name');
            $user->email = $request->input('email');
            $user->password = Hash::make($request->input('password'));
            $user->save();
        

        return response()->json("Account created successfully");

    }
    // public function updateUser(Request $request,$id)
    // {
    //     $data = $request->all();
    //     $user = User::find($id);
    //     if(! $user){
    //         return response()->json(['user does not exist'], 404);
    //     }
    //     $user->fill($data);
    //     $user->save();
        
    //     return response()->json("user updated");
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyUser($id)
    {
        //
        $user = User::find($id);
        if(! $user){
            return response()->json(['user does not exist'], 404);
        }
        $user ->delete();
        return response()->json("user successfully deleted");
    }
    public function createRole(Request $request){

        $role = new Role();
        $role->name = $request->input('name');
        $role->save();

        return response()->json("role successfully created");

    }

    public function assignRole(Request $request){
        $user = User::where('email', '=', $request->input('email'))->first();
        if(! $user){
            return response()->json("user doesn't exit");
        }
        $role = Role::where('name', '=', $request->input('role'))->first();
        //$user->attachRole($request->input('role'));
        $user->roles()->attach($role->id);

        return response()->json("role assigned to the user");
    }

    public function attachPermission(Request $request){
        $role = Role::where('name', '=', $request->input('role'))->first();
        $permission = Permission::where('name', '=', $request->input('permission'))->first();
        if($role->hasPerm($permission->name)){
            return response()->json('role already attached to permission');
        }
        if(! $role || ! $permission){
            return response()->json("role or permission doesn't exit");
        }
        $role->attachPermission($permission);

        return response()->json('role attached to permission');
        // return $permission;
    }

    public function checkRoles(Request $request){
        $user = User::where('email', '=', $request->input('email'))->first();
        if(! $user){
            return response()->json("user doesn't exit");
        }
        Log::info($user);
        return response()->json([
            "user" => $user,
            "owner" => $user->hasRole('owner'),
            "admin" => $user->hasRole('admin'),
            "createUser" => $user->can('create-users'),
            "editUser" => $user->can('edit-user'),
            "listUsers" => $user->can('list-users')
        ]);
    }
    
    public function updateRoles(Request $request, $id){
        $data = $request->all();
        $role = Role::find($id);
        if(! $role){
            return response()->json(['role does not exist'], 404);
        }
        $role->fill($data);
        $role->save();
        
        return response()->json("role updated");
    }
    public function destroyRoles($id){
        $role = Role::find($id);
        if(! $role){
            return response()->json(['role does not exist'], 404);
        }
        $role ->delete();
        return response()->json("role successfully deleted");
    }
    public function getPermissions(){
        $permission = Permission::all();
        return $permission;
    }
    public function checkPermissions(Request $request){
        $role = Role::where('name', '=', $request->input('role'))->first();
        $permissions = Permission::all();
        foreach ($permissions as $permission) {
            // return $permission;
            return response()->json(['permission' => $permission]);
            // return response()->json(['status'=> true, 'message'=> 'success', 
            //     'permission'=>$permission],200);
        }
        
    }
    // if($role->hasPerm($permission->name)){
    //         return $permission;
    //         }

}

