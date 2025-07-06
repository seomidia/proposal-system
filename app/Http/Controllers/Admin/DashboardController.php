<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'proposals_count' => Proposal::count(),
            'users_count' => User::count(),
            'latest_proposals' => Proposal::latest()->take(5)->get(),
        ];

        return view('admin.dashboard', $stats);
    }
}
