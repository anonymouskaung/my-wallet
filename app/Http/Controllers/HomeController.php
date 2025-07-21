<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balance;
use Jenssegers\Agent\Agent;

class HomeController extends Controller
{
    public function __construct() {
        return $this->middleware('auth');
    }
    public function index()
    {
        $addedBalance = Balance::where('money_flow', 'added')->sum('amount') ?? 0;
        $usedBalance = Balance::where('money_flow', 'used')->sum('amount') ?? 0;
        $balance = $addedBalance - $usedBalance;
        $inboxes = Balance::select('id', 'amount', 'money_flow', 'content', 'photo', 'created_at')->orderBy('id', 'desc')->get();

        $agent = new Agent();
        if($agent->isMobile()) {
            return view('display_screens/mobile', compact('balance', 'inboxes'));
        } else {
            return view('display_screens/desktop', compact('balance', 'inboxes'));
        }
    }
}
