<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoutingController extends Controller
{
    public function index(Request $request)
    {
        return view('index');
    }

    /**
     * Display a view based on first route param
     *
     * @return \Illuminate\Http\Response
     */
    public function root(Request $request, $first)
    {
        $view = 'pages.' . $first;

        if (! view()->exists($view)) {
            $view = $first;
        }

        return view($view);
    }

    /**
     * second level route
     */
    public function secondLevel(Request $request, $first, $second)
    {
        $view = 'pages.' . $first . '.' . $second;

        if (! view()->exists($view)) {
            $view = $first . '.' . $second;
        }

        return view($view);
    }

    /**
     * third level route
     */
    public function thirdLevel(Request $request, $first, $second, $third)
    {
        $view = 'pages.' . $first . '.' . $second . '.' . $third;

        if (! view()->exists($view)) {
            $view = $first . '.' . $second . '.' . $third;
        }

        return view($view);
    }
}
