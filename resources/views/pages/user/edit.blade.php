@extends('layouts.admin')

@section('title')
    Edit User {{ $user->name }}
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('akses.user.update', $user->id) }}" method="post">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label required">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ old('name', $user->name) }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            value="{{ old('email', $user->email) }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">No. Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            value="{{ old('phone', $user->phone) }}">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label required">Role</label>
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

                    <div class="col-md-12">
                        <label class="form-check">
                            <input class="form-check-input" type="checkbox" name="reset_password" value="1"
                                {{ old('reset_password') ? 'checked' : '' }}>
                            <span class="form-check-label">Reset password ke 12345678</span>
                        </label>
                    </div>

                    <div class="col-md-12">
                        <button class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('akses.user.index') }}" class="btn btn-outline-secondary ms-2">Kembali</a>
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
