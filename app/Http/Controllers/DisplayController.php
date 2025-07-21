<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balance;

class DisplayController extends Controller
{
    public function desktop() {
        $addedBalance = Balance::where('money_flow', 'added')->sum('amount') ?? 0;
        $usedBalance = Balance::where('money_flow', 'used')->sum('amount') ?? 0;
        $balance = $addedBalance - $usedBalance;
        $inboxes = Balance::select('id', 'amount', 'money_flow', 'content', 'photo', 'created_at')->orderBy('id', 'desc')->get();
        
        return view('display_screens/desktop', compact('balance', 'inboxes'));
    }
    public function mobile() {
        $addedBalance = Balance::where('money_flow', 'added')->sum('amount') ?? 0;
        $usedBalance = Balance::where('money_flow', 'used')->sum('amount') ?? 0;
        $balance = $addedBalance - $usedBalance;
        $inboxes = Balance::select('id', 'amount', 'money_flow', 'content', 'photo', 'created_at')->orderBy('id', 'desc')->get();
        
        return view('display_screens/mobile', compact('balance', 'inboxes'));
    }
}
