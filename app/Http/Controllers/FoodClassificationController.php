<?php

namespace App\Http\Controllers;

use App\FoodClassification;
use Illuminate\Http\Request;

class FoodClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return FoodClassification::all();
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
        return FoodClassification::create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\FoodClassification  $foodClassification
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(FoodClassification $foodClassification, $id)
    {
        return $foodClassification->where('id', $id)->firstOrFail();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\FoodClassification  $foodClassification
     * @return \Illuminate\Http\Response
     */
    public function edit(FoodClassification $foodClassification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\FoodClassification  $foodClassification
     * $param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, FoodClassification $foodClassification, $id)
    {
        $foodClassification->where('id', $id)->update($request->all());
        return $foodClassification->where('id', $id)->firstOrFail();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\FoodClassification  $foodClassification
     * @return \Illuminate\Http\Response
     */
    public function destroy(FoodClassification $foodClassification, $id)
    {
        $foodClassification->where('id', $id)->delete();
        return response()->json(['status' => 'success', 'message' => 'Classification deleted successfully']);
    }
}
