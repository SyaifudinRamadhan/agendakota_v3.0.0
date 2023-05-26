<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;

class TestController extends Controller
{
    public function testOnly(Request $request)
    {
        return view('user.test-video');
    }
    public function testStudio(Request $req)
    {
        return view('user.organization.event.studio');
    }
}