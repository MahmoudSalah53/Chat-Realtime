<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('chat.index', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Sign In</h2>
        <p class="text-gray-600 text-sm">Enter your credentials to access your account</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
            <p class="text-green-700 text-sm">{{ session('status') }}</p>
        </div>
    @endif

    <form wire:submit="login" class="space-y-6">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email Address
            </label>
            <div class="relative">
                <input wire:model="form.email" 
                       id="email" 
                       type="email" 
                       name="email" 
                       required 
                       autofocus 
                       autocomplete="username"
                       class="input-field w-full px-4 py-3 rounded-xl border-2 focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400"
                       placeholder="Enter your email">
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                </div>
            </div>
            @error('form.email')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
        Password
    </label>
    <div class="relative">
        <input wire:model="form.password" 
               id="password" 
               type="password" 
               name="password" 
               required 
               autocomplete="current-password"
               class="input-field w-full px-4 py-3 pr-10 rounded-xl border-2 focus:outline-none focus:ring-0 text-gray-800 placeholder-gray-400"
               placeholder="Enter your password">

        <!-- Lock icon (SVG) on the right inside the input field -->
        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none">
            <svg xmlns="http://www.w3.org/2000/svg" 
                 class="w-5 h-5 text-gray-400" 
                 fill="none" 
                 viewBox="0 0 24 24" 
                 stroke="currentColor" 
                 stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
            </svg>
        </div>
    </div>
    @error('form.password')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>


        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember" class="flex items-center cursor-pointer">
                <input wire:model="form.remember" 
                       id="remember" 
                       type="checkbox" 
                       name="remember"
                       class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                <span class="ml-2 text-sm text-gray-600">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" 
                   wire:navigate
                   class="text-sm text-indigo-600 hover:text-indigo-500 font-medium transition-colors">
                    Forgot password?
                </a>
            @endif
        </div>

        <!-- Login Button -->
        <button type="submit" 
                class="btn-primary w-full py-3 px-4 rounded-xl text-white font-medium hover:shadow-lg transition-all duration-300 relative overflow-hidden">
            <span class="relative z-10">Sign In</span>
        </button>

        <!-- Register Link -->
        <div class="text-center pt-4">
            <p class="text-sm text-gray-600">
                Don't have an account? 
                <a href="{{ route('register') }}" 
                   wire:navigate
                   class="text-indigo-600 hover:text-indigo-500 font-medium transition-colors">
                    Create one here
                </a>
            </p>
        </div>
    </form>
</div>