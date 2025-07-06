<h1>Bem-vindo</h1>
@if(Auth::check())
<a href="/admin/dashboard">Dashboard</a>
<form method="post" action="{{ route('logout') }}">@csrf<button>Logout</button></form>
@else
<a href="{{ route('login') }}">Login</a>
@endif
