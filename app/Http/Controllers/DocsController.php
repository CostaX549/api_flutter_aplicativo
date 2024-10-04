<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DocsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctor = Auth::user();
        $appointments = Appointment::where("doc_id", $doctor->id)->where("status", "futuro")->get();
        $reviews = Review::where("doc_id", $doctor->id)->where("status", "active")->get();
        return view('dashboard')->with(['doctor' => $doctor, 'appointments' => $appointments, 'reviews' => $reviews]);
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
        $review = new Review();
    $appointment = Appointment::where("id", $request->get("appointment_id"))->first();
    $review->user_id = Auth::user()->id;
    $review->doc_id = $request->get("doctor_id");
    $review->ratings = $request->get("ratings");
    $review->reviews = $request->get("reviews");
    $review->reviewed_by = Auth::user()->name;
    $review->status ='active';
    $review->save();
    $appointment->status = 'completo';
    $appointment->save();

    return response()->json([
      'success' => 'The appointment has been completed and reviewed successfully'
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
