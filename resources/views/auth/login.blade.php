@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-4">
    <h3>Login</h3>
    <form method="POST" action="/login">
       @csrf
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
      </div>
      <div class="form-group">
        <label>Senha</label>
        <input type="password" name="password" class="form-control">
      </div>
      <button class="btn btn-primary btn-block">Entrar</button>
      <p class="mt-2"><a href="/register">Criar conta</a></p>
    </form>
  </div>
</div>
@endsection
