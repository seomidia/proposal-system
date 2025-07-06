<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'count' => Proposal::count(),
            'latest' => Proposal::latest()->take(5)->get(),
        ]);
    }
}
