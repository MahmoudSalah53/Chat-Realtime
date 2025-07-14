<div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
    <style>
        .sound-indicator {
            display: flex;
            align-items: center;
            gap: 2px;
            height: 20px;
        }

        .sound-bar {
            width: 3px;
            background: #3b82f6;
            border-radius: 2px;
            animation: soundBars 0.8s ease-in-out infinite;
        }

        .sound-bar:nth-child(1) {
            height: 10px;
            animation-delay: 0s;
        }

        .sound-bar:nth-child(2) {
            height: 15px;
            animation-delay: 0.1s;
        }

        .sound-bar:nth-child(3) {
            height: 20px;
            animation-delay: 0.2s;
        }

        .sound-bar:nth-child(4) {
            height: 15px;
            animation-delay: 0.3s;
        }

        .sound-bar:nth-child(5) {
            height: 10px;
            animation-delay: 0.4s;
        }

        @keyframes soundBars {

            0%,
            100% {
                transform: scaleY(1);
            }

            50% {
                transform: scaleY(0.3);
            }
        }

        .playing .sound-bar {
            animation-play-state: running;
        }

        .not-playing .sound-bar {
            animation-play-state: paused;
            transform: scaleY(0.3);
        }
    </style>
    <!-- Profile Photo Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <header class="mb-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Profile Photo') }}
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Update your profile photo.') }}
            </p>
        </header>

        <form wire:submit="updateAvatar" class="space-y-6">
            <div class="flex items-center space-x-6">
                <!-- Current Avatar -->
                <div class="shrink-0">
                    <img class="h-20 w-20 object-cover rounded-full border-2 border-gray-300"
                        src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}">
                </div>

                <!-- Avatar Upload -->
                <div class="flex-1">
                    <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ __('Choose new photo') }}
                    </label>
                    <input type="file" wire:model="avatar" id="avatar" accept="image/*" class="block w-full text-sm text-gray-500
                                  file:mr-4 file:py-2 file:px-4
                                  file:rounded-full file:border-0
                                  file:text-sm file:font-semibold
                                  file:bg-indigo-50 file:text-indigo-700
                                  hover:file:bg-indigo-100">

                    @error('avatar')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <!-- Loading indicator -->
                    <div wire:loading wire:target="avatar" class="mt-2">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-indigo-600"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Uploading...
                        </div>
                    </div>

                    <!-- Preview -->
                    @if ($avatar)
                        <div class="mt-4">
                            <p class="text-sm text-gray-600 mb-2">Preview:</p>
                            <img src="{{ $avatar->temporaryUrl() }}"
                                class="h-20 w-20 object-cover rounded-full border-2 border-gray-300">
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-4">
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    wire:loading.attr="disabled" wire:target="updateAvatar">
                    {{ __('Update Photo') }}
                </button>

                @if (auth()->user()->avatar)
                    <button type="button" wire:click="deleteAvatar"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        {{ __('Delete Photo') }}
                    </button>
                @endif
            </div>

            @if (session('status'))
                <div class="text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
        </form>
    </div>

    <!-- Sound Settings Section -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center gap-3 mb-6">
            <div
                class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M9 7l7-7 7 7" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Sound Settings</h3>
                <p class="text-sm text-gray-500">Choose your notification sound type</p>
            </div>
        </div>

        <!-- Sound Type Selection -->
        <div class="space-y-3 mb-6">
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer"
                onclick="selectSoundType('advanced')">
                <div class="flex items-center gap-3">
                    <input type="radio" name="soundType" value="advanced" class="w-4 h-4 text-blue-600">
                    <span class="font-medium text-gray-700">Advanced Sound</span>
                </div>
                <button onclick="testSound('advanced')"
                    class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5 7l7-7 7 7" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors cursor-pointer border-2 border-blue-200"
                onclick="selectSoundType('whatsapp')">
                <div class="flex items-center gap-3">
                    <input type="radio" name="soundType" value="whatsapp" checked class="w-4 h-4 text-blue-600">
                    <span class="font-medium text-gray-700">WhatsApp Sound</span>
                    <span class="text-xs bg-blue-100 text-blue-600 px-2 py-1 rounded-full">Recommended</span>
                </div>
                <button onclick="testSound('whatsapp')"
                    class="p-2 bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 14.142M5 7l7-7 7 7" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Sound Indicator -->
        <div class="p-3 bg-gray-50 rounded-xl mb-6">
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-600">Sound Status:</span>
                <div class="sound-indicator not-playing" id="soundIndicator">
                    <div class="sound-bar"></div>
                    <div class="sound-bar"></div>
                    <div class="sound-bar"></div>
                    <div class="sound-bar"></div>
                    <div class="sound-bar"></div>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-6 p-4 bg-blue-50 rounded-xl border border-blue-200">
            <div class="flex items-start gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 mt-0.5" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <h5 class="font-medium text-blue-800 mb-1">Important Information</h5>
                    <p class="text-sm text-blue-700">• Sound will play when receiving new messages</p>
                    <p class="text-sm text-blue-700">• Works across all pages</p>
                    <p class="text-sm text-blue-700">• Make sure browser sound is enabled</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Select sound type
    function selectSoundType(type) {
        // Update radio buttons
        const radios = document.querySelectorAll('input[name="soundType"]');
        radios.forEach(radio => {
            radio.checked = radio.value === type;
        });

        // Update sound
        if (window.ChatNotification) {
            window.ChatNotification.setSoundType(type);
        }

        // Save setting
        localStorage.setItem('chatSoundType', type);

        // Show confirmation
        // showConfirmation('Sound type updated');
    }

    // Test sound
    function testSound(type) {
        const indicator = document.getElementById('soundIndicator');

        // Show indicator
        indicator.className = 'sound-indicator playing';

        // Play sound
        if (window.ChatNotification) {
            window.ChatNotification.setSoundType(type);
            window.ChatNotification.testSound();
        }

        // Hide indicator after 1 second
        setTimeout(() => {
            indicator.className = 'sound-indicator not-playing';
        }, 1000);
    }

    // Simulate message received
    function simulateMessage() {
        const indicator = document.getElementById('soundIndicator');

        // Show indicator
        indicator.className = 'sound-indicator playing';

        // Play sound
        if (window.ChatNotification) {
            window.ChatNotification.playSound();
        }

        // Show notification
        if (Notification.permission === 'granted') {
            new Notification('New Message', {
                body: 'This is a test message',
                icon: '/favicon.ico'
            });
        }

        // Hide indicator after 1 second
        setTimeout(() => {
            indicator.className = 'sound-indicator not-playing';
        }, 1000);

        // showConfirmation('Message sound played');
    }

    // Show confirmation message
    function showConfirmation(message) {
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300';
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (document.body.contains(notification)) {
                    document.body.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // Load saved settings
    document.addEventListener('DOMContentLoaded', () => {
        const savedSoundType = localStorage.getItem('chatSoundType') || 'whatsapp';
        selectSoundType(savedSoundType);

        // Request notification permission
        if ('Notification' in window && Notification.permission === 'default') {
            Notification.requestPermission();
        }
    });
</script>