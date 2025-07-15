<div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50/30 to-indigo-50/50">
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="max-w-7xl mx-auto px-4 py-16">
            <!-- Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg mb-6">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h2 class="text-5xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-4">
                    Our Members
                </h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Connect with professionals in your network and build meaningful relationships
                </p>
            </div>

            <!-- Search Bar -->
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input 
                        type="text" 
                        wire:model.live="search"
                        class="block w-full pl-12 pr-12 py-4 border border-gray-200 rounded-2xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white/80 backdrop-blur-sm shadow-lg hover:shadow-xl transition-all duration-300 placeholder-gray-500 text-gray-900 text-lg"
                        placeholder="Search members by name or email..."
                        autocomplete="off"
                    >
                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                        <div wire:loading wire:target="search" class="animate-spin">
                            <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Grid -->
            @if($users->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach ($users as $user)
                        <div class="group bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100/50">
                            <!-- Avatar Section -->
                            <div class="p-8 pb-0 flex justify-center relative">
                                <div class="absolute inset-0 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-t-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                <div class="relative z-10">
                                    <div class="absolute inset-0 rounded-full bg-gradient-to-br from-blue-200 to-indigo-200 blur-lg opacity-30 group-hover:opacity-50 transition-opacity duration-300"></div>
                                    @if($user->avatar)
                                        <x-avatar alt="{{$user->name}}"
                                            class="relative z-10 w-28 h-28 rounded-full object-cover border-4 border-white shadow-xl group-hover:shadow-2xl transition-shadow duration-300"
                                            src="{{ asset('storage/' . $user->avatar) }}" />
                                    @else
                                        <div class="relative z-10 w-28 h-28 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 border-4 border-white shadow-xl group-hover:shadow-2xl transition-shadow duration-300 flex items-center justify-center">
                                            <span class="text-white text-2xl font-bold">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- User Info -->
                            <div class="p-8 pt-6 text-center">
                                <h3 class="text-xl font-bold text-gray-800 mb-2 group-hover:text-blue-700 transition-colors duration-300 truncate px-2" title="{{$user->name}}">
                                    {{$user->name}}
                                </h3>
                                <p class="text-sm text-gray-500 mb-6 px-2 truncate" title="{{$user->email}}">
                                    {{$user->email}}
                                </p>

                                <!-- Button -->
                                <button wire:click="message({{$user->id}})"
                                    class="w-full px-6 py-3 text-sm font-semibold rounded-xl bg-gradient-to-r from-blue-500 to-indigo-600 text-white hover:from-blue-600 hover:to-indigo-700 shadow-lg hover:shadow-xl transition-all duration-300 transform focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    <span class="flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <span>Message</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-20">
                    <div class="max-w-md mx-auto">
                        <div class="bg-gradient-to-br from-blue-100 to-indigo-100 rounded-3xl w-32 h-32 flex items-center justify-center mx-auto mb-8 shadow-lg">
                            <svg class="w-16 h-16 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-800 mb-4">
                            No Members Found
                        </h3>
                        <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                            There are currently no members available or no members match your search criteria. Try adjusting your search terms.
                        </p>
                        <div class="w-24 h-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full mx-auto"></div>
                    </div>
                </div>
            @endif
            
            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-gray-200/50 p-2">
                    {{ $users->links('pagination::tailwind') }}
                </div>
            </div>
        </div>

        <script>
            window.onload = () => {
                window.scrollTo({ top: 0, behavior: 'smooth' });
            };

            document.addEventListener('click', e => {
                const el = e.target.closest('a[href*="page="], button[wire\\:click*="gotoPage"]');
                if (el) {
                    setTimeout(() => {
                        window.scrollTo({ top: 130, behavior: 'smooth' });
                    }, 200);
                }
            });
        </script>
    </div>

</div>