@props(['title', 'value', 'color' => 'purple'])

<div class="bg-white rounded-3xl p-6 shadow-sm">
    <div class="flex-col items-center gap-4 mb-4">
        <div class="w-12 h-12 flex items-center justify-center rounded-xl">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M32 32V6H2V32H32ZM32 32H46V22L40 16H32V32ZM16 37C16 39.7614 13.7614 42 11 42C8.23858 42 6 39.7614 6 37C6 34.2386 8.23858 32 11 32C13.7614 32 16 34.2386 16 37ZM42 37C42 39.7614 39.7614 42 37 42C34.2386 42 32 39.7614 32 37C32 34.2386 34.2386 32 37 32C39.7614 32 42 34.2386 42 37Z" 
            stroke="{{ $color }}" 
            stroke-width="4" 
            stroke-linecap="round" 
            stroke-linejoin="round"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-[#BF6A02]">{{ $title }}</h3>
    </div>
    <p class="text-4xl font-bold">{{ $value }}</p>
</div>