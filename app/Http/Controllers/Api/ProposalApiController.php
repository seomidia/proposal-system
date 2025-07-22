<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Proposal;

class ProposalApiController extends Controller
{
    public function show(Proposal $proposal)
    {
        return response()->json($proposal);
    }
}
