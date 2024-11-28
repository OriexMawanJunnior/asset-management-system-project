<aside class="w-64 bg-purple-700 text-white min-h-screen">
    <div class="p-4">
        <div class="flex items-center gap-2 mb-8">
            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M42 10C42 13.3137 33.9411 16 24 16C14.0589 16 6 13.3137 6 10M42 10C42 6.68629 33.9411 4 24 4C14.0589 4 6 6.68629 6 10M42 10V38C42 41.32 34 44 24 44C14 44 6 41.32 6 38V10M42 24C42 27.32 34 30 24 30C14 30 6 27.32 6 24" stroke="#F5F5F5" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="text-2xl font-bold">AMS.</span>
        </div>
        
        <nav class="space-y-4">
            <a href="/dashboard" class="flex items-center gap-3 p-3 rounded-full {{ request()->is('dashboard') ? 'bg-white text-purple-700' : 'hover:bg-purple-600' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path d="M4 5h16M4 12h16m-7 7h7"/>
                </svg>
                <span>Dashboard</span>
            </a>
            <a href="/assets" class="flex items-center gap-3 p-3 rounded-full {{ request()->is('assets') ? 'bg-white text-purple-700' : 'hover:bg-purple-600' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span>Assets</span>
            </a>
            <a href="/users" class="flex items-center gap-3 p-3 rounded-full {{ request()->is('users') ? 'bg-white text-purple-700' : 'hover:bg-purple-600' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span>Users</span>
            </a>
            <a href="/borrowings" class="flex items-center gap-3 p-3 rounded-full {{ request()->is('borrowings') ? 'bg-white text-purple-700' : 'hover:bg-purple-600' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor">
                    <path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span>Borrowing</span>
            </a>
            <a href="/categories" class="flex items-center gap-3 p-3 rounded-full {{ request()->is('categories') ? 'bg-white text-purple-700' : 'hover:bg-purple-600' }}">
                <svg fill="none" stroke="currentColor" class="h-6 w-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 3C12.5523 3 13 3.44772 13 4V5H17C18.6569 5 20 6.34315 20 8C20 9.65685 18.6569 11 17 11H13V13H14C15.6569 13 17 14.3431 17 16C17 17.6569 15.6569 19 14 19H13V20C13 20.5523 12.5523 21 12 21C11.4477 21 11 20.5523 11 20V19H10C8.34315 19 7 17.6569 7 16C7 14.3431 8.34315 13 10 13H11V11H7C5.34315 11 4 9.65685 4 8C4 6.34315 5.34315 5 7 5H11V4C11 3.44772 11.4477 3 12 3ZM7 7C6.44772 7 6 7.44772 6 8C6 8.55228 6.44772 9 7 9H12H17C17.5523 9 18 8.55228 18 8C18 7.44772 17.5523 7 17 7H12H7ZM10 15C9.44772 15 9 15.4477 9 16C9 16.5523 9.44772 17 10 17H12H14C14.5523 17 15 16.5523 15 16C15 15.4477 14.5523 15 14 15H12H10Z" />
                </svg>
                <span>Category</span>
            </a>
            <a href="/subcategories" class="flex items-center gap-3 p-3 rounded-full {{ request()->is('subcategories') ? 'bg-white text-purple-700' : 'hover:bg-purple-600' }}">
                <svg fill="none" stroke="currentColor" class="h-6 w-6" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M5 7C5 5.34315 6.34315 4 8 4C9.65685 4 11 5.34315 11 7V11H13V10C13 8.34315 14.3431 7 16 7C17.6569 7 19 8.34315 19 10V11H20C20.5523 11 21 11.4477 21 12C21 12.5523 20.5523 13 20 13H19V14C19 15.6569 17.6569 17 16 17C14.3431 17 13 15.6569 13 14V13H11V17C11 18.6569 9.65685 20 8 20C6.34315 20 5 18.6569 5 17V13H4C3.44772 13 3 12.5523 3 12C3 11.4477 3.44772 11 4 11H5V7ZM8 6C7.44772 6 7 6.44772 7 7V12V17C7 17.5523 7.44772 18 8 18C8.55228 18 9 17.5523 9 17V12V7C9 6.44772 8.55228 6 8 6ZM16 9C15.4477 9 15 9.44772 15 10V12V14C15 14.5523 15.4477 15 16 15C16.5523 15 17 14.5523 17 14V12V10C17 9.44772 16.5523 9 16 9Z"/>
                    
                </svg>
                <span>Subcategory</span>
            </a>
        </nav>
    </div>
</aside>