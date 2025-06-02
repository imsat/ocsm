<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChargingStation;
use App\Models\Transaction;

class ChargingStationController extends Controller
{
    public function index()
    {
        $stations = ChargingStation::with('transactions')->get();
        return view('charging-stations.index', compact('stations'));
    }

    public function show(ChargingStation $station)
    {
        $station->load(['transactions' => function($query) {
            $query->latest()->take(10);
        }]);

        return view('charging-stations.show', compact('station'));
    }

    public function dashboard()
    {
        $totalStations = ChargingStation::count();
        $activeStations = ChargingStation::where('status', 'Available')->count();
        $totalTransactions = Transaction::count();
        $activeTransactions = Transaction::whereNull('stop_time')->count();

        $recentTransactions = Transaction::with('chargingStation')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalStations',
            'activeStations',
            'totalTransactions',
            'activeTransactions',
            'recentTransactions'
        ));
    }

    public function sendCommand(Request $request, ChargingStation $station)
    {
        $request->validate([
            'command' => 'required|string',
            'parameters' => 'nullable|array'
        ]);

        // Here you would implement sending commands to the charging station
        // This would require storing the WebSocket connection reference

        return response()->json([
            'success' => true,
            'message' => "Command '{$request->command}' sent to station {$station->identifier}"
        ]);
    }
}
