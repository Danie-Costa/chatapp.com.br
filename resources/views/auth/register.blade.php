@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-4">
    <h3>Cadastro</h3>
    <form method="POST" action="/register">
      @csrf
      <div class="form-group">
        <label>Nome</label>
        <input type="text" name="name" class="form-control">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" name="email" class="form-control">
      </div>
      <div class="form-group">
        <label>Senha</label>
        <input type="password" name="password" class="form-control">
      </div>
      <div class="form-group">
        <label>Confirmação</label>
        <input type="password" name="password_confirmation" class="form-control">
      </div>
      <button class="btn btn-success btn-block">Registrar</button>
    </form>
  </div>
</div>
@endsection
