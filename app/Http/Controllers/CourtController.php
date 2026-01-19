<?php

namespace App\Http\Controllers;

use App\Models\Court;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courts = Court::with('category', 'user')->get();
        return response()->json($courts);
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
        $request->validate([
            'code' => 'required|unique:courts',
            'name' => 'nullable|string',
            'cat' => 'required|exists:court__cats,code',
            'active' => 'boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $court = Court::create($request->all());
        return response()->json($court, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Court $court)
    {
        return response()->json($court->load('category', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Court $court)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Court $court)
    {
        $request->validate([
            'code' => 'required|unique:courts,code,' . $court->id,
            'name' => 'nullable|string',
            'cat' => 'required|exists:court__cats,code',
            'active' => 'boolean',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $court->update($request->all());
        return response()->json($court);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Court $court)
    {
        $court->delete();
        return response()->json(['message' => 'Court deleted']);
    }
}
