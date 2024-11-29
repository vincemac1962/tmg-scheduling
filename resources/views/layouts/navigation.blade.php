<!--suppress JSDeprecatedSymbols -->
<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>
                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex items-center">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <!-- Sites Dropdown -->
                    <x-dropdown align="right" width="48" :active="request()->routeIs('sites.*')">
                        <x-slot name="trigger">
                            <span class="py-1 bg-white dark:bg-gray-700 text-sm leading-5">{{ __('Sites') }}</span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('sites.index')">
                                {{ __('View Sites') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('sites.create')">
                                {{ __('Create Site') }}
                            </x-dropdown-link>
                            <!-- Add more links for other routes here -->
                        </x-slot>
                    </x-dropdown>
                    <!-- Schedules Dropdown -->
                    <x-dropdown align="right" width="48" :active="request()->routeIs('schedules.*')">
                        <x-slot name="trigger">
                            <span class="py-1 bg-white dark:bg-gray-700 text-sm leading-5">{{ __('Schedules') }}</span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('schedules.index')">
                                {{ __('View Schedules') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('schedules.create')">
                                {{ __('Create Schedule') }}
                            </x-dropdown-link>
                            <!-- Add more links for other routes here -->
                        </x-slot>
                    </x-dropdown>
                    <x-dropdown align="right" width="48" :active="request()->routeIs('items.*')">
                        <x-slot name="trigger">
                            <span class="py-1 bg-white dark:bg-gray-700 text-sm leading-5">{{ __('Schedule Items') }}</span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('schedule_items.index')">
                                {{ __('View All Schedule Items') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                    <!-- Advertisers dropdown -->
                    <x-dropdown align="right" width="48" :active="request()->routeIs('items.*')">
                        <x-slot name="trigger">
                            <span class="py-1 bg-white dark:bg-gray-700 text-sm leading-5">{{ __('Advertisers') }}</span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('advertisers.index')">
                                {{ __('View All Advertisers') }}
                            </x-dropdown-link>
                            <x-dropdown-link :href="route('advertisers.createNoScheduleId')">
                                {{ __('Create Advertiser') }}
                            </x-dropdown-link>
                            <!-- Add more links for other routes here -->
                        </x-slot>
                    </x-dropdown>
                    <x-dropdown align="right" width="48" :active="request()->routeIs('items.*')">
                        <x-slot name="trigger">
                            <span class="py-1 bg-white dark:bg-gray-700 text-sm leading-5">{{ __('Users') }}</span>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link :href="route('users.index')">
                                {{ __('Manage Users') }}
                            </x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                            @if(Auth::check())
                                <div>{{ Auth::user()->name }}</div>
                            @else
                                <div>Guest</div>
                            @endif
                            <div class="ml-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                             onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <!-- Your hamburger menu here -->
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <!-- Your responsive navigation menu here -->
</nav>