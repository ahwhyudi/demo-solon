@extends('layouts.admin')

@section('title')
    Edit User {{ $user->name }}
@endsection

@section('content')
    <div class="card shadow-sm border border-light-subtle mb-4 rounded-3">
        <div class="card-header bg-light bg-opacity-50 py-3 border-bottom border-light-subtle">
            <h5 class="mb-0 fw-bold text-dark">Form Edit User</h5>
        </div>
        <div class="card-body pt-3">
            <form action="{{ route('akses.user.update', $user->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold required">NAMA</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold required">USERNAME</label>
                        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
                            value="{{ old('username', $user->username) }}">
                        @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold required">EMAIL</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">NO. TELEPON</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-secondary small fw-bold required">ROLE</label>
                        <select name="role[]" multiple class="form-select select2 @error('role') is-invalid @enderror"
                            data-placeholder="Pilih Role">
                            <option value=""></option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ in_array($role->id, old('role', $userRole)) ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="reset_password" id="resetPassword" value="1"
                                {{ old('reset_password') ? 'checked' : '' }}>
                            <label class="form-check-label text-dark fw-medium" for="resetPassword">
                                Reset password ke <strong>12345678</strong>
                            </label>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <hr class="border-light-subtle mb-3">
                        <div class="d-flex justify-content-start gap-2">
                            <button class="btn btn-primary px-4 shadow-sm">
                                <i class="bi bi-check2 me-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('akses.user.index') }}" class="btn btn-light border-light-subtle shadow-sm px-4">
                                <i class="bi bi-arrow-left me-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('addScript')
    <script>
        $('.select2').select2({
            theme: 'bootstrap-5',
            width: '100%'
        });
    </script>
@endpush