<?php

namespace App\Http\Controllers;

use App\Http\Requests\TimeRequest;
use App\Http\Resources\TimeResource;
use App\Models\Expert;
use App\Models\Time;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    use HttpResponses;

    public function storeReservations(TimeRequest $request)
    {
        // valdation
        $request->validated($request->all());
        $expert_id = Auth::id();
        // creating the reservation
        Time::create([
            'expert_id' => $expert_id,
            'time' => $request->date,
            'is_booked' => false // so we know if this reservation is booked by a user so others can't book at the same date
        ]);
        return $this->success('',"Appointment Add successfully!");
    }

    public function getReservations(Request $request)
    {
        $expert_id = $request->id;
        $times = Time::where('expert_id',$expert_id)->get();
        // sending each time to time resource to modify the response then storing each time in a array
        foreach($times as $time)
            $all_times[] = new TimeResource($time);
        return $all_times;
    }

    public function isBooked(Request $request)
    {
        // validation
        $request->validate([
            'expert_id' => ['numeric', 'gte:1'],
            'date' => ['date']
        ]);
        // check if the time exists, since null == 0 in Laravel, if the time didn't exist therefor is booked = null (which means 0)
        // so it will book a reservation (it won't add the booking in the table but it will still modify the budgets of the user and expert)
        $is_exist = Time::where([
            ['expert_id', $request->expert_id],
            ['time', $request->date]
        ])->exists();
        // check if the time is booked
        $is_booked = Time::where([
            ['expert_id', $request->expert_id],
            ['time', $request->date]
        ])->value("is_booked");

        //  $is_booked++;/
        $user_id = Auth::id();
        $budget_of_user = User::where('id', $user_id)->value("budget");
        $budget_of_expert = Expert::where('id', $request->expert_id)->value("budget");
        $price = Expert::where('id', $request->expert_id)->value("price");

        if(!$is_booked && $is_exist)
            // if the time ain't booked and the user have in there budget the price of the reservation
            if ($budget_of_user >= $price) {
                Time::where([
                    ['expert_id', $request->expert_id],
                    ['time', $request->date]
                ])->update(array('is_booked' =>true)); // change that reservation to booken so it can't be booked again by others
                // updating the user's budget and the expert's budget depending on the price that the expert chose (when they registered)
                $new_budget_user = $budget_of_user - $price;
                User::where('id', $user_id)->update(array('budget' => $new_budget_user));
                $new_budget_expert = $budget_of_expert + $price;
                Expert::where('id', $request->expert_id)->update(array('budget' => $new_budget_expert));
                return $this->success('', 'The appointment booked successfully!');
            }

            else // the time ain't booked but the user doesn't have enough cash in their budget to book that time
                return $this->error('', 'The Budget does not contain enough cash!', 401);
        // the time is already booked by other user
        return $this->success('',"There are no appointments today!");
    }
}
