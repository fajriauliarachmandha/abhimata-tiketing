<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request){
        $tickets = Ticket::where('created_by', $request->user()->id)->get();
        return Inertia::render('User/Home', [
            'tickets' => $tickets
        ]);
    }
}
