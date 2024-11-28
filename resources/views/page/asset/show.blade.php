@extends('layouts.blank')



@section('content')
    <div class="min-h-screen py-6 flex flex-col justify-center sm:py-12">
        <div class="relative py-3 sm:max-w-xl md:max-w-4xl mx-auto">
            <div class="relative px-4 py-10 bg-white mx-8 md:mx-0 shadow rounded-3xl sm:p-10">
                <div class="max-w-md mx-auto">
                    <div class="divide-y divide-gray-200">
                        <div class="py-8 text-base leading-6 space-y-4 text-gray-700 sm:text-lg sm:leading-7">
                            <h2 class="text-2xl font-bold mb-6">Asset Details</h2>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Asset ID</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->asset_id }}</dd>
                                </div>

                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Name</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->name }}</dd>
                                </div>

                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Category</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->category->name }}</dd>
                                </div>

                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Subcategory</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->subcategory->name }}</dd>
                                </div>

                                {{-- Continue with all other fields --}}

                                @if($asset->merk)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Merk</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->merk ?? '-' }}</dd>
                                    </div>
                                @endif

                                @if($asset->color)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Color</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->color ?? '-' }}</dd>
                                    </div>
                                @endif

                                @if($asset->serial_number)
                                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Serial Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->serial_number ?? '-' }}</dd>
                                    </div>
                                @endif

                                @if($asset->purchase_order_number)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Purchase Order Number</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->purchase_order_number ?? '-' }}</dd>
                                    </div>
                                @endif

                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Purchase Price</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        @if($asset->purchase_price)
                                            Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}
                                        @else
                                            -
                                        @endif
                                    </dd>
                                </div>

                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Condition</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $asset->condition === 'new' ? 'bg-green-100 text-green-800' : 
                                            ($asset->condition === 'used' ? 'bg-yellow-100 text-yellow-800' : 
                                            'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($asset->condition) }}
                                        </span>
                                    </dd>
                                </div>

                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $asset->status === 'available' ? 'bg-green-100 text-green-800' : 
                                            ($asset->status === 'in_use' ? 'bg-blue-100 text-blue-800' : 
                                            ($asset->status === 'maintenance' ? 'bg-yellow-100 text-yellow-800' : 
                                            'bg-red-100 text-red-800')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $asset->status)) }}
                                        </span>
                                    </dd>
                                </div>

                                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Location</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->location ?? '-' }}</dd>
                                </div>

                                @if($asset->remarks)
                                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                        <dt class="text-sm font-medium text-gray-500">Remarks</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $asset->remaks ?? '-' }}</dd>
                                    </div>
                                @endif

                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">Date of Receipt</dt>
                                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                        {{ \Carbon\Carbon::parse($asset->date_of_receipt)->format('d F Y') }}
                                    </dd>
                                </div>


                                @if($asset->qr_code_path)
                                <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 rounded-lg">
                                    <dt class="text-sm font-medium text-gray-500">QR Code</dt>
                                    <dd class="mt-1 sm:mt-0 sm:col-span-2">
                                        <img src="{{ asset('qrcodes/' . $asset->qr_code_path) }}" alt="Asset QR Code" class="h-32 w-32">
                                    </dd>
                                </div>
                                @endif
                            </div>

                            <div class="pt-5">
                                <div class="flex justify-end space-x-3">
                                    <a href="{{ route('assets.index') }}"
                                        class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Back to List
                                    </a>
                                    {{-- {{ route('asset.edit', $asset->id) }} --}}
                                    {{-- <a href="#"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Edit Asset
                                    </a> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection