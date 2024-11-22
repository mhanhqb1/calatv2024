<h2>User Registration</h2>
<form method="POST" action="{{ route('register') }}">
    @csrf
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" placeholder="Your Name" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" placeholder="Your Email" required>

    <label for="password">Password:</label>
    <input type="password" name="password" id="password" placeholder="Password" required>

    <label for="password_confirmation">Confirm Password:</label>
    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>

    <button type="submit">Register</button>
</form>

@if ($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p>{{ $error }}</p>
        @endforeach
    </div>
@endif
