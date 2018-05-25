<?php
	use App\User;

class CheckUserService {
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
}
?>