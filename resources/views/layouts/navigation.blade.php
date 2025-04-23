<nav class="bg-gradient-to-r from-indigo-600 to-purple-600 shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-white">
                    Silsilah
                </a>
            </div>
            <div class="flex items-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-white hover:text-indigo-200 px-3 py-2">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-white hover:text-indigo-200 px-3 py-2">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-white hover:text-indigo-200 px-3 py-2">Login</a>
                    <a href="{{ route('register') }}" class="ml-2 bg-white text-indigo-600 hover:bg-indigo-100 px-4 py-2 rounded-md">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
