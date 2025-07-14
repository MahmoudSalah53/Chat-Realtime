<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('chat.index', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Create Account</h2>
        <p class="text-gray-600 text-sm">Join us today and start your journey</p>
    </div>

    <form wire:submit="register" class="space-y-6">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Full Name
            </label>
            <div class="relative">
                <input wire:model="name" 
                       id="name" 
                       type="text" 
                       name="name" 
                       required 
                       autofocus 
                       autocomplete="name"
                       class="input-field w-full px-4 py-3 rounded-xl border-2 focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400"
                       placeholder="Enter your full name">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
            </div>
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
            </label>
            <div class="relative">
                <input wire:model="email" 
                       id="email" 
                       type="email" 
                       name="email" 
                       required 
                       autocomplete="username"
                       class="input-field w-full px-4 py-3 rounded-xl border-2 focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400"
                       placeholder="Enter your email">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
            </div>
            @error('email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

       <!-- Password -->
<div>
    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
        Password
    </label>
    <div class="relative">
        <input wire:model="password" 
               id="password" 
               type="password" 
               name="password" 
               required 
               autocomplete="new-password"
               class="input-field w-full px-4 py-3 pr-10 rounded-xl border-2 focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400"
               placeholder="Create a password">
        
        <!-- Lock Icon -->
        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-5 h-5 text-gray-400" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
    </div>
    @error('password')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

<!-- Confirm Password -->
<div>
    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
        Confirm Password
    </label>
    <div class="relative">
        <input wire:model="password_confirmation" 
               id="password_confirmation" 
               type="password" 
               name="password_confirmation" 
               required 
               autocomplete="new-password"
               class="input-field w-full px-4 py-3 pr-10 rounded-xl border-2 focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400"
               placeholder="Confirm your password">
        
        <!-- Lock Icon -->
        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-5 h-5 text-gray-400" 
                 fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
    </div>
    @error('password_confirmation')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


        <!-- Register Button -->
        <button type="submit" 
                class="btn-primary w-full py-3 px-4 rounded-xl text-white font-medium hover:shadow-lg transition-all duration-300 relative overflow-hidden">
            <span class="relative z-10">Create Account</span>
        </button>

        <!-- Login Link -->
        <div class="text-center pt-4">
            <p class="text-sm text-gray-600">
                Already have an account? 
                <a href="{{ route('login') }}" 
                   wire:navigate
                   class="text-indigo-600 hover:text-indigo-500 font-medium transition-colors">
                    Sign in here
                </a>
            </p>
        </div>
    </form>
</div>