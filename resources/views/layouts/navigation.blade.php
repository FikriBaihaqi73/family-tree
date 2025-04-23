<nav class="bg-white shadow-lg">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-xl font-bold text-gray-800">
                    Silsilah
                </a>
            </div>
            <div class="flex items-center">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900 px-3 py-2">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2">Login</a>
                    <a href="{{ route('register') }}" class="text-gray-600 hover:text-gray-900 px-3 py-2">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
