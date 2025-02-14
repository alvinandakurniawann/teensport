
<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full space-y-8 p-8">
            <div>
                <h2 class="text-center text-3xl font-bold">Register</h2>
            </div>
            
            <form method="POST" action="{{ route('register') }}" class="mt-8 space-y-6">
                @csrf
                
                <div>
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" required class="w-full px-3 py-2 border rounded-md @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" required class="w-full px-3 py-2 border rounded-md @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required class="w-full px-3 py-2 border rounded-md @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required class="w-full px-3 py-2 border rounded-md">
                </div>

                <div>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-500 text-white rounded-md">
                        Register
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>