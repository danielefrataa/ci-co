<x-guest-layout>
    <form method="POST" action="{{ route('front_office.login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" required autofocus>
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required>
        </div>

        <!-- Submit Button -->
        <div class="mt-4">
            <button type="submit">
                Login
            </button>
        </div>
    </form>
</x-guest-layout>
