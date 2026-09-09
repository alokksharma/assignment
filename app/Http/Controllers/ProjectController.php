<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::query()->paginate(10);
        return view('projects.index', compact('projects'));
    }

    public function create(){
         return view('projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'url'            => 'required|string|max:255',
            'name'           => 'required|string|max:255',
            'client_name'    => 'required|string|max:255',
            'billing_address' => 'required|string|max:1000',
            'description'     => 'nullable|string|max:1000',
            'code'           => 'required|string|max:255',
            'supplier_token' => 'required|string|max:255',
            'supplier_id'    => 'required|string|max:255',
            'status'         => 'required|in:0,1',
        ]);

        Project::create($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Project created successfully.');
    }

    public function edit(Project $supplier)
    {
        return view('projects.edit', compact('supplier'));
    }

    public function update(Request $request, Project $supplier)
    {
        $validated = $request->validate([
            'url'            => 'required|string|max:255',
            'name'           => 'required|string|max:255',
            'client_name'    => 'required|string|max:255',
            'billing_address' => 'required|string|max:1000',
            'code'           => 'required|string|max:255',
            'supplier_token' => 'required|string|max:255',
            'supplier_id'    => 'required|string',
            'status'         => 'required|in:0,1',
            'description'    => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Project deleted successfully.');
    }

}
