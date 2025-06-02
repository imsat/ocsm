@extends('layouts.app')

@section('title', 'Add Charging Station - OCPP Manager')

@section('content')
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('charging-stations.index') }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add Charging Station</h1>
                <p class="mt-1 text-sm text-gray-500">Create a new OCPP 1.6J charging station</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('charging-stations.store') }}" method="POST" class="card">
            @csrf
            <div class="card-header">
                <h2 class="text-lg font-medium text-gray-900">Station Information</h2>
            </div>
            <div class="card-body space-y-6">
                <!-- Station Identifier -->
                <div>
                    <label for="identifier" class="label">Station Identifier *</label>
                    <input type="text"
                           name="identifier"
                           id="identifier"
                           value="{{ old('identifier') }}"
                           class="input-field @error('identifier') border-red-300 @enderror"
                           placeholder="e.g., STATION_001"
                           required>
                    @error('identifier')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Unique identifier for this charging station</p>
                </div>

                <!-- Vendor -->
                <div>
                    <label for="vendor" class="label">Vendor *</label>
                    <input type="text"
                           name="vendor"
                           id="vendor"
                           value="{{ old('vendor') }}"
                           class="input-field @error('vendor') border-red-300 @enderror"
                           placeholder="e.g., Tesla, ChargePoint, ABB"
                           required>
                    @error('vendor')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="label">Model *</label>
                    <input type="text"
                           name="model"
                           id="model"
                           value="{{ old('model') }}"
                           class="input-field @error('model') border-red-300 @enderror"
                           placeholder="e.g., Supercharger V3, DC Fast Charger"
                           required>
                    @error('model')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="label">Serial Number *</label>
                    <input type="text"
                           name="serial_number"
                           id="serial_number"
                           value="{{ old('serial_number') }}"
                           class="input-field @error('serial_number') border-red-300 @enderror"
                           placeholder="e.g., SN123456789"
                           required>
                    @error('serial_number')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Firmware Version -->
                <div>
                    <label for="firmware_version" class="label">Firmware Version</label>
                    <input type="text"
                           name="firmware_version"
                           id="firmware_version"
                           value="{{ old('firmware_version') }}"
                           class="input-field @error('firmware_version') border-red-300 @enderror"
                           placeholder="e.g., 1.0.0">
                    @error('firmware_version')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Connector Count -->
                <div>
                    <label for="connector_count" class="label">Number of Connectors *</label>
                    <select name="connector_count"
                            id="connector_count"
                            class="input-field @error('connector_count') border-red-300 @enderror"
                            required>
                        @for($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}" {{ old('connector_count', 1) == $i ? 'selected' : '' }}>
                                {{ $i }} Connector{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                    @error('connector_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="label">Location</label>
                    <input type="text"
                           name="location"
                           id="location"
                           value="{{ old('location') }}"
                           class="input-field @error('location') border-red-300 @enderror"
                           placeholder="e.g., Parking Lot A, Building 1">
                    @error('location')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea name="description"
                              id="description"
                              rows="3"
                              class="input-field @error('description') border-red-300 @enderror"
                              placeholder="Additional notes about this charging station">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('charging-stations.index') }}" class="btn-outline">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Station
                </button>
            </div>
        </form>
    </div>
@endsection
