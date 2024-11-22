<h2>User Login</h2>
<form method="POST" action="{{ route('login') }}">
    @csrf
    <input type="email" name="email" placeholder="User Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <p>{{ $error }}</p>
    @endforeach
@endif
