<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function index(Request $request)
    {
        $query = Proposal::query();

        if ($search = $request->get('search')) {
            $query->where('client_name', 'like', "%{$search}%");
        }

        $proposals = $query->latest()->paginate(20);
        return view('admin.proposals.index', compact('proposals'));
    }

    public function edit(Proposal $proposal)
    {
        return view('admin.proposals.edit', compact('proposal'));
    }

    public function update(Request $request, Proposal $proposal)
    {
        $data = $request->validate([
            'client_name' => 'nullable|string',
            'client_email' => 'nullable|string',
            'client_phone' => 'nullable|string',
            'amount' => 'nullable|numeric',
            'due_date' => 'nullable|date',
        ]);

        $proposal->update($data);
        return redirect()->route('admin.proposals.index');
    }
}
