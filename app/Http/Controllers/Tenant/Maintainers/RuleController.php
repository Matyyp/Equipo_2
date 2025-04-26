<?php

namespace App\Http\Controllers\Tenant\Maintainers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rule;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Rule = Rule::all();
        return view('tenant.admin.maintainer.rule.index', compact('Rule'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tenant.admin.maintainer.rule.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',

        ]);

        Rule::create([
            'name' => $request->name,
            'description' => $request->description

        ]);

        return redirect()->route('reglas.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rule = Rule::where('id_rule', $id)->first();
        return view('tenant.admin.maintainer.rule.edit', compact('rule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',

        ]);

        Rule::where('id_rule', $id)
        ->update([
            'name' => $request->name,
            'description' => $request->description
        ]);

        return redirect()->route('reglas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Rule::where('id_rule', $id)->delete();
        return redirect()->route('reglas.index');
    }
}
