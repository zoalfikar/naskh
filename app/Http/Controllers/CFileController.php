<?php

namespace App\Http\Controllers;

use App\Models\CFile;
use Illuminate\Http\Request;

class CFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cFiles = CFile::with('vCourt', 'user', 'decisions')->get();
        return response()->json($cFiles);
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
            'v_corte' => 'required|exists:v_courts,code',
            'number' => 'required|integer',
            'subject' => 'nullable|string',
            'kind' => 'integer',
            'c_date' => 'required|date',
            'c_begin_n' => 'nullable|integer',
            'c_start_year' => 'nullable|integer',
            'round_year' => 'nullable|integer',
            'degree1_court' => 'nullable|integer',
            'degree1_state' => 'nullable|integer',
            'degree1_room' => 'nullable|integer',
            'degree1_number' => 'nullable|integer',
            'degree1_year' => 'nullable|integer',
            'degree1_dec_n' => 'nullable|integer',
            'degree1_dec_d' => 'nullable|date',
            'degree2_court' => 'nullable|integer',
            'degree2_state' => 'nullable|integer',
            'degree2_room' => 'nullable|integer',
            'degree2_number' => 'nullable|integer',
            'degree2_year' => 'nullable|integer',
            'degree2_dec_n' => 'nullable|integer',
            'degree2_dec_d' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $cFile = CFile::create($request->all());
        return response()->json($cFile, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(CFile $cFile)
    {
        return response()->json($cFile->load('vCourt', 'user', 'decisions', 'jVcourts'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CFile $cFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CFile $cFile)
    {
        $request->validate([
            'v_corte' => 'required|exists:v_courts,code',
            'number' => 'required|integer',
            'subject' => 'nullable|string',
            'kind' => 'integer',
            'c_date' => 'required|date',
            'c_begin_n' => 'nullable|integer',
            'c_start_year' => 'nullable|integer',
            'round_year' => 'nullable|integer',
            'degree1_court' => 'nullable|integer',
            'degree1_state' => 'nullable|integer',
            'degree1_room' => 'nullable|integer',
            'degree1_number' => 'nullable|integer',
            'degree1_year' => 'nullable|integer',
            'degree1_dec_n' => 'nullable|integer',
            'degree1_dec_d' => 'nullable|date',
            'degree2_court' => 'nullable|integer',
            'degree2_state' => 'nullable|integer',
            'degree2_room' => 'nullable|integer',
            'degree2_number' => 'nullable|integer',
            'degree2_year' => 'nullable|integer',
            'degree2_dec_n' => 'nullable|integer',
            'degree2_dec_d' => 'nullable|date',
            'user_id' => 'required|exists:users,id',
        ]);

        $cFile->update($request->all());
        return response()->json($cFile);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CFile $cFile)
    {
        $cFile->delete();
        return response()->json(['message' => 'CFile deleted']);
    }
}
