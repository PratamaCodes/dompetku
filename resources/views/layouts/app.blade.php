<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $title ?? config('app.name') }}</title>

    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.9.0/fonts/remixicon.css" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>
    <div class="drawer">
        <input id="my-drawer-2" type="checkbox" class="drawer-toggle lg:hidden" />
        <div class="drawer-content flex flex-col min-h-screen">
            <!-- Navbar -->
            <div class="navbar bg-base-300 w-full">
                <div class="flex-none lg:hidden">
                    <label for="my-drawer-2" aria-label="open sidebar" class="btn btn-square btn-ghost drawer-button">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            class="inline-block h-6 w-6 stroke-current">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </label>
                </div>
                <div class="mx-2 flex-1 px-2">
                    <i class="ri-wallet-fill"></i>
                    <span class="font-bold">Dompetku</span>
                </div>
                <div class="hidden flex-none lg:block">
                    <ul class="menu menu-horizontal">
                        <!-- Navbar menu content here -->
                        <li><a wire:navigate wire:current='font-semibold' href="/kategori">Kategori</a></li>
                        <li><a wire:navigate wire:current='font-semibold' href="/transaksi">Transaksi</a></li>
                    </ul>
                </div>
            </div>
            <!-- Page content here -->
            <div class="flex-1 px-5">
                {{ $slot }}
            </div>

            <div class="flex">
                <footer class="footer sm:footer-horizontal footer-center bg-base-300 text-base-content p-4">
                    <aside>
                        <p>Copyright © 2026 - All right reserved by Dompetku Ltd</p>
                    </aside>
                </footer>
            </div>
        </div>
        <div class="drawer-side">
            <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label>
            <ul class="menu bg-base-200 min-h-full w-80 p-4">
                <!-- Sidebar content here -->
                <li><a>Kategori</a></li>
                <li><a>Transaksi</a></li>
            </ul>
        </div>
    </div>

    @livewireScripts
</body>

</html>