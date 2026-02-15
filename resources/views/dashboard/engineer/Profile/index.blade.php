@extends('layouts.dashboard')
@section('content')

<div class="container-fluid py-4">

    <div class="row">

        {{-- Profile Card --}}
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 text-center p-4">
                <img src="{{ asset('assets/img/user.png') }}"
                     class="rounded-circle mx-auto mb-3"
                     width="120">

                <h5 class="fw-bold">{{ $user->name }}</h5>
                <p class="text-muted">{{ $user->email }}</p>

                <hr>

                <div class="row text-center">
                    <div class="col">
                        <h5 class="fw-bold">{{ $totalTasks }}</h5>
                        <small>Total Tasks</small>
                    </div>
                    <div class="col">
                        <h5 class="fw-bold text-success">{{ $doneTasks }}</h5>
                        <small>Completed</small>
                    </div>
                    <div class="col">
                        <h5 class="fw-bold text-primary">{{ $inprogress }}</h5>
                        <small>In Progress</small>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Profile --}}
        <div class="col-md-8">

            {{-- Update Info --}}
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0">Edit Profile</h6>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('engineer.profile.update') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Name</label>
                                <input type="text" name="name"
                                       value="{{ $user->name }}"
                                       class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email"
                                       value="{{ $user->email }}"
                                       class="form-control">
                            </div>
                        </div>

                        <button class="btn btn-primary">
                            Update Profile
                        </button>
                    </form>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-header bg-white">
                    <h6 class="fw-bold mb-0">Change Password</h6>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('engineer.profile.password') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>New Password</label>
                                <input type="password" name="password"
                                       class="form-control">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label>Confirm Password</label>
                                <input type="password" name="password_confirmation"
                                       class="form-control">
                            </div>
                        </div>

                        <button class="btn btn-success">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
