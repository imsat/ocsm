@extends('layouts.app')

@section('title', 'Charging Stations - OCPP Manager')

@section('content')
    <div class="mb-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-gray-900">Charging Stations</h1>
                <p class="mt-1 text-sm text-gray-500">Manage your OCPP 1.6J charging stations</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <a href="{{ route('charging-stations.create') }}" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add Station
                </a>
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

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="ml-3 text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-medium text-gray-900">All Stations ({{ $stations->total() }})</h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500">{{ $stations->count() }} of {{ $stations->total() }} stations</span>
                </div>
            </div>
        </div>
        <div class="overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="table-header">Station</th>
                        <th class="table-header">Vendor & Model</th>
                        <th class="table-header">Connectors</th>
                        <th class="table-header">Status</th>
                        <th class="table-header">Last Heartbeat</th>
                        <th class="table-header">Location</th>
                        <th class="table-header">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($stations as $station)
                        <tr class="hover:bg-gray-50">
                            <td class="table-cell">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-ocpp-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-ocpp-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $station->identifier }}</div>
                                        <div class="text-sm text-gray-500">S/N: {{ $station->serial_number }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="table-cell">
                                <div class="text-sm text-gray-900">{{ $station->vendor }}</div>
                                <div class="text-sm text-gray-500">{{ $station->model }}</div>
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center">
                                    <span class="text-sm font-medium text-gray-900">{{ $station->connector_count }}</span>
                                    <span class="ml-1 text-sm text-gray-500">connector{{ $station->connector_count > 1 ? 's' : '' }}</span>
                                </div>
                            </td>
                            <td class="table-cell">
                                <div class="flex flex-col space-y-1">
                                <span class="status-badge {{ $station->status_color }}">
                                    {{ $station->status }}
                                </span>
                                    <span class="status-badge {{ $station->isOnline() ? 'status-online' : 'status-offline' }}">
                                    {{ $station->online_status }}
                                </span>
                                </div>
                            </td>
                            <td class="table-cell">
                                <div class="text-sm text-gray-900">
                                    {{ $station->last_heartbeat ? $station->last_heartbeat->diffForHumans() : 'Never' }}
                                </div>
                                @if($station->last_heartbeat)
                                    <div class="text-xs text-gray-500">
                                        {{ $station->last_heartbeat->format('M j, Y H:i') }}
                                    </div>
                                @endif
                            </td>
                            <td class="table-cell">
                                <div class="text-sm text-gray-900">
                                    {{ $station->location ?: 'Not specified' }}
                                </div>
                            </td>
                            <td class="table-cell">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('charging-stations.show', $station) }}"
                                       class="text-ocpp-600 hover:text-ocpp-900 text-sm font-medium">
                                        View
                                    </a>
                                    <a href="{{ route('charging-stations.edit', $station) }}"
                                       class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                        Edit
                                    </a>
                                    <form action="{{ route('charging-stations.destroy', $station) }}"
                                          method="POST"
                                          class="inline"
                                          onsubmit="return confirm('Are you sure you want to delete this charging station?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">No charging stations</h3>
                                    <p class="mt-1 text-sm text-gray-500">Get started by adding your first charging station.</p>
                                    <div class="mt-6">
                                        <a href="{{ route('charging-stations.create') }}" class="btn-primary">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Add Charging Station
                                        </a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($stations->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $stations->links() }}
            </div>
        @endif
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="stat-card">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Stations</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $stations->total() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Online Now</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $stations->filter(fn($s) => $s->isOnline())->count() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="stat-card">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Connectors</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $stations->sum('connector_count') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
