<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ChargingStation;
use App\Models\Transaction;
use App\Models\Connector;
use Illuminate\Support\Facades\Validator;

class ChargingStationController extends Controller
{
    public function index()
    {
        $stations = ChargingStation::with(['transactions' => function($query) {
            $query->latest()->take(5);
        }, 'connectors'])->paginate(10);

        return view('charging-stations.index', compact('stations'));
    }

    public function show(ChargingStation $station)
    {
        $station->load([
            'transactions' => function($query) {
                $query->latest()->take(20);
            },
            'connectors'
        ]);

        $stats = [
            'total_transactions' => $station->transactions()->count(),
            'active_transactions' => $station->transactions()->whereNull('stop_time')->count(),
            'total_energy' => $station->transactions()
                    ->whereNotNull('stop_meter_value')
                    ->selectRaw('SUM(stop_meter_value - start_meter_value) as total')
                    ->value('total') ?? 0,
            'avg_session_duration' => $station->transactions()
                    ->whereNotNull('stop_time')
                    ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, start_time, stop_time)) as avg_duration')
                    ->value('avg_duration') ?? 0
        ];

        return view('charging-stations.show', compact('station', 'stats'));
    }

    public function create()
    {
        return view('charging-stations.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string|max:255|unique:charging_stations',
            'vendor' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'firmware_version' => 'nullable|string|max:255',
            'connector_count' => 'required|integer|min:1|max:10',
            'location' => 'nullable|string|max:500',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $station = ChargingStation::create([
            'identifier' => $request->identifier,
            'vendor' => $request->vendor,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'firmware_version' => $request->firmware_version ?? 'Unknown',
            'status' => 'Unavailable',
            'connector_count' => $request->connector_count,
            'location' => $request->location,
            'lat' => $request->lat,
            'long' => $request->long,
            'description' => $request->description
        ]);

        // Create connectors for the station
        for ($i = 1; $i <= $request->connector_count; $i++) {
            Connector::create([
                'charging_station_id' => $station->id,
                'connector_id' => $i,
                'status' => 'Unavailable'
            ]);
        }

        return redirect()->route('charging-stations.show', $station)
            ->with('success', 'Charging station created successfully!');
    }

    public function edit(ChargingStation $station)
    {
        return view('charging-stations.edit', compact('station'));
    }

    public function update(Request $request, ChargingStation $station)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string|max:255|unique:charging_stations,identifier,' . $station->id,
            'vendor' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'serial_number' => 'required|string|max:255',
            'firmware_version' => 'nullable|string|max:255',
            'connector_count' => 'required|integer|min:1|max:10',
            'location' => 'nullable|string|max:500',
            'lat' => 'nullable|string',
            'long' => 'nullable|string',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $oldConnectorCount = $station->connector_count;
        $newConnectorCount = $request->connector_count;

        $station->update([
            'identifier' => $request->identifier,
            'vendor' => $request->vendor,
            'model' => $request->model,
            'serial_number' => $request->serial_number,
            'firmware_version' => $request->firmware_version ?? 'Unknown',
            'connector_count' => $request->connector_count,
            'location' => $request->location,
            'lat' => $request->lat,
            'long' => $request->long,
            'description' => $request->description
        ]);

        // Handle connector count changes
        if ($newConnectorCount > $oldConnectorCount) {
            // Add new connectors
            for ($i = $oldConnectorCount + 1; $i <= $newConnectorCount; $i++) {
                Connector::create([
                    'charging_station_id' => $station->id,
                    'connector_id' => $i,
                    'status' => 'Unavailable'
                ]);
            }
        } elseif ($newConnectorCount < $oldConnectorCount) {
            // Remove excess connectors (only if no active transactions)
            $connectorsToRemove = $station->connectors()
                ->where('connector_id', '>', $newConnectorCount)
                ->get();

            foreach ($connectorsToRemove as $connector) {
                $hasActiveTransactions = Transaction::where('charging_station_id', $station->id)
                    ->where('connector_id', $connector->connector_id)
                    ->whereNull('stop_time')
                    ->exists();

                if (!$hasActiveTransactions) {
                    $connector->delete();
                }
            }
        }

        return redirect()->route('charging-stations.show', $station)
            ->with('success', 'Charging station updated successfully!');
    }

    public function destroy(ChargingStation $station)
    {
        // Check for active transactions
        $activeTransactions = $station->transactions()->whereNull('stop_time')->count();

        if ($activeTransactions > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete station with active transactions. Please stop all transactions first.');
        }

        $stationName = $station->identifier;
        $station->delete();

        return redirect()->route('charging-stations.index')
            ->with('success', "Charging station '{$stationName}' deleted successfully!");
    }

    public function dashboard()
    {
        $totalStations = ChargingStation::count();
        $activeStations = ChargingStation::where('last_heartbeat', '>', now()->subMinutes(10))->count();
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

    public function toggleStatus(ChargingStation $station)
    {
        $newStatus = $station->status === 'Available' ? 'Unavailable' : 'Available';
        $station->update(['status' => $newStatus]);

        return redirect()->back()
            ->with('success', "Station status changed to {$newStatus}");
    }
}
