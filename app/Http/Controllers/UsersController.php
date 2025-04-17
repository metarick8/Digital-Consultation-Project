<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\FavoriteResrouce;
use App\Http\Resources\UserResource;
use App\Models\Expert;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    use HttpResponses;

    public function login(LoginRequest $request)
    {
        // validation
        $request->validated($request->all());
        // check if user's credentials are correct
        if(!auth()->guard('web')->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return  $this->error('','Credentials do not match', 401);
        }
        // credentials matches -> show the info of the user
        $user = User::where('email', $request->email)->first();
        return $this->success([
            'user' => new UserResource($user),
            'token' => $user -> createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function register(RegisterUserRequest $request)
    {
        // validation
        $request->validated($request->all());
        // creating the users' informations on there table
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'budget' => $request->budget
        ]);
        return $this->success([
            'user' => new UserResource($user),
            'token' => $user->createToken('API Token of' . $user->name)->plainTextToken
        ]);

    }
    public function logout()
    {
        // deleting user's token from token's table
       Auth::user()->currentAccessToken()->delete();
        return $this->success([
            'message' => 'You have successfully been logged out and your token has been deleted.'
        ]);
    }

    public function storeFavorite(Request $request)
    {
        $expert_id = $request->id;
        $user = User::find(Auth::user()->id);
        // adding the expert to user's favorite list
        $user->experts()->syncWithoutDetaching([$expert_id]);
        return $this->success([
            'message' => "Add completed successfully!"
        ]);
    }

    public function deleteFavorite(Request $request)
    {
        $expert_id = $request->id;
        $user = User::find(Auth::user()->id);
        // deleting the expert from user's favoite table
        $user->experts()->detach($expert_id);
        return $this->success([
                'message' => "Delete completed successfully!"
        ]);
    }

    public function getAllFavorites()
    {
        $user_id = Auth::id();
        // attaching user's favorite list to the user so we show it
        $favorites = User::with("experts")->where('id', $user_id )->get("id");
        return new FavoriteResrouce($favorites[0]);
    }
}

