<?php

namespace App\Http\Controllers;

use App\Models\SessionYear;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sessions = SessionYear::paginate();
        return Inertia::render('Session/Index', [
            'sessions' => $sessions,
        ]);
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
        $request->validate([
            'year'=>"array|required",
            'year.0'=>"required",
            'year.1'=>"required",
            'session_type'=>'required|in:FALL,SPRING,SUMMER',
        ],[
            'year.0.required'=>"Please Select Start Year",
            'year.1.required'=>"Please Select End Year",

        ]);
        $start=Carbon::parse($request->year[0])->format('Y');
        $end= Carbon::parse($request->year[1])->format('Y');
        if($start==$end){
            return back()->withErrors([
                'year'=>"Start and End Year can't be same"
            ]);
        }
        $session_year="$start-$end";
        SessionYear::create([
            'session_year'=>$session_year,
            'session_type'=>$request->session_type,
        ]);
        session()->flash('success','Session Created Successfully');
        return back();
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
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
