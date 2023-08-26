@include('layouts.header')

<body>

    <div x-data="{ isSideMenuOpen: false }">
        <div x-show="isSideMenuOpen" x-transition:enter="transition ease-in-out duration-150"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in-out duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 z-10 flex items-end sm:items-center sm:justify-center"
            @click="isSideMenuOpen = false" x-cloak></div>

        <div class="flex h-screen bg-gray-50 " :class="{ 'overflow-hidden': isSideMenuOpen }">
            <!-- Desktop sidebar -->

            @include('layouts.dashboard.sidebar')
            {{-- End of sidebar --}}
            <div class="flex flex-col flex-1 w-full">
                <header class="z-10 py-4 bg-white shadow-md dark:bg-gray-700">
                    <div
                        class="container flex items-center justify-between h-full px-6 mx-auto text-green-600 dark:text-green-300">
                        <!-- Mobile hamburger -->


                        <button @click="isSideMenuOpen = !isSideMenuOpen"
                            class="p-1 mr-5 -ml-1 rounded-md md:hidden focus:outline-none focus:shadow-outline-purple"
                            x-cloak>
                            <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>


                        <div class="flex justify-center flex-1 lg:mr-32">
                            <div class="relative w-full max-w-xl mr-6 focus-within:text-purple-500">
                                {{-- Data Here --}}
                            </div>
                        </div>
                        <ul class="flex items-center flex-shrink-0 space-x-6">
                            <!-- Theme toggler -->
                            <li class="flex">
                                <button class="rounded-md focus:outline-none focus:shadow-outline-purple"
                                    @click="toggleTheme" aria-label="Toggle color mode">
                                    <template x-if="!dark">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z">
                                            </path>
                                        </svg>
                                    </template>
                                    <template x-if="dark">
                                        <svg class="w-5 h-5" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </template>
                                </button>
                            </li>


                           <livewire:components.notification/>



                            @can('admin-access')
                                <li class="flex">



                                    <div x-data="{
                                        open: false,
                                        toggle() {
                                            if (this.open) {
                                                return this.close()
                                            }

                                            this.$refs.button.focus()

                                            this.open = true
                                        },
                                        close(focusAfter) {
                                            if (!this.open) return

                                            this.open = false

                                            focusAfter && focusAfter.focus()
                                        }
                                    }" x-on:keydown.escape.prevent.stop="close($refs.button)"
                                        x-on:focusin.window="! $refs.panel.contains($event.target) && close()"
                                        x-id="['dropdown-button']" class="relative">
                                        <button x-ref="button" x-on:click="toggle()" :aria-expanded="open"
                                            :aria-controls="$id('dropdown-button')" type="button"
                                            class="relative align-middle">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0M3.124 7.5A8.969 8.969 0 015.292 3m13.416 0a8.969 8.969 0 012.168 4.5" />
                                            </svg>

                                            <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                                        </button>
                                        <div x-ref="panel" x-show="open" x-transition.origin.top.left
                                            x-on:click.outside="close($refs.button)" :id="$id('dropdown-button')"
                                            style="display: none;"
                                            class="absolute right-0  w-64  rounded-md bg-white shadow-md">
                                            <ul class="p-3 space-y-1 text-sm text-gray-700 dark:text-gray-200"
                                                aria-labelledby="dropdownHelperButton">
                                                <li>


                                                    <div class="flex p-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">

                                                        <div class="ml-2 text-sm">
                                                            <x-label>

                                                                <div>Enable notifications</div>
                                                                <p id="helper-checkbox-text-1"
                                                                    class="text-xs font-normal text-gray-500 dark:text-gray-300">
                                                                    Some helpful instruction goes over here.</p>
                                                            </x-label>
                                                        </div>
                                                    </div>


                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </li>
                            @endcan

                            <li x-data="{ isProfileMenuOpen: false }" @keydown.escape="isProfileMenuOpen = false">
                                <button class="flex items-center justify-center rounded-full focus:outline-none"
                                    @click="isProfileMenuOpen = !isProfileMenuOpen" aria-label="Account"
                                    aria-haspopup="true">
                                    <span class="mr-1">{{ Auth()->user()?->name }}</span>
                                    <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                                        aria-hidden="true">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>

                                <ul x-show="isProfileMenuOpen" x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    @click.away="isProfileMenuOpen = false"
                                    @keydown.escape="isProfileMenuOpen = false"
                                    class="absolute right-0 w-56 p-2 mt-2 space-y-2 text-gray-600 bg-white border border-gray-100 rounded-md shadow-md dark:border-gray-700 dark:text-gray-300 dark:bg-gray-700"
                                    aria-label="submenu">
                                    <li class="flex">
                                        <a href="#"
                                            class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                                            <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                            <span>Profile</span>
                                        </a>
                                    </li>
                                    <li class="flex">
                                        <a href="#"
                                            class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                                            <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 01-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 01-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 01-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 01-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 011.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                                </path>
                                                <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            <span>Settings</span>
                                        </a>
                                    </li>
                                    <li class="flex">
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="inline-flex items-center w-full px-2 py-1 text-sm font-semibold transition-colors duration-150 rounded-md hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-800 dark:hover:text-gray-200">
                                                <svg class="w-4 h-4 mr-3" aria-hidden="true" fill="none"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path
                                                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                                    </path>
                                                </svg>
                                                <span>{{ __('Log out') }}</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>



                        </ul>



                    </div>
                </header>
                <main class="h-full overflow-y-auto px-8 ">
                    <div class="mx-auto my-6">
                        @yield('content')
                        @include('components.footer')
                    </div>
                </main>
            </div>
        </div>



        @yield('script')
        @stack('modals')
        @stack('scripts')

        <script src="{{ asset('assets/js/charts-lines.js') }}" defer></script>
        <script src="{{ asset('assets/js/charts-pie.js') }}" defer></script>
        <script src="{{ asset('assets/js/charts-bars.js') }}" defer></script>
        <script src="{{ asset('assets/js/focus-trap.js') }}" defer></script>
        <script src="{{ asset('assets/js/cards.js') }}" defer></script>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>





        @livewireScripts
</body>

</html>
