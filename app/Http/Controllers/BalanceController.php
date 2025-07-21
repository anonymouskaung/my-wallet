<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Balance;

class BalanceController extends Controller
{
    public function added(Request $request) {
        $addedBalance = Balance::where('money_flow', 'added')->sum('amount') ?? 0;
        $usedBalance = Balance::where('money_flow', 'used')->sum('amount') ?? 0;
        $currentBalance = $addedBalance - $usedBalance;
        $balance = new Balance;
        if($balance) {
            $balance->amount = $request->addedAmount;
            $balance->money_flow = 'added';
            $balance->content = $request->topupDescription;
            $balance->save();
            $amount = $currentBalance + $request->addedAmount;
            $created_at = date('j M Y');
        } else {
            return response()->json(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'amount' => $amount,
                'added_amount' => $request->addedAmount,
                'money_flow' => 'added',
                'content' => $request->topupDescription,
                'created_at' => $created_at,
            ]
        ]);
    }
    public function used(Request $request) {
        $addedBalance = Balance::where('money_flow', 'added')->sum('amount') ?? 0;
        $usedBalance = Balance::where('money_flow', 'used')->sum('amount') ?? 0;
        $currentBalance = $addedBalance - $usedBalance;
        $balance = new Balance;
        if($balance) {
            if($currentBalance >= $request->usedAmount) {
                $balance->amount = $request->usedAmount;
                $balance->money_flow = 'used';
                $balance->content = $request->payDescription;
                $balance->save();
                $amount = $currentBalance - $request->usedAmount;
                $created_at = date('j M Y');
            } else {
                return response()->json(['error' => true]);
            }
        } else {
            return response()->json(['success' => false]);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'amount' => $amount,
                'used_amount' => $request->usedAmount,
                'money_flow' => 'used',
                'content' => $request->payDescription,
                'created_at' => $created_at,
            ]
        ]);
    }
}
