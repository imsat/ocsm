@extends('layouts.app')

@section('title', $station->identifier . ' - Charging Station Details')

@section('content')
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ route('charging-stations.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">{{ $station->identifier }}</h1>
                    <p class="mt-1 text-sm text-gray-500">{{ $station->vendor }} {{ $station->model }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
{{--                <form action="{{ route('charging-stations.toggle-status', $station->id) }}" method="POST" class="inline">--}}
{{--                    @csrf--}}
{{--                    <button type="submit"--}}
{{--                            class="btn-outline"--}}
{{--                            onclick="return confirm('Are you sure you want to change the station status?')">--}}
{{--                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>--}}
{{--                        </svg>--}}
{{--                        Toggle Status--}}
{{--                    </button>--}}
{{--                </form>--}}
{{--                <a href="{{ route('charging-stations.edit', $station->id) }}" class="btn-secondary">--}}
{{--                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>--}}
{{--                    </svg>--}}
{{--                    Edit Station--}}
{{--                </a>--}}
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Station Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Station Info Card -->
        <div class="lg:col-span-2">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Station Information</h2>
                </div>
                <div class="card-body">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-3">Basic Information</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Identifier</dt>
                                    <dd class="text-sm text-gray-600">{{ $station->identifier }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Vendor</dt>
                                    <dd class="text-sm text-gray-600">{{ $station->vendor }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Model</dt>
                                    <dd class="text-sm text-gray-600">{{ $station->model }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Serial Number</dt>
                                    <dd class="text-sm text-gray-600">{{ $station->serial_number }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Firmware Version</dt>
                                    <dd class="text-sm text-gray-600">{{ $station->firmware_version }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-3">Status & Location</h3>
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Current Status</dt>
                                    <dd class="text-sm">
                                    <span class="status-badge {{ $station->status_color }}">
                                        {{ $station->status }}
                                    </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Connection Status</dt>
                                    <dd class="text-sm">
                                    <span class="status-badge {{ $station->isOnline() ? 'status-online' : 'status-offline' }}">
                                        {{ $station->online_status }}
                                    </span>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Last Heartbeat</dt>
                                    <dd class="text-sm text-gray-600">
                                        {{ $station->last_heartbeat ? $station->last_heartbeat->diffForHumans() : 'Never' }}
                                        @if($station->last_heartbeat)
                                            <br><span class="text-xs text-gray-500">{{ $station->last_heartbeat->format('M j, Y H:i:s') }}</span>
                                        @endif
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Location</dt>
                                    <dd class="text-sm text-gray-600">{{ $station->location ?: 'Not specified' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-900">Connectors</dt>
                                    <dd class="text-sm text-gray-600">{{ $station->connector_count }} connector{{ $station->connector_count > 1 ? 's' : '' }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                    @if($station->description)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-sm font-medium text-gray-900 mb-2">Description</h3>
                            <p class="text-sm text-gray-600">{{ $station->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Statistics Card -->
        <div>
            <div class="card">
                <div class="card-header">
                    <h2 class="text-lg font-medium text-gray-900">Statistics</h2>
                </div>
                <div class="card-body space-y-4">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total_transactions'] }}</div>
                        <div class="text-sm text-gray-500">Total Transactions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-ocpp-600">{{ $stats['active_transactions'] }}</div>
                        <div class="text-sm text-gray-500">Active Sessions</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">
                            {{ number_format($stats['total_energy'] / 1000, 1) }} kWh
                        </div>
                        <div class="text-sm text-gray-500">Total Energy Delivered</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-purple-600">
                            {{ number_format($stats['avg_session_duration']) }} min
                        </div>
                        <div class="text-sm text-gray-500">Avg Session Duration</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Connectors Status -->
    <div class="mb-8">
        <div class="card">
            <div class="card-header">
                <h2 class="text-lg font-medium text-gray-900">Connector Status</h2>
            </div>
            <div class="card-body">
                @if($station->connectors->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        @foreach($station->connectors as $connector)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between mb-2">
                                    <h3 class="text-sm font-medium text-gray-900">Connector {{ $connector->connector_id }}</h3>
                                    <span class="status-badge {{ $connector->status === 'Available' ? 'status-available' : ($connector->status === 'Charging' ? 'status-charging' : 'status-offline') }}">
                                    {{ $connector->status }}
                                </span>
                                </div>
                                @if($connector->error_code && $connector->error_code !== 'NoError')
                                    <div class="text-xs text-red-600 mb-1">
                                        Error: {{ $connector->error_code }}
                                    </div>
                                @endif
                                @if($connector->info)
                                    <div class="text-xs text-gray-500">
                                        {{ $connector->info }}
                                    </div>
                                @endif
                                <div class="text-xs text-gray-400 mt-2">
                                    Updated {{ $connector->updated_at->diffForHumans() }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No connectors found</h3>
                        <p class="mt-1 text-sm text-gray-500">Connectors will appear here once the station connects.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">Recent Transactions</h2>
                <span class="text-sm text-gray-500">Last 20 transactions</span>
            </div>
        </div>
        <div class="overflow-hidden">
            @if($station->transactions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                        <tr>
                            <th class="table-header">Transaction ID</th>
                            <th class="table-header">Connector</th>
                            <th class="table-header">ID Tag</th>
                            <th class="table-header">Start Time</th>
                            <th class="table-header">Duration</th>
                            <th class="table-header">Energy</th>
                            <th class="table-header">Status</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($station->transactions as $transaction)
                            <tr class="hover:bg-gray-50">
                                <td class="table-cell">
                                    <span class="font-mono text-sm">{{ $transaction->transaction_id }}</span>
                                </td>
                                <td class="table-cell">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-gray-100 rounded-full flex items-center justify-center mr-2">
                                            <span class="text-xs font-medium text-gray-600">{{ $transaction->connector_id }}</span>
                                        </div>
                                        Connector {{ $transaction->connector_id }}
                                    </div>
                                </td>
                                <td class="table-cell">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ $transaction->id_tag }}
                                </span>
                                </td>
                                <td class="table-cell">
                                    <div class="text-sm text-gray-900">{{ $transaction->start_time->format('M j, H:i') }}</div>
                                    <div class="text-xs text-gray-500">{{ $transaction->start_time->diffForHumans() }}</div>
                                </td>
                                <td class="table-cell">
                                    <span class="text-sm text-gray-900">{{ $transaction->duration }}</span>
                                </td>
                                <td class="table-cell">
                                    @if($transaction->energy_consumed)
                                        <span class="text-sm font-medium text-gray-900">
                                        {{ number_format($transaction->energy_consumed / 1000, 2) }} kWh
                                    </span>
                                    @else
                                        <span class="text-sm text-gray-500">-</span>
                                    @endif
                                </td>
                                <td class="table-cell">
                                    @if($transaction->stop_time)
                                        <span class="status-badge status-available">
                                        Completed
                                    </span>
                                    @else
                                        <span class="status-badge status-charging">
                                        Active
                                    </span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No transactions yet</h3>
                    <p class="mt-1 text-sm text-gray-500">Transactions will appear here once charging sessions begin.</p>
                    <div class="mt-6">
{{--                        <a href="{{ route('test-client') }}" class="btn-primary">--}}
{{--                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">--}}
{{--                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>--}}
{{--                            </svg>--}}
{{--                            Test with Simulator--}}
{{--                        </a>--}}
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
{{--            <div class="bg-white rounded-lg p-4 border border-gray-200">--}}
{{--                <h4 class="text-sm font-medium text-gray-900 mb-2">Test Connection</h4>--}}
{{--                <p class="text-sm text-gray-600 mb-3">Test the OCPP connection with this station using the simulator.</p>--}}
{{--                <a href="{{ route('test-client') }}" class="btn-outline text-sm">--}}
{{--                    Open Test Client--}}
{{--                </a>--}}
{{--            </div>--}}

            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <h4 class="text-sm font-medium text-gray-900 mb-2">View All Stations</h4>
                <p class="text-sm text-gray-600 mb-3">Go back to the stations overview to manage all your charging points.</p>
                <a href="{{ route('charging-stations.index') }}" class="btn-outline text-sm">
                    All Stations
                </a>
            </div>

            <div class="bg-white rounded-lg p-4 border border-gray-200">
                <h4 class="text-sm font-medium text-gray-900 mb-2">Server Status</h4>
                <p class="text-sm text-gray-600 mb-3">Check the OCPP server status and connected stations.</p>
                <button onclick="checkServerStatus()" class="btn-outline text-sm">
                    Check Status
                </button>
            </div>
        </div>
    </div>

    <script>
        function checkServerStatus() {
            // This would typically make an AJAX call to check server status
            alert('Server status check would be implemented here. For now, check the console logs of your OCPP server.');
        }

        // Auto-refresh page every 30 seconds to show updated data
        setInterval(function() {
            // Only refresh if the station is online to show real-time updates
            @if($station->isOnline())
            location.reload();
            @endif
        }, 30000);
    </script>
@endsection
