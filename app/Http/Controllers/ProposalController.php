<?php

namespace App\Http\Controllers;

use App\Models\Proposal;

class ProposalController extends Controller
{
    public function show(Proposal $proposal)
    {
        return view('proposal', [
            'proposal' => $proposal,
            'subtitle' => 'Proposta de ' . $proposal->name
        ]);
    }
}
