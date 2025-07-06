<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function show(Proposal $proposal)
    {
        return view('proposal', compact('proposal'));
    }

    public function index(Request $request)
    {
        $query = Proposal::query();
        if ($request->filled('q')) {
            $query->where('client_name', 'like', "%{$request->q}%");
        }
        return view('admin.proposals.index', ['proposals' => $query->paginate(10)]);
    }

    public function edit(Proposal $proposal)
    {
        return view('admin.proposals.edit', compact('proposal'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $proposal->update($request->all());
        return redirect()->route('proposals.index');
    }

    public function destroy(Proposal $proposal)
    {
        $proposal->delete();
        return back();
    }
}
