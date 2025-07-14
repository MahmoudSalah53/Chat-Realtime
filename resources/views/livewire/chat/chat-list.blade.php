<div x-data="{type: 'all', query:@entangle('query'), searchQuery: ''}" x-init="
        setTimeout(()=>{

        conversationElement = document.getElementById('conversation-'+query);

        //scroll to the element

        if(conversationElement)
        {

            conversationElement.scrollIntoView({'behavior':'smooth'});

        }

        },200);

        document.addEventListener('livewire:load', () => {
        Echo.private('users.{{ auth()->id() }}')
            .notification((notification) => {
                if (notification.type === 'App\\Notifications\\MessageRead' || notification.type === 'App\\Notifications\\MessageSent') {
                    Livewire.dispatch('refresh');
                }
            });
    });
        " class="flex flex-col transition-all h-full overflow-hidden w-full bg-gradient-to-b from-gray-50 to-white">
    <header class="px-4 z-10 bg-white sticky top-0 w-full py-3 shadow-sm border-b border-gray-100">

        <div class="justify-between flex items-center pb-2">

            <div class="flex items-center gap-2">
                <h5 class="text-2xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Chats</h5>
            </div>

        </div>

        <!-- Enhanced Search Bar -->
        <div class="mt-3 relative">
            <div class="relative">
                <input type="text" x-model="searchQuery" placeholder="Search conversations..." wire:model.live="search"
                    class="w-full px-5 py-3 pr-12 text-sm bg-gray-50 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 shadow-sm hover:shadow-md">
                <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none">
                    <svg class="w-5 h-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <button x-show="searchQuery.length > 0" x-transition @click="searchQuery = ''"
                    class="absolute inset-y-0 left-0 flex items-center pl-4">
                    <svg class="w-4 h-4 text-gray-400 hover:text-gray-600 transition-colors"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

    </header>

    <main class="overflow-y-auto overflow-hidden grow h-full relative px-2" style="contain:content">

        {{-- chatlist --}}

        <ul class="py-3 grid w-full space-y-3">

            @if($conversations)

                @foreach ($conversations as $key => $conversation)

                    <li id="conversation-{{ $conversation->id }}" wire:key="{{ $conversation->id }}"
                        class="py-3 hover:bg-blue-50 rounded-2xl transition-all duration-200 flex gap-4 relative w-full cursor-pointer px-3 {{ $conversation->id == $selectedConversation?->id ? 'bg-blue-100/80 shadow-md' : 'hover:shadow-sm' }}">
                        <a href="{{ route('chat', $conversation->id) }}" wire:navigate class="shrink-0">
                            @if($conversation->getReceiver()->avatar)
                                <x-avatar class="h-14 w-14 border-2 border-white shadow-md" src="{{ asset('storage/' . $conversation->getReceiver()->avatar) }}" />
                            @else
                                <x-avatar class="h-14 w-14 border-2 border-white shadow-md bg-gradient-to-br from-blue-400 to-purple-500" />
                            @endif
                        </a>

                        <aside class="grid grid-cols-12 w-full">
                            <a href="{{ route('chat', $conversation->id) }}" wire:navigate
                                class="col-span-11 pb-2 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1">

                                {{-- name and date --}}
                                <div class="flex justify-between w-full items-center mb-1">

                                    <h6 class="truncate font-semibold tracking-wider text-gray-900 text-lg">
                                        {{ $conversation->getReceiver()->name }}
                                    </h6>

                                    <small
                                        class="text-gray-500 font-medium">{{ $conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans() }}</small>

                                </div>

                                {{-- Message body --}}

                                <div class="flex gap-x-2 items-center w-full">

                                    @if ($conversation->messages?->last()?->sender_id == auth()->id())


                                        @if ($conversation->isReadMessage())
                                            {{-- double tick --}}
                                            <span class="text-blue-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-check2-all" viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
                                                    <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
                                                </svg>
                                            </span>
                                        @else
                                            {{-- single tick --}}
                                            <span class="text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                    class="bi bi-check2" viewBox="0 0 16 16">
                                                    <path
                                                        d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                                </svg>
                                            </span>

                                        @endif
                                    @endif

                                    <p class="grow truncate text-sm text-gray-500">
                                        @if($conversation->messages?->last()?->image)
                                            <span class="text-blue-400 italic flex items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                                </svg>
                                                Image sent
                                            </span>
                                        @endif
                                        {{ $conversation->messages?->last()?->body ?? ' ' }}
                                    </p>


                                    {{-- unread count --}}
                                    @if ($conversation->unreadMessagesCount() > 0)
                                        <span class="font-bold p-1 px-2.5 text-xs shrink-0 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-sm">
                                            {{ $conversation->unreadMessagesCount() }}
                                        </span>
                                    @endif
                                </div>

                            </a>

                            <div class="col-span-1 flex flex-col text-center my-auto">

                                <x-dropdown align="right" width="48">
                                    <x-slot name="trigger">
                                        <button class="text-gray-400 hover:text-gray-600 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                class="bi bi-three-dots-vertical w-5 h-7" viewBox="0 0 16 16">
                                                <path
                                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                            </svg>

                                        </button>
                                    </x-slot>

                                    <x-slot name="content">

                                        <div class="w-full p-1">
                                            <button onclick="confirm('Are you sure?')||event.stopImmediatePropagation()"
                                                wire:click="deleteByUser('{{ encrypt($conversation->id) }}')" 
                                                class="items-center gap-3 flex w-full px-4 py-2 text-left text-sm leading-5 text-gray-500 hover:bg-red-50 hover:text-red-500 transition-all duration-150 ease-in-out focus:outline-none rounded-lg">

                                                <span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                                        <path
                                                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z" />
                                                    </svg>
                                                </span>

                                                Delete
                                            </button>

                                        </div>

                                    </x-slot>
                                </x-dropdown>

                            </div>
                        </aside>
                    </li>
                @endforeach

            @else

            @endif
        </ul>

    </main>
</div>