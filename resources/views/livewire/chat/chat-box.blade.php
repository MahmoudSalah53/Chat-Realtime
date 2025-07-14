<div x-data="{
    height:0,
    conversationElement:document.getElementById('conversation'),
    markAsRead:null
}" x-init="
        height= conversationElement.scrollHeight;
        $nextTick(()=>conversationElement.scrollTop= height);" @scroll-bottom.window="
 $nextTick(()=>
 conversationElement.scrollTop= conversationElement.scrollHeight
 );

 Echo.private('users.{{Auth()->User()->id}}')
    .notification((notification)=>{
        if(notification['type']== 'App\\Notifications\\MessageRead' && notification['conversation_id']== {{$this->selectedConversation->id}})
        {
            Livewire.dispatch('refresh');
            
        }
    });
 " class="w-full overflow-hidden bg-gradient-to-b from-gray-50 to-white">

    <div class="flex flex-col overflow-y-auto grow h-full">
        {{-- header --}}

        <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 bg-white border-b shadow-sm">

            <div class="flex w-full items-center px-4 lg:px-6 gap-3 md:gap-5 py-2">

                <a href="#" class="shrink-0 lg:hidden text-gray-600 hover:text-gray-800 transition-colors">

                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 12h-15m0 0l6.75 6.75M4.5 12l6.75-6.75" />
                    </svg>

                </a>

                {{-- avatar --}}

                <div class="shrink-0">
                    @if($selectedConversation->getReceiver()->avatar)
                        <x-avatar class="h-12 w-12 lg:w-14 lg:h-14 border-2 border-white shadow-md"
                            src="{{ asset('storage/' . $selectedConversation->getReceiver()->avatar) }}" />
                    @else
                        <x-avatar class="h-12 w-12 lg:w-14 lg:h-14 border-2 border-white shadow-md bg-gradient-to-br from-blue-400 to-purple-500" />
                    @endif
                </div>

                <div class="flex flex-col">
                    <h6 class="font-bold text-lg"> {{ $selectedConversation->getReceiver()->name }} </h6>
                    <p class="font-light text-sm text-gray-500 truncate"> {{ $selectedConversation->getReceiver()->email }} </p>
                </div>

            </div>

        </header>

        {{-- body --}}

        <main x-data="{ oldHeight: 0 }" @store-scroll-height.window="oldHeight = $el.scrollHeight"
            @update-chat-height.window="
        $nextTick(() => {
            let newHeight = $el.scrollHeight;
            $el.scrollTop = newHeight - oldHeight;
            oldHeight = newHeight;
        });
    " @scroll.debounce.50ms="if ($el.scrollTop <= 0) $wire.loadMore();" x-init="oldHeight = $el.scrollHeight"
            id="conversation"
            class="flex flex-col gap-4 p-4 overflow-y-auto flex-grow overscroll-contain overflow-x-hidden w-full my-auto bg-gradient-to-b from-white to-gray-50">

            @if ($loadedMessages)

                @foreach ($loadedMessages as $key => $message)

                    @php

                        $previousMessage = $key > 0 ? $loadedMessages->get($key - 1) : null;
                        

                        $nextMessage = $loadedMessages->get($key + 1);
                        
                        $showAvatar = $message->sender_id !== auth()->id() && 
                                     ($previousMessage === null || $previousMessage->sender_id !== $message->sender_id);
                    @endphp

                    <div wire:key="{{time() . $key}}" @class(['max-w-[85%] md:max-w-[78%] flex w-auto gap-3 relative mt-2', 'ml-auto' => $message->sender_id === auth()->id()])>

                        {{-- avatar --}}
                        <div @class([
                            'shrink-0',
                            'invisible' => !$showAvatar && $message->sender_id !== auth()->id(),
                            'hidden' => $message->sender_id === auth()->id()
                        ])>

                            @if($selectedConversation->getReceiver()->avatar)
                                <x-avatar class="h-10 w-10 lg:w-12 lg:h-12 border-2 border-white shadow-sm"
                                    src="{{ asset('storage/' . $selectedConversation->getReceiver()->avatar) }}" />
                            @else
                                <x-avatar class="h-10 w-10 lg:w-12 lg:h-12 border-2 border-white shadow-sm bg-gradient-to-br from-blue-400 to-purple-500" />
                            @endif

                        </div>

                        {{-- message body --}}
                        <div @class([
                            'flex flex-wrap text-[15px] rounded-xl p-3 flex flex-col shadow-sm transition-all duration-200',
                            'rounded-bl-none bg-gray-100 text-gray-800 border border-gray-200/40 hover:bg-gray-200/80' => !($message->sender_id === auth()->id()),
                            'rounded-br-none bg-gradient-to-r from-blue-500 to-blue-600 text-white hover:from-blue-600 hover:to-blue-700' => $message->sender_id === auth()->id()
                        ])>

                            @if($message->hasImage())
                                <div class="mb-2 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-all duration-200">
                                    <img src="{{ asset('storage/' . $message->image) }}" 
                                         alt="Image" 
                                         class="max-w-64 max-h-64 object-cover cursor-pointer hover:scale-[1.02] transition-transform duration-200"
                                         onclick="this.classList.toggle('max-h-64'); this.classList.toggle('max-h-full');">
                                </div>
                            @endif

                            @if($message->body)
                                <p class="whitespace-normal text-sm md:text-base tracking-wide lg:tracking-normal">
                                    {{ $message->body }}
                                </p>
                            @endif

                            <div class="ml-auto flex gap-2 items-center mt-1">

                                <p @class([
                                    'text-xs',
                                    'text-gray-500' => !($message->sender_id === auth()->id()),
                                    'text-gray-200' => $message->sender_id === auth()->id(),
                                ])>
                                    {{ $message->created_at->format('g:i a') }}
                                </p>

                                {{-- message status , only show if message belongs auth --}}
                                @if ($message->sender_id === auth()->id())

                                    <div x-data="{markAsRead:@json($message->isRead())}">

                                        <span x-cloak x-show="markAsRead" @class('text-gray-200')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                class="bi bi-check2-all" viewBox="0 0 16 16">
                                                <path
                                                    d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0l7-7zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0z" />
                                                <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708z" />
                                            </svg>
                                        </span>

                                        <span x-show="!markAsRead" @class('text-gray-200')>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                                class="bi bi-check2" viewBox="0 0 16 16">
                                                <path
                                                    d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z" />
                                            </svg>
                                        </span>

                                    </div>
                                @endif

                            </div>

                        </div>

                    </div>
                @endforeach
            @endif

        </main>

        {{-- Modern Enhanced Footer --}}
        <footer class="shrink-0 z-10 bg-white border-t border-gray-200 shadow-sm">
            <div class="p-4 space-y-3">
                
                <!-- Image Preview Section -->
                @if($image)
                    <div class="bg-white rounded-xl p-3 border border-gray-200 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview" 
                                         class="w-16 h-16 object-cover rounded-xl border-2 border-gray-100 shadow-sm">
                                    <div class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-white" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <span class="text-sm font-medium text-gray-700 block truncate">{{ $image->getClientOriginalName() }}</span>
                                    <span class="text-xs text-gray-500">Ready to send</span>
                                </div>
                            </div>
                            <button type="button" wire:click="$set('image', null)" 
                                    class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Message Input Section -->
                <form x-data="{ body: @entangle('body').defer || '' }" 
                      @submit.prevent="$wire.set('body', body).then(() => { $wire.sendMessage(); body = ''; })" 
                      method="POST" autocapitalize="off" class="relative">
                    @csrf
                    <input type="hidden" autocomplete="false" style="display: none;">
                    
                    <div class="relative flex items-center bg-white rounded-2xl border border-gray-200 shadow-md hover:shadow-lg transition-all duration-300 overflow-hidden">
                        
                        <!-- Message Input -->
                        <div class="flex-1 relative">
                            <input x-model="body" 
                                   type="text" 
                                   autocomplete="off" 
                                   autofocus
                                   placeholder="Type your message..." 
                                   maxlength="1700"
                                   class="w-full px-5 py-4 bg-transparent border-0 outline-none focus:ring-0 text-gray-700 placeholder-gray-400 text-sm">
                            
                            <!-- Input bottom border animation -->
                            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gradient-to-r from-blue-500 to-purple-500 transform scale-x-0 transition-transform duration-300 group-focus-within:scale-x-100"></div>
                        </div>

                        <!-- Action Buttons Container -->
                        <div class="flex items-center gap-2 px-3">
                            
                            <!-- Image Upload Button -->
                            <label for="image-upload" class="group relative cursor-pointer">
                                <div class="flex items-center justify-center w-11 h-11 rounded-xl bg-gray-50 hover:bg-blue-50 transition-all duration-200 group-hover:scale-105">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 group-hover:text-blue-600 transition-colors" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <!-- Tooltip -->
                                <div class="absolute -top-10 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap">
                                    Add Image
                                </div>
                            </label>
                            <input id="image-upload" type="file" wire:model="image" accept="image/*" class="hidden">

                            <!-- Send Button -->
                            <button x-bind:disabled="!body?.trim() && !$wire.image" 
                                    class="relative group flex items-center justify-center w-12 h-11 rounded-xl bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 disabled:from-gray-300 disabled:to-gray-400 disabled:cursor-not-allowed transition-all duration-200 transform shadow-lg hover:shadow-xl" 
                                    type="submit">
                                
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white transform transition-transform duration-200 group-hover:translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                
                                <!-- Button glow effect -->
                                <div class="absolute inset-0 rounded-xl bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-200"></div>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Error Messages -->
                <div class="space-y-1">
                    @error('body')
                        <div class="flex items-center gap-2 text-red-500 text-sm bg-red-50 px-3 py-2 rounded-lg border border-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror

                    @error('image')
                        <div class="flex items-center gap-2 text-red-500 text-sm bg-red-50 px-3 py-2 rounded-lg border border-red-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </footer>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('messageStatus', {
                readMessages: [],
                markAsRead(id) {
                    if (!this.readMessages.includes(id)) {
                        this.readMessages.push(id);
                    }
                },
                isRead(id) {
                    return this.readMessages.includes(id);
                }
            });
        });
    </script>
</div>