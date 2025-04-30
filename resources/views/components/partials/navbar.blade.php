<nav
    class="bg-white/70 backdrop-blur-md shadow-md fixed inset-x-0 sm:bottom-auto bottom-0 rounded-t-xl z-50 md:inset-x-[10%] md:top-5 md:rounded-xl md:block">
    <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold text-blue-600 hidden md:block">Sistem Akuntansi</h1>
        <ul class="grid grid-cols-4 space-x-4 md:space-x-6 md:items-center w-full md:w-auto">
            <li
                class="bg-slate-100 p-2 sm:p-0 rounded-md shadow-md flex justify-center items-center sm:justify-left sm:bg-transparent sm:rounded-none sm:shadow-none">
                <a href="{{ route('home') }}"
                    class="text-center {{ request()->routeIs('home') ? 'text-blue-600 font-bold' : 'text-gray-700 hover:text-blue-600' }}">
                    <span class="hidden sm:inline">Laporan </span>Buku Besar
                </a>
            </li>
            <li
                class="bg-slate-100 p-2 sm:p-0 rounded-md shadow-md flex justify-center items-center sm:justify-left sm:bg-transparent sm:rounded-none sm:shadow-none">
                <a href="{{ route('neraca') }}"
                    class="text-center {{ request()->routeIs('neraca') ? 'text-blue-600 font-bold' : 'text-gray-700 hover:text-blue-600' }}">
                    <span class="hidden sm:inline">Laporan </span>Neraca
                </a>
            </li>
            <li
                class="bg-slate-100 p-2 sm:p-0 rounded-md shadow-md flex justify-center items-center sm:justify-left sm:bg-transparent sm:rounded-none sm:shadow-none">
                <a href="{{ route('laba-rugi') }}"
                    class="text-center {{ request()->routeIs('laba-rugi') ? 'text-blue-600 font-bold' : 'text-gray-700 hover:text-blue-600' }}">
                    <span class="hidden sm:inline">Laporan </span>Laba Rugi
                </a>
            </li>
            <li
                class="bg-slate-100 p-2 sm:p-0 rounded-md shadow-md flex justify-center items-center sm:justify-left sm:bg-transparent sm:rounded-none sm:shadow-none">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-center text-gray-700 hover:text-blue-600">
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>
