<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $appointment = Appointment::where("user_id", Auth::user()->id)->get();
       $doctor = User::where("type", "doctor")->get();
       foreach($appointment as $data) {
        foreach($doctor as $info) {
            $details = $info->doctor;
            if($data['doc_id'] == $info['id']) {
                $data['doctor_name'] = $info['name'];
                $data['doctor_profile'] = $info['profile_photo_url'];
                $data['category'] = $details['category'];
            }
        }
       }
       return $appointment;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $appointment = new Appointment();
        $appointment->user_id = Auth::user()->id;
        $appointment->doc_id = $request->get('doctor_id');
        $appointment->date = $request->get("date");
        $appointment->day = $request->get("day");
        $appointment->time = $request->get("time");
        $appointment->status= 'upcoming';
        $appointment->save();
        return response()->json([
            'success' => 'New Appointment has been made successfully!',
            
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
