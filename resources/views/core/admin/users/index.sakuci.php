@extends('layouts.app')

@section('title', 'Manage User')

@section('content')

    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <span class="badge rounded-pill badge-brand px-3 py-2 mb-2">Area Admin</span>
            <h1 class="h4 mb-0">Manage User</h1>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">&larr; Kembali</a>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 mb-3">Tambah User Baru</h2>

                    <form method="POST" action="{{ route('admin.users.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="username">Username</label>
                            <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control {{ errors()->has('username') ? 'is-invalid' : '' }}" autofocus>
                            @error('username') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Password</label>
                            <input type="password" id="password" name="password" class="form-control {{ errors()->has('password') ? 'is-invalid' : '' }}">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="role">Role</label>
                            <select id="role" name="role" class="form-select {{ errors()->has('role') ? 'is-invalid' : '' }}">
                                @foreach ($roles as $roleOption)
                                    <option value="{{ $roleOption->name }}" @if (old('role') === $roleOption->name) selected @endif>{{ $roleOption->name }}</option>
                                @endforeach
                            </select>
                            @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <div class="form-text">Butuh role lain? Tambahkan dulu di <a href="{{ route('admin.roles.index') }}">Manage Role</a>.</div>
                        </div>

                        <button class="btn btn-brand w-100" type="submit">Tambah User</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h2 class="h6 mb-3">Daftar User</h2>

                    <table class="table table-sm align-middle mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->username }}</td>
                                    <td><code class="inline">{{ $item->role }}</code></td>
                                    <td class="text-end text-nowrap">
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                onclick="document.getElementById('edit-user-{{ $item->id }}').showModal()">Edit</button>

                                        <form method="POST" action="{{ route('admin.users.destroy', ['id' => $item->id]) }}"
                                              class="d-inline"
                                              onsubmit="return confirm('Hapus user {{ $item->username }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                        </form>

                                        <dialog id="edit-user-{{ $item->id }}" class="text-start rounded-3 border-0 p-4 shadow"
                                                style="background: var(--bs-body-bg, #fff); color: var(--bs-body-color, #212529); width: min(420px, 92vw);">
                                            <form method="POST" action="{{ route('admin.users.update', ['id' => $item->id]) }}">
                                                @csrf
                                                @method('PUT')

                                                <h2 class="h6 mb-3">Edit User</h2>

                                                <div class="mb-3">
                                                    <label class="form-label" for="edit-username-{{ $item->id }}">Username</label>
                                                    <input type="text" id="edit-username-{{ $item->id }}" name="username" value="{{ $item->username }}" class="form-control">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="edit-password-{{ $item->id }}">Password baru</label>
                                                    <input type="password" id="edit-password-{{ $item->id }}" name="password" class="form-control" placeholder="Kosongkan jika tidak diganti">
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="edit-role-{{ $item->id }}">Role</label>
                                                    <select id="edit-role-{{ $item->id }}" name="role" class="form-select">
                                                        @foreach ($roles as $roleOption)
                                                            <option value="{{ $roleOption->name }}" @if ($item->role === $roleOption->name) selected @endif>{{ $roleOption->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="d-flex justify-content-end gap-2">
                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                            onclick="document.getElementById('edit-user-{{ $item->id }}').close()">Batal</button>
                                                    <button type="submit" class="btn btn-sm btn-brand">Simpan</button>
                                                </div>
                                            </form>
                                        </dialog>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-secondary">Belum ada user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection