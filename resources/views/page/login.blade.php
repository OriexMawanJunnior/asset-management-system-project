@extends('layouts.blank')



@section('content')
    <div class="min-h-screen flex items-center justify-center">
        <div class="bg-purple-700 p-8 rounded-lg shadow-lg w-96">
            <h1 class="text-3xl font-bold text-white mb-8">Login</h1>
            

            

            <form action="{{ route('signIn') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div>
                        <label class="text-white mb-2 block">Email</label>
                        <input 
                            type="text" 
                            name="email" 
                            class="w-full px-4 py-2 rounded-lg @error('email') border-red-500 @enderror" 
                            value="{{ old('email') }}"
                            required 
                            autofocus
                        >
                    </div>
                    <div>
                        <label class="text-white mb-2 block">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            class="w-full mb-8 px-4 py-2 rounded-lg @error('password') border-red-500 @enderror" 
                            required
                        >
                    </div>
                    <button type="submit" class="w-full bg-white text-purple-700 font-medium py-2 px-4 rounded-full hover:bg-gray-100 transition">
                        Sign In
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

