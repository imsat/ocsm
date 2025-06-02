@extends('layouts.app')

@section('title', 'Edit Charging Station - OCPP Manager')

@section('content')
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('charging-stations.show', $station) }}" class="text-gray-500 hover:text-gray-700 mr-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Charging Station</h1>
                <p class="mt-1 text-sm text-gray-500">Update {{ $station->identifier }} configuration</p>
            </div>
        </div>
    </div>

    <div class="max-w-2xl">
        <form action="{{ route('charging-stations.update', $station) }}" method="POST" class="card">
            @csrf
            @method('PUT')
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
                           value="{{ old('identifier', $station->identifier) }}"
                           class="input-field @error('identifier') border-red-300 @enderror"
                           required>
                    @error('identifier')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Vendor -->
                <div>
                    <label for="vendor" class="label">Vendor *</label>
                    <input type="text"
                           name="vendor"
                           id="vendor"
                           value="{{ old('vendor', $station->vendor) }}"
                           class="input-field @error('vendor') border-red-300 @enderror"
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
                           value="{{ old('model', $station->model) }}"
                           class="input-field @error('model') border-red-300 @enderror"
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
                           value="{{ old('serial_number', $station->serial_number) }}"
                           class="input-field @error('serial_number') border-red-300 @enderror"
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
                           value="{{ old('firmware_version', $station->firmware_version) }}"
                           class="input-field @error('firmware_version') border-red-300 @enderror">
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
                            <option value="{{ $i }}" {{ old('connector_count', $station->connector_count) == $i ? 'selected' : '' }}>
                                {{ $i }} Connector{{ $i > 1 ? 's' : '' }}
                            </option>
                        @endfor
                    </select>
                    @error('connector_count')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    @if($station->connector_count > 1)
                        <p class="mt-1 text-sm text-yellow-600">
                            ⚠️ Reducing connector count will remove excess connectors (if no active transactions)
                        </p>
                    @endif
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="label">Location</label>
                    <input type="text"
                           name="location"
                           id="location"
                           value="{{ old('location', $station->location) }}"
                           class="input-field @error('location') border-red-300 @enderror">
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
                              class="input-field @error('description') border-red-300 @enderror">{{ old('description', $station->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                <a href="{{ route('charging-stations.show', $station) }}" class="btn-outline">
                    Cancel
                </a>
                <button type="submit" class="btn-primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Update Station
                </button>
            </div>
        </form>
    </div>
@endsection
