<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\TeamCategory;

class PublicTeamController extends Controller
{
    public function index()
    {
        $categories = TeamCategory::with(['members' => function ($query) {
            $query->where('status', 'active')->orderBy('sort_order');
        }])->orderBy('sort_order')->get();

        return view('public.team', compact('categories'));
    }
}
