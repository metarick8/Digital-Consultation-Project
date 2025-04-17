<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\NumberRequest;
use App\Http\Requests\RegisterExpertRequest;
use App\Http\Resources\Expert_TypeResource as ExpertResource;
use App\Http\Resources\Type_ExpertResource as TypeResource;
use App\Models\Expert;
use App\Models\Type;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ExpertsController extends Controller
{
    use HttpResponses;

    public function login(LoginRequest $request)
    {
        // validation
       $request->validated($request->all());
        // check if user's credentials are correct (we used guard in sacntum cause of multi authinctication)
       if(!auth()->guard('expert')->attempt(['email' => $request->email, 'password' => $request->password]))
        {
            return  $this->error('','Credentials do not match', 401);
        }
        // credentials matches -> show the info of the user
        $expert = Expert::where('email', $request->email)->first();
        return $this->success([
            'expert' => new ExpertResource($expert),
            'token' => $expert -> createToken('API Token of ' . $expert->name)->plainTextToken
        ]);
    }

    public function register(RegisterExpertRequest $request)
    {
        // validation
        $request->validated($request->all());
        // creating the users' informations on there table
        $expert= Expert::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'image_path'=>$request->image_path,
            'experience'=>$request->experience,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'budget' => 0,
            'price' => $request->price,
            'rate' => 0
        ]);
        return $this->success([
            'expert' => new ExpertResource($expert),
            'token' => $expert->createToken('API Token of' . $expert->name)->plainTextToken
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

    public function storeTypes(Request $request)
    {
        // storing all given types in an array
        $types[] = $request->input('types');
        $expert = Expert::find(Auth::user()->id);
        // attaching the array of types to the expert
        for ($i = 0; $i < sizeof($types[0]); $i++) {
            $expert->types()->syncWithoutDetaching($types[0][$i]);
        }
        return $this->success([
            'message' => "Storage Catagories completed successfully!"
        ]);
    }

    public function getType(NumberRequest $request)
    {
        // validation
        $request->validated($request->all());
        // getting all experts that have the given type in there informations using type resource
        $type =Type::with('experts')->where('id',$request->number)->get();
        return new TypeResource($type[0]);
    }

    public function getAllExperts()
    {
        $experts = Expert::with('types')->get();
        // showing only the informations we want using exoert resource
        foreach($experts as $expert)
          $all_experts[] = new ExpertResource($expert);
        return $this->success([
            $all_experts,
        ]);
    }

    public function rate(NumberRequest $request)
    {
        // validtaion
        $request->validated($request->all());
        $old_rate = Expert::where('id',$request->expert_id)->value('rate');
        // since we will get 1 rate per time so we add that to the rate that the expert has (the old rate) then devide on 2 so we calculate the average of the expert's rate
        $new_rate = ($old_rate+$request->number)/2;
        // updating the expert's rate
        Expert::where('id',$request->expert_id)->update(array('rate'=> $new_rate));
        return $this->success([
            'message' => "Rating expert completed successfully!"
        ]);
    }
}

