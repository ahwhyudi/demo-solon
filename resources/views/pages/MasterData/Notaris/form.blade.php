<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Nama Kantor</label>
        <input type="text" name="nama_kantor" class="form-control"
            value="{{ old('nama_kantor', $item->nama_kantor ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label required">Nama Notaris</label>
        <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label required">Nomor</label>
        <input type="text" name="nomor" class="form-control" value="{{ old('nomor', $item->nomor ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label required">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $item->email ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">NPWP</label>
        <input type="text" name="npwp" class="form-control" value="{{ old('npwp', $item->npwp ?? '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Nomor SK</label>
        <input type="text" name="nomor_sk" class="form-control" value="{{ old('nomor_sk', $item->nomor_sk ?? '') }}">
    </div>
    <div class="col-12">
        <label class="form-label">Alamat Kantor</label>
        <textarea name="alamat_kantor" class="form-control" rows="3">{{ old('alamat_kantor', $item->alamat_kantor ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Foto</label>
        <input type="file" name="foto" class="form-control" accept="image/*">
        @if (!empty($item?->foto))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $item->foto) }}" alt="foto" style="width:96px;height:96px;object-fit:cover;border-radius:8px;">
            </div>
        @endif
    </div>
    <div class="col-md-6">
        <label class="form-label">Foto KTP</label>
        <input type="file" name="foto_ktp" class="form-control" accept="image/*">
        @if (!empty($item?->foto_ktp))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $item->foto_ktp) }}" alt="foto ktp" style="width:96px;height:96px;object-fit:cover;border-radius:8px;">
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Logo</label>
        <input type="file" name="logo" class="form-control" accept="image/*">
        @if (!empty($item?->logo))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $item->logo) }}" alt="logo" style="width:96px;height:96px;object-fit:cover;border-radius:8px;">
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Tanda Tangan</label>
        <input type="file" name="tanda_tangan" class="form-control" accept="image/*">
        @if (!empty($item?->tanda_tangan))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $item->tanda_tangan) }}" alt="tanda tangan" style="width:96px;height:96px;object-fit:cover;border-radius:8px;">
            </div>
        @endif
    </div>
    <div class="col-md-4">
        <label class="form-label">Stempel</label>
        <input type="file" name="stempel" class="form-control" accept="image/*">
        @if (!empty($item?->stempel))
            <div class="mt-2">
                <img src="{{ asset('storage/' . $item->stempel) }}" alt="stempel" style="width:96px;height:96px;object-fit:cover;border-radius:8px;">
            </div>
        @endif
    </div>
    <div class="col-12 d-flex gap-2">
        <button class="btn btn-primary" type="submit">Simpan</button>
        <a href="{{ route('master-data.notaris.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>
