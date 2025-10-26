<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationProblem;
use Illuminate\Http\Request;

class ApplicationProblemController extends Controller
{
    public function index()
    {
        return view('dashboard.admin.problem-application.index');
    }

    public function store()
    {
        return view('dashboard.admin.problem-application.store');
    }
    public function show($id)
    {
        $problem = ApplicationProblem::with('application')->findOrFail($id);
        return view('dashboard.admin.problem-application.show', compact('problem'));
    }
    public function edit($id)
    {
        $problem = ApplicationProblem::findOrFail($id);
        $applications = Application::all();

        return view('dashboard.admin.problem-application.edit', compact('problem', 'applications'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'application_id' => 'required|uuid',
            'problem_name' => 'required|string|max:255',
            'description' => 'required|string',

        ]);

        $problem = ApplicationProblem::findOrFail($id);
        $problem->update([
            'application_id' => $request->application_id,
            'problem_name' => $request->problem_name,
            'description' => $request->description,
        ]);

        return redirect()->route('application-problems')->with('success', 'Problem berhasil diperbarui');
    }
    public function destroy($id)
    {
        $problem = ApplicationProblem::findOrFail($id);
        $problem->delete();

        return redirect()->route('application-problem.index')->with('success', 'Problem berhasil dihapus');
    }
}
