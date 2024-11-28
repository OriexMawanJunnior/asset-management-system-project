@extends('layouts.blank')



@section('content')
    <div class="min-h-screen py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl md:max-w-4xl mx-auto">
            <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
                <div class="max-w-md mx-auto">
                    <div class="divide-y divide-gray-200">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                            <h2 class="text-2xl font-bold mb-6">Create New Subcategory</h2>
                            <form action="{{route('subcategories.update', $subcategory->id)}}" method="POST" class="space-y-6">
                                @csrf
                                @method('PUT')
                                
                                {{-- Basic Information --}}
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="text-sm font-medium text-gray-700">Name <span class="text-red-500">*</span></label>
                                        <input type="text" name="name" id="name" required
                                            value="{{ old('name', $subcategory->name) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="code" class="text-sm font-medium text-gray-700">Code <span class="text-red-500">*</span></label>
                                        <input type="text" name="code" id="code" required
                                            value="{{ old('code', $subcategory->code) }}"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    <div>
                                        <label for="category_id" class="text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                                        <div class="relative">
                                            <input type="text" 
                                                id="category_search" 
                                                value="{{$subcategory->category->name}}"
                                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                placeholder="Search category...">
                                            <input type="hidden" 
                                                name="category_id" 
                                                id="category_id" 
                                                value="{{$subcategory->category_id}}"
                                                required>
                                            <div id="category_suggestions" 
                                                class="absolute z-10 w-full bg-white shadow-lg rounded-md hidden">
                                            </div>
                                        </div>
                                    </div>

                                    
                                </div>

                                <div class="pt-5">
                                    <div class="flex justify-end">
                                        <button type="button" onclick="history.back()"
                                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            Cancel
                                        </button>
                                        <button type="submit"
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
        document.addEventListener('DOMContentLoaded', () => {
        const categorySearch = document.getElementById('category_search');
        const categoryId = document.getElementById('category_id');
        const categorySuggestions = document.getElementById('category_suggestions');

        // Function to filter categories based on search input
        function filterCategories(searchTerm) {
            const filteredCategories = categories.filter(category => 
                category.name.toLowerCase().includes(searchTerm.toLowerCase())
            );
            
            return filteredCategories;
        }

        // Function to render category suggestions
        function renderSuggestions(suggestions) {
            // Clear previous suggestions
            categorySuggestions.innerHTML = '';
            
            // Hide suggestions if no search term
            if (suggestions.length === 0) {
                categorySuggestions.classList.add('hidden');
                return;
            }

            // Show suggestions container
            categorySuggestions.classList.remove('hidden');

            // Create suggestion items
            suggestions.forEach(category => {
                const suggestionItem = document.createElement('div');
                suggestionItem.classList.add(
                    'p-2', 
                    'hover:bg-gray-100', 
                    'cursor-pointer', 
                    'border-b', 
                    'last:border-b-0'
                );
                suggestionItem.textContent = category.name;
                
                // Select category when clicked
                suggestionItem.addEventListener('click', () => {
                    categorySearch.value = category.name;
                    categoryId.value = category.id;
                    categorySuggestions.classList.add('hidden');
                });

                categorySuggestions.appendChild(suggestionItem);
            });
        }

        // Event listener for search input
        categorySearch.addEventListener('input', (e) => {
            const searchTerm = e.target.value;
            const suggestions = filterCategories(searchTerm);
            renderSuggestions(suggestions);
        });

        // Close suggestions when clicking outside
        document.addEventListener('click', (e) => {
            if (!categorySearch.contains(e.target) && 
                !categorySuggestions.contains(e.target)) {
                categorySuggestions.classList.add('hidden');
            }
        });
    });
    </script>
@endsection