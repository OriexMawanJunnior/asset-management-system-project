@extends('layouts.blank')



@section('content')
<div class="min-h-screen py-6 flex flex-col justify-center sm:py-12">
    <div class="relative py-3 sm:max-w-xl md:max-w-4xl mx-auto">
        <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
            <div class="max-w-md mx-auto">
                <div class="divide-y divide-gray-200">
                    <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                        <h2 class="text-2xl font-bold mb-6">Create New Borrowing</h2>
                        <form action="{{ route('borrowings.store') }}" method="POST" class="space-y-6" id="borrowingForm">
                            @csrf
                            
                            {{-- Basic Information --}}
                            <div class="space-y-4">
                                <div>
                                    @php
                                    $fields = [
                                        [
                                            'id' => 'asset',
                                            'label' => 'Asset',
                                            'placeholder' => 'Search asset by asset id...'
                                        ],
                                        [
                                            'id' => 'employee',
                                            'label' => 'Employee',
                                            'placeholder' => 'Search employee...'
                                        ]
                                    ];
                                    @endphp
                                    @foreach($fields as $field)
                                    <div>
                                    <label for="{{ $field['id'] }}" class="text-sm font-medium text-gray-700">{{ $field['label'] }} <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="text" 
                                                id="{{ $field['id'] }}_search" 
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="{{ $field['placeholder'] }}">
                                            <input type="hidden" 
                                                name="{{ $field['id'] }}_id" 
                                                id="{{ $field['id'] }}_id" 
                                                required>
                                            <div id="{{ $field['id'] }}_suggestions" 
                                                class="absolute z-10 w-full bg-white shadow-lg rounded-md hidden">
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                   

                                {{-- Date Fields --}}
                                <div>
                                    <label for="date_of_receipt" class="text-sm font-medium text-gray-700">
                                        Date of Receipt <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" 
                                        name="date_of_receipt" 
                                        id="date_of_receipt" 
                                        required
                                        value="{{ old('date_of_receipt', date('Y-m-d')) }}"
                                        min="{{ date('Y-m-d') }}"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 @error('date_of_receipt') border-red-500 @enderror">
                                    @error('date_of_receipt')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <input type="text" id="status" name="status" class="hidden" value="borrowed">
                            <div class="pt-5">
                                <div class="flex justify-end">
                                    <button type="button" 
                                        onclick="history.back()"
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        id="submitBtn"
                                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data from controller
    const assets = @json($assets);
    const employees = @json($employees);

    // Filter available assets
    const availableAssets = assets.filter(asset => asset.status !== 'borrowed');

    // Configuration for each field
    const fieldsConfig = {
        asset: {
            data: availableAssets,
            displayField: 'asset_id',
            searchFields: ['asset_id', 'name'],
            emptyMessage: 'Asset available not found'
        },
        employee: {
            data: employees,
            displayField: 'name',
            searchFields: ['name', 'employee_id'],
            emptyMessage: 'Employee not found'
        }
    };

    // Setup autocomplete for each field
    Object.keys(fieldsConfig).forEach(fieldName => {
        setupAutocomplete(fieldName, fieldsConfig[fieldName]);
    });

    function setupAutocomplete(fieldName, config) {
        const searchInput = document.getElementById(`${fieldName}_search`);
        const hiddenInput = document.getElementById(`${fieldName}_id`);
        const suggestionBox = document.getElementById(`${fieldName}_suggestions`);
        
        // Update placeholder if no available assets
        if (fieldName === 'asset' && config.data.length === 0) {
            searchInput.placeholder = config.emptyMessage;
            searchInput.disabled = true;
            return;
        }

        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            // Clear suggestions if search term is too short
            if (searchTerm.length < 1) {
                suggestionBox.classList.add('hidden');
                return;
            }
            
            // Filter data based on search term
            const filteredData = config.data.filter(item =>
                config.searchFields.some(field => 
                    item[field] && item[field].toString().toLowerCase().includes(searchTerm)
                )
            );
            
            // Show suggestions or empty message
            if (filteredData.length > 0) {
                // Generate suggestions HTML
                suggestionBox.innerHTML = filteredData.map(item => `
                    <div class="suggestion-item p-2 hover:bg-gray-100 cursor-pointer" 
                        data-id="${item.id}" 
                        data-value="${item[config.displayField]}">
                        <div class="font-medium">${item[config.displayField]}</div>
                        ${getSecondaryText(item, config)}
                    </div>
                `).join('');
                
                suggestionBox.classList.remove('hidden');
                
                // Add click events to suggestions
                suggestionBox.querySelectorAll('.suggestion-item').forEach(div => {
                    div.addEventListener('click', function() {
                        searchInput.value = this.dataset.value;
                        hiddenInput.value = this.dataset.id;
                        suggestionBox.classList.add('hidden');
                    });
                });
            } else {
                // Show empty message
                suggestionBox.innerHTML = `
                    <div class="p-2 text-gray-500 text-sm">
                        ${config.emptyMessage}
                    </div>
                `;
                suggestionBox.classList.remove('hidden');
            }
        });

        // Handle keyboard navigation
        searchInput.addEventListener('keydown', function(e) {
            const suggestions = suggestionBox.querySelectorAll('.suggestion-item');
            const currentActive = suggestionBox.querySelector('.suggestion-item.active');
            let nextActive;

            if (suggestions.length === 0) return;

            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    if (!currentActive) {
                        nextActive = suggestions[0];
                    } else {
                        const nextElement = currentActive.nextElementSibling;
                        nextActive = nextElement || suggestions[0];
                    }
                    break;

                case 'ArrowUp':
                    e.preventDefault();
                    if (!currentActive) {
                        nextActive = suggestions[suggestions.length - 1];
                    } else {
                        const prevElement = currentActive.previousElementSibling;
                        nextActive = prevElement || suggestions[suggestions.length - 1];
                    }
                    break;

                case 'Enter':
                    if (currentActive) {
                        e.preventDefault();
                        currentActive.click();
                    }
                    return;
            }

            if (nextActive) {
                currentActive?.classList.remove('active');
                nextActive.classList.add('active');
                nextActive.scrollIntoView({ block: 'nearest' });
            }
        });
    }

    // Helper function to get secondary display text
    function getSecondaryText(item, config) {
        if (config.displayField === 'asset_id') {
            return `
                <div class="text-sm text-gray-600">
                    ${item.name || ''}
                    ${item.status ? `<span class="ml-2 px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Available</span>` : ''}
                </div>`;
        }
        if (config.displayField === 'name') {
            return `<div class="text-sm text-gray-600">${item.employee_id || ''}</div>`;
        }
        return '';
    }

    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        Object.keys(fieldsConfig).forEach(fieldName => {
            const searchInput = document.getElementById(`${fieldName}_search`);
            const suggestionBox = document.getElementById(`${fieldName}_suggestions`);
            
            if (!searchInput?.contains(e.target) && !suggestionBox?.contains(e.target)) {
                suggestionBox?.classList.add('hidden');
            }
        });
    });
});
</script>

@endsection