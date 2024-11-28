@extends('layouts.app')



@section('content')
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-semibold text-gray-900">Borrowings</h1>
                <a href="{{route('borrowings.create')}}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Create New Borrowing
                </a>
            </div>

            <!-- Table Section -->
            <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Asset ID</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Asset Name</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">User Name</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($borrowings as $borrowing)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ ($borrowings->currentPage() - 1) * $borrowings->perPage() + $loop->iteration }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $borrowing->asset->asset_id }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $borrowing->asset->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">{{ $borrowing->employee->name ?? 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $borrowing->status === 'returned' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $borrowing->status }}
                                    </span>
                                </td>
                                
                                <td class="px-6 py-4 whitespace-nowrap flex justify-center gap-2">
                                        <!-- View Details Button -->
                                        <a href="{{ route('borrowings.document', $borrowing->id) }}" class="text-green-600 hover:text-green-900" title="View Details">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 15v4c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2v-4M17 9l-5 5-5-5M12 12.8V2.5"/>
                                            </svg>
                                        </a>
                                    
                                        
                                        <!-- Edit Button -->
                                        <form action="{{route('borrowings.update', $borrowing->id)}}" method="POST" class="text-indigo-600 hover:text-indigo-900" title="Return Button">
                                            @csrf
                                            @method('PUT')
                                            <input name="asset_id" type="text" class="hidden" value="{{$borrowing->asset_id}}">
                                            <input name="employee_id" type="text" class="hidden" value="{{$borrowing->employee_id}}">
                                            <input name="date_of_receipt" type="date" class="hidden" value="{{ date('Y-m-d', strtotime($borrowing->date_of_receipt)) }}">
                                            <input name="date_of_return" type="date" class="hidden" value="{{ now()->format('Y-m-d') }}">
                                            <input name="status" type="text" class="hidden" value="returned">
                                            <button type="submit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="h-5 w-5">
                                                        <g fill="none" stroke="currentColor" stroke-width="3    " stroke-linecap="round" stroke-linejoin="round">
                                                          <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c2.39 0 4.68.94 6.36 2.63l1.64-1.64" />
                                                          <path d="M21 5v4h-4" />
                                                        </g>
                                                    </svg>
                                            </button>
                                        </form>
                                        
                                        <!-- Delete Button -->
                                        <form action="{{route('borrowings.destroy', $borrowing->id)}}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this asset?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </form>
                                    {{-- </div> --}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $borrowings->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection