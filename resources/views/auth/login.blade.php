@extends('layout.master2')

@section('content')
<div class="page-content d-flex align-items-center justify-content-center" style="background-image: url('/assets/images/login-background.jpeg');height: 100%;background-position: center;background-repeat: no-repeat;background-size: cover;">

  <div class="row w-100 mx-0 auth-page">
    <div class="col-md-8 col-xl-4 mx-auto">
      <div class="col-md-12 text-center">
        <a href="#" class="sidebar-brand">
          <image src="{{ url('assets/images/ico/catena-ico.png') }}" alt=""></image>
        </a>
      </div>
      <br>
      <div class="card">
          <div class="col-md-12 ps-md-0">
            <div class="auth-form-wrapper px-4 py-5">
              <label class="noble-ui-logo d-block mb-2" style="color: #B91202;">Welcome to Catena</label>
              <h5 class="text-muted fw-normal mb-4">Please login to your account for enjoy all features.</h5>

              <form class="forms-sample" method="POST" action="{{ route('login') }}">
                @csrf
                <div class="mb-3">
                  <label for="userEmail" class="form-label">Email address</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                </div>
                <div class="mb-3">
                  <label for="userPassword" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password" autocomplete="current-password" placeholder="Password">
                </div>
                <div class="form-check mb-3" style="display: none;">
                  <input type="checkbox" class="form-check-input" id="authCheck">
                  <label class="form-check-label" for="authCheck">
                    Remember me
                  </label>
                </div>

                <div class="text-center">
                  <button type="submit" class="btn btn-primary btn-icon-text mb-2 mb-md-0 w-100">
                    Login 
                  </button>
                  <br><br>
                  <label style="color: grey;">Problem with login? Please contact administrator</label>
                </div>
              </form>
            </div>
          </div>
      </div>
    </div>
  </div>

</div>
@endsection