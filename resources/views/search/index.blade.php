<x-layouts.app :title="__('Search Results')">
    <div class="max-w-4xl mx-auto">
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ __('Search') }}</h1>
        </div>

        <!-- Search Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
            <form method="GET" action="{{ route('search.index') }}">
                <div class="flex gap-2">
                    <div class="flex-1">
                        <input
                            type="text"
                            name="q"
                            placeholder="{{ __('Enter search term...') }}"
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ request()->get('q') }}"
                        >
                    </div>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors duration-200 flex items-center gap-2"
                    >
                        <flux:icon name="magnifying-glass" class="h-5 w-5" />
                        {{ __('Search') }}
                    </button>
                </div>
            </form>
        </div>

        @if(request()->has('q'))
            <div class="mb-4">
                <p class="text-gray-600 dark:text-gray-400">
                    {{ __('Results for: :query', ['query' => request()->get('q')]) }}
                </p>
            </div>

            @php
                $hasResults = false;
            @endphp

            @if(isset($results) && is_array($results))
                @foreach($results as $type => $items)
                    @if(!empty($items) && $items->count() > 0)
                        @php
                            $hasResults = true;
                        @endphp
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 capitalize">
                                {{ __($type) }}
                            </h2>
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($items as $item)
                                        <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700">
                                            @php
                                                $url = '#';
                                                if($type === 'buildings') {
                                                    $url = route('location') . '?building=' . $item->id;
                                                } elseif($type === 'rooms') {
                                                    $url = route('location') . '?building=' . $item->building_id . '&room=' . $item->id;
                                                } elseif($type === 'cctvs') {
                                                    $url = route('cctv.stream', $item->id);
                                                } elseif($type === 'users') {
                                                    // For users, we might want to go to a user profile page if it exists
                                                    // For now, we'll just use the dashboard
                                                    $url = route('dashboard');
                                                } elseif($type === 'contacts') {
                                                    $url = route('contact');
                                                } elseif($type === 'maintenances') {
                                                    // For maintenances, we might want to go to a maintenance page if it exists
                                                    // For now, we'll just use the dashboard
                                                    $url = route('dashboard');
                                                } elseif($type === 'alerts') {
                                                    $url = route('notifications');
                                                }
                                            @endphp
                                            <a href="{{ $url }}" class="block">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0">
                                                        @if($type === 'buildings')
                                                            <flux:icon name="home" class="h-5 w-5 text-gray-500" />
                                                        @elseif($type === 'rooms')
                                                            <flux:icon name="door-open" class="h-5 w-5 text-gray-500" />
                                                        @elseif($type === 'cctvs')
                                                            <flux:icon name="camera" class="h-5 w-5 text-gray-500" />
                                                        @elseif($type === 'users')
                                                            <flux:icon name="user" class="h-5 w-5 text-gray-500" />
                                                        @elseif($type === 'contacts')
                                                            <flux:icon name="envelope" class="h-5 w-5 text-gray-500" />
                                                        @elseif($type === 'maintenances')
                                                            <flux:icon name="wrench" class="h-5 w-5 text-gray-500" />
                                                        @elseif($type === 'alerts')
                                                            <flux:icon name="bell" class="h-5 w-5 text-gray-500" />
                                                        @else
                                                            <flux:icon name="document-text" class="h-5 w-5 text-gray-500" />
                                                        @endif
                                                    </div>
                                                    <div class="ml-4">
                                                        <h3 class="text-sm font-medium text-gray-900 dark:text-white">
                                                            {{ $item->name ?? $item->title ?? 'Untitled' }}
                                                        </h3>
                                                        @if(isset($item->description) || isset($item->email) || isset($item->address))
                                                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                                {{ $item->description ?? $item->email ?? $item->address ?? '' }}
                                                            </p>
                                                        @endif
                                                        @if(isset($item->building))
                                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                                {{ $item->building->name ?? '' }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                </div>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif
                @endforeach
            @endif

            @if(!$hasResults)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                    <flux:icon name="magnifying-glass" class="h-12 w-12 text-gray-400 mx-auto" />
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('No results found') }}</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        {{ __('Try different keywords or remove search filters.') }}
                    </p>
                </div>
            @endif
        @else
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 text-center">
                <flux:icon name="magnifying-glass" class="h-12 w-12 text-gray-400 mx-auto" />
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">{{ __('Search') }}</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">
                    {{ __('Enter a search term to find content.') }}
                </p>
            </div>
        @endif
    </div>
    
    <!-- Mobile Search Responsive -->
    <style>
        @media (max-width: 768px) {
            .search-container {
                max-width: 100% !important;
                padding: 1rem !important;
            }
            .search-header {
                margin-bottom: 1.5rem !important;
            }
            .search-header h1 {
                font-size: 1.5rem !important;
            }
            .search-form {
                padding: 1rem !important;
                margin-bottom: 1.5rem !important;
            }
            .search-form .flex {
                flex-direction: column !important;
                gap: 0.75rem !important;
            }
            .search-form input {
                padding: 0.75rem !important;
                font-size: 0.875rem !important;
            }
            .search-form button {
                padding: 0.75rem !important;
                font-size: 0.875rem !important;
                justify-content: center !important;
            }
            .search-form button svg {
                width: 1rem !important;
                height: 1rem !important;
            }
            .search-results {
                margin-bottom: 1.5rem !important;
            }
            .search-results h2 {
                font-size: 1rem !important;
                margin-bottom: 0.75rem !important;
            }
            .search-results li {
                padding: 0.75rem !important;
            }
            .search-results .flex-shrink-0 svg {
                width: 1rem !important;
                height: 1rem !important;
            }
            .search-results h3 {
                font-size: 0.875rem !important;
            }
            .search-results p {
                font-size: 0.75rem !important;
            }
            .search-results .text-xs {
                font-size: 0.625rem !important;
            }
            .search-no-results {
                padding: 1.5rem !important;
            }
            .search-no-results svg {
                width: 3rem !important;
                height: 3rem !important;
            }
            .search-no-results h3 {
                font-size: 1rem !important;
            }
            .search-no-results p {
                font-size: 0.875rem !important;
            }
        }
        @media (max-width: 480px) {
            .search-container {
                padding: 0.75rem !important;
            }
            .search-header {
                margin-bottom: 1rem !important;
            }
            .search-header h1 {
                font-size: 1.25rem !important;
            }
            .search-form {
                padding: 0.75rem !important;
                margin-bottom: 1rem !important;
            }
            .search-form .flex {
                gap: 0.5rem !important;
            }
            .search-form input {
                padding: 0.5rem !important;
                font-size: 0.75rem !important;
            }
            .search-form button {
                padding: 0.5rem !important;
                font-size: 0.75rem !important;
            }
            .search-results {
                margin-bottom: 1rem !important;
            }
            .search-results h2 {
                font-size: 0.875rem !important;
                margin-bottom: 0.5rem !important;
            }
            .search-results li {
                padding: 0.5rem !important;
            }
            .search-results .flex-shrink-0 svg {
                width: 0.875rem !important;
                height: 0.875rem !important;
            }
            .search-results h3 {
                font-size: 0.75rem !important;
            }
            .search-results p {
                font-size: 0.625rem !important;
            }
            .search-results .text-xs {
                font-size: 0.5rem !important;
            }
            .search-no-results {
                padding: 1rem !important;
            }
            .search-no-results svg {
                width: 2.5rem !important;
                height: 2.5rem !important;
            }
            .search-no-results h3 {
                font-size: 0.875rem !important;
            }
            .search-no-results p {
                font-size: 0.75rem !important;
            }
        }
    </style>
</x-layouts.app>
