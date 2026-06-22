<form method="POST" action="{{ route('verify-2fa.store') }}">
    @csrf

    <div>
        <label for="code">Enter your 6-digit Verification Code:</label>
        <input type="text" id="code" name="code" required autofocus>
    </div>

    @error('code')
        <div style="color: red;">{{ $message }}</div>
    @enderror

    <button type="submit">Verify Code</button>
</form>