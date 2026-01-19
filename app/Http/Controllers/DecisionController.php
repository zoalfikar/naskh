<?php

namespace App\Http\Controllers;

use App\Models\Decision;
use App\Models\Dec_tab;
use Illuminate\Http\Request;

class DecisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
            'cf_code' => 'required|exists:c_files,code',
            'number' => 'required|integer',
            'date' => 'required|date',
            'user_id' => 'required|exists:users,id',
            'type' => 'required|exists:decision_types,code',
            'selectedTabs' => 'required|array',
            'selectedTabs.*.code' => 'exists:tabs,code',
            'selectedTabs.*.value' => 'string',
        ]);

        $decision = Decision::create($request->only([
            'cf_code', 'number', 'date', 'note', 'user_id', 'croup', 'hurry_date', 'kind', 'hurry', 'type', 'hurry_text', 'opposit_judge'
        ]));

        foreach ($request->selectedTabs as $tabData) {
            Dec_tab::create([
                'tab_code' => $tabData['code'],
                'tab_desc' => $tabData['description'] ?? '',
                'tab_order' => $tabData['order'] ?? 0,
                'tab_value' => $tabData['value'],
                'cf_code' => $request->cf_code,
                'descision_n' => $request->number,
                'descision_d' => $request->date,
                'user_id' => $request->user_id,
            ]);
        }

        return response()->json($decision->load('decisionType'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Decision $decision)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Decision $decision)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Decision $decision)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Decision $decision)
    {
        //
    }
}
