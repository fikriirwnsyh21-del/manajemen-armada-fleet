@extends('layouts.app')

@section('title', 'Perawatan - Manajemen Armada')

@section('page-icon')
<i class="bi bi-tools"></i>
@endsection

@section('page-title', 'Manajemen Perawatan')

@section('top-actions')
<button class="btn btn-primary-custom" onclick="resetForm(); $('#form-card').slideDown(300);">
    <i class="bi bi-plus-lg me-1"></i> Tambah Perawatan
</button>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-tools"></i>
                </div>
                <div>
                    <div class="stat-number" id="stat-total">0</div>
                    <div class="stat-label">Total Perawatan</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                    <i class="bi bi-cash-stack"></i>
                </div>
                <div>
                    <div class="stat-number" id="stat-biaya" style="color: #16a34a;">Rp 0</div>
                    <div class="stat-label">Total Biaya Perawatan</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Form --}}
<div class="card-custom mb-4" id="form-card" style="display: none;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 id="form-title"><i class="bi bi-plus-circle me-2"></i>Tambah Perawatan</h5>
        <button type="button" class="btn-close" onclick="$('#form-card').slideUp(300);"></button>
    </div>
    <div class="card-body">
        <form id="perawatan-form">
            <input type="hidden" id="perawatan-id" value="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="kendaraan_id" class="form-label">Kendaraan</label>
                    <select class="form-select" id="kendaraan_id" required>
                        <option value="">-- Pilih Kendaraan --</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="tanggal" class="form-label">Tanggal Perawatan</label>
                    <input type="date" class="form-control" id="tanggal" required>
                </div>
                <div class="col-md-6">
                    <label for="jenis" class="form-label">Jenis Perawatan</label>
                    <input type="text" class="form-control" id="jenis" placeholder="Contoh: Ganti Oli, Servis Rutin" required>
                </div>
                <div class="col-md-6">
                    <label for="biaya" class="form-label">Biaya (Rp)</label>
                    <input type="number" class="form-control" id="biaya" placeholder="Contoh: 500000" min="0" required>
                </div>
                <div class="col-12 d-flex justify-content-end gap-2 mt-4">
                    <button type="button" class="btn btn-secondary-custom" onclick="resetForm(); $('#form-card').slideUp(300);">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary-custom" id="save-btn">
                        <i class="bi bi-check-lg me-1"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="card-custom">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-list-ul me-2"></i>Data Perawatan</h5>
        <button class="btn btn-secondary-custom btn-sm" onclick="loadData()">
            <i class="bi bi-arrow-clockwise me-1"></i> Refresh
        </button>
    </div>
    <div class="card-body p-0">
        <div class="spinner-wrapper" id="loading-spinner">
            <div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div>
        </div>
        <div class="table-responsive" id="table-wrapper" style="display: none;">
            <table class="table-custom" id="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kendaraan</th>
                        <th>Tanggal</th>
                        <th>Jenis Perawatan</th>
                        <th>Biaya</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="empty-state" id="empty-state" style="display: none;">
            <i class="bi bi-inbox d-block"></i>
            <p class="mb-0">Belum ada data perawatan.</p>
        </div>
        <div id="pagination-wrapper"></div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-exclamation-triangle text-danger me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus data perawatan <strong id="delete-label"></strong>?</p>
                <p class="text-muted small mb-0">Data yang dihapus tidak dapat dikembalikan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary-custom" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn">
                    <i class="bi bi-trash me-1"></i> Hapus
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    const API_URL = '/api/perawatan';
    let currentPage = 1;
    let deleteId = null;

    function resetForm() {
        $('#perawatan-id').val('');
        $('#perawatan-form')[0].reset();
        $('#form-title').html('<i class="bi bi-plus-circle me-2"></i>Tambah Perawatan');
        $('#save-btn').html('<i class="bi bi-check-lg me-1"></i> Simpan');
    }

    function loadOptions() {
        // Load Kendaraan
        $.getJSON('/api/kendaraan-list', function(data) {
            const select = $('#kendaraan_id');
            select.empty().append('<option value="">-- Pilih Kendaraan --</option>');
            data.forEach(item => {
                select.append(`<option value="${item.id}">${item.nopol} - ${item.merk}</option>`);
            });
        });
    }

    function loadData(page = 1) {
        currentPage = page;
        $('#loading-spinner').show();
        $('#table-wrapper, #empty-state').hide();
        $('#pagination-wrapper').empty();

        $.ajax({
            url: `${API_URL}?page=${page}`,
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                $('#loading-spinner').hide();
                const items = response.data;

                if (!items || items.length === 0) {
                    if (response.total === 0) { $('#empty-state').show(); }
                    loadStats();
                    return;
                }

                const tbody = $('#data-table tbody');
                tbody.empty();

                items.forEach((item, index) => {
                    const no = (response.current_page - 1) * response.per_page + index + 1;
                    const kendaraanText = item.kendaraan ? `${item.kendaraan.nopol} - ${item.kendaraan.merk}` : '-';
                    
                    tbody.append(`
                        <tr>
                            <td>${no}</td>
                            <td><strong>${escapeHtml(kendaraanText)}</strong></td>
                            <td>${item.tanggal}</td>
                            <td>${escapeHtml(item.jenis)}</td>
                            <td>${formatRupiah(item.biaya)}</td>
                            <td>
                                <button class="btn-action btn-edit me-1" onclick="editItem(${item.id})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button class="btn-action btn-delete" onclick="confirmDelete(${item.id}, '${escapeHtml(item.jenis)}')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>`);
                });

                $('#table-wrapper').show();
                $('#pagination-wrapper').html(renderPagination(response, 'loadData'));
                loadStats();
            },
            error: function () {
                $('#loading-spinner').hide();
                showAlert('Gagal memuat data perawatan.', 'danger');
            }
        });
    }

    function loadStats() {
        $.ajax({
            url: `${API_URL}?page=1&per_page=9999`,
            method: 'GET',
            dataType: 'json',
            success: function (allResp) {
                const all = allResp.data || [];
                $('#stat-total').text(allResp.total || all.length);
                
                let totalBiaya = 0;
                all.forEach(r => totalBiaya += parseInt(r.biaya || 0));
                $('#stat-biaya').text(formatRupiah(totalBiaya));
            }
        });
    }

    // Form submit
    $('#perawatan-form').on('submit', function (e) {
        e.preventDefault();
        const id = $('#perawatan-id').val();
        const isUpdate = id !== '';
        const method = isUpdate ? 'PUT' : 'POST';
        const url = isUpdate ? `${API_URL}/${id}` : API_URL;

        const payload = {
            kendaraan_id: $('#kendaraan_id').val(),
            tanggal: $('#tanggal').val(),
            jenis: $('#jenis').val().trim(),
            biaya: $('#biaya').val()
        };

        const $btn = $('#save-btn');
        const orig = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...');

        $.ajax({
            url: url,
            method: method,
            contentType: 'application/json',
            data: JSON.stringify(payload),
            headers: { 'Accept': 'application/json' },
            success: function () {
                showAlert(isUpdate ? 'Data perawatan berhasil diperbarui!' : 'Data perawatan berhasil ditambahkan!', 'success');
                resetForm();
                $('#form-card').slideUp(300);
                loadData(isUpdate ? currentPage : 1);
            },
            error: function (jqXHR) {
                if (jqXHR.status === 422) {
                    const errors = jqXHR.responseJSON.errors || jqXHR.responseJSON;
                    let msg = '<strong>Validasi gagal:</strong><ul class="mb-0 mt-1">';
                    for (const field in errors) {
                        (Array.isArray(errors[field]) ? errors[field] : [errors[field]]).forEach(m => { msg += `<li>${m}</li>`; });
                    }
                    msg += '</ul>';
                    showAlert(msg, 'danger');
                } else {
                    showAlert('Terjadi kesalahan saat menyimpan data.', 'danger');
                }
            },
            complete: function () { $btn.prop('disabled', false).html(orig); }
        });
    });

    function editItem(id) {
        $.ajax({
            url: `${API_URL}/${id}`,
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function (data) {
                $('#perawatan-id').val(data.id);
                $('#kendaraan_id').val(data.kendaraan_id);
                $('#tanggal').val(data.tanggal);
                $('#jenis').val(data.jenis);
                $('#biaya').val(data.biaya);
                
                $('#form-title').html('<i class="bi bi-pencil-square me-2"></i>Edit Perawatan');
                $('#save-btn').html('<i class="bi bi-check-lg me-1"></i> Perbarui');
                $('#form-card').slideDown(300);
                $('html, body').animate({ scrollTop: $('#form-card').offset().top - 80 }, 300);
            },
            error: function () { showAlert('Data tidak ditemukan.', 'danger'); }
        });
    }

    function confirmDelete(id, label) {
        deleteId = id;
        $('#delete-label').text(label);
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    $('#confirm-delete-btn').on('click', function () {
        if (!deleteId) return;
        const $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Menghapus...');

        $.ajax({
            url: `${API_URL}/${deleteId}`,
            method: 'DELETE',
            headers: { 'Accept': 'application/json' },
            success: function () {
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                showAlert('Data perawatan berhasil dihapus!', 'success');
                loadData(currentPage);
            },
            error: function () {
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                showAlert('Gagal menghapus data.', 'danger');
            },
            complete: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-trash me-1"></i> Hapus');
                deleteId = null;
            }
        });
    });

    $(document).ready(function () { 
        loadOptions();
        loadData(); 
    });
</script>
@endsection
