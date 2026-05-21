@extends('layouts.app')

@section('title', 'Rute - Manajemen Armada')

@section('page-icon')
<i class="bi bi-signpost-2"></i>
@endsection

@section('page-title', 'Manajemen Rute')

@section('top-actions')
<button class="btn btn-primary-custom" onclick="resetForm(); $('#form-card').slideDown(300);">
    <i class="bi bi-plus-lg me-1"></i> Tambah Rute
</button>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-signpost-split"></i>
                </div>
                <div>
                    <div class="stat-number" id="stat-total">0</div>
                    <div class="stat-label">Total Rute</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-6">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #f3e8ff; color: #9333ea;">
                    <i class="bi bi-geo-alt"></i>
                </div>
                <div>
                    <div class="stat-number" id="stat-jarak" style="color: #9333ea;">0 <span class="fs-6">km</span></div>
                    <div class="stat-label">Total Jarak Ditempuh</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Form --}}
<div class="card-custom mb-4" id="form-card" style="display: none;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 id="form-title"><i class="bi bi-plus-circle me-2"></i>Tambah Rute</h5>
        <button type="button" class="btn-close" onclick="$('#form-card').slideUp(300);"></button>
    </div>
    <div class="card-body">
        <form id="rute-form">
            <input type="hidden" id="rute-id" value="">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="asal" class="form-label">Asal</label>
                    <input type="text" class="form-control" id="asal" placeholder="Kota / Titik Asal" required>
                </div>
                <div class="col-md-4">
                    <label for="tujuan" class="form-label">Tujuan</label>
                    <input type="text" class="form-control" id="tujuan" placeholder="Kota / Titik Tujuan" required>
                </div>
                <div class="col-md-4">
                    <label for="jarak" class="form-label">Jarak (km)</label>
                    <input type="number" class="form-control" id="jarak" placeholder="Contoh: 150" min="0" step="0.1" required>
                </div>
                <div class="col-md-6">
                    <label for="kendaraan_id" class="form-label">Kendaraan</label>
                    <select class="form-select" id="kendaraan_id" required>
                        <option value="">-- Pilih Kendaraan --</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="supir_id" class="form-label">Supir</label>
                    <select class="form-select" id="supir_id" required>
                        <option value="">-- Pilih Supir --</option>
                    </select>
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
        <h5><i class="bi bi-list-ul me-2"></i>Data Rute</h5>
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
                        <th>Asal</th>
                        <th>Tujuan</th>
                        <th>Jarak (km)</th>
                        <th>Kendaraan</th>
                        <th>Supir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="empty-state" id="empty-state" style="display: none;">
            <i class="bi bi-inbox d-block"></i>
            <p class="mb-0">Belum ada data rute.</p>
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
                <p>Apakah Anda yakin ingin menghapus rute <strong id="delete-label"></strong>?</p>
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
    const API_URL = '/api/rute';
    let currentPage = 1;
    let deleteId = null;

    function resetForm() {
        $('#rute-id').val('');
        $('#rute-form')[0].reset();
        $('#form-title').html('<i class="bi bi-plus-circle me-2"></i>Tambah Rute');
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

        // Load Supir
        $.getJSON('/api/supir-list', function(data) {
            const select = $('#supir_id');
            select.empty().append('<option value="">-- Pilih Supir --</option>');
            data.forEach(item => {
                select.append(`<option value="${item.id}">${item.nama}</option>`);
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
                    const supirText = item.supir ? item.supir.nama : '-';
                    
                    tbody.append(`
                        <tr>
                            <td>${no}</td>
                            <td><strong>${escapeHtml(item.asal)}</strong></td>
                            <td><strong>${escapeHtml(item.tujuan)}</strong></td>
                            <td>${item.jarak}</td>
                            <td>${escapeHtml(kendaraanText)}</td>
                            <td>${escapeHtml(supirText)}</td>
                            <td>
                                <button class="btn-action btn-edit me-1" onclick="editItem(${item.id})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button class="btn-action btn-delete" onclick="confirmDelete(${item.id}, '${escapeHtml(item.asal)} ke ${escapeHtml(item.tujuan)}')">
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
                showAlert('Gagal memuat data rute.', 'danger');
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
                
                let totalJarak = 0;
                all.forEach(r => totalJarak += parseFloat(r.jarak || 0));
                $('#stat-jarak').html(`${totalJarak.toFixed(1)} <span class="fs-6">km</span>`);
            }
        });
    }

    // Form submit
    $('#rute-form').on('submit', function (e) {
        e.preventDefault();
        const id = $('#rute-id').val();
        const isUpdate = id !== '';
        const method = isUpdate ? 'PUT' : 'POST';
        const url = isUpdate ? `${API_URL}/${id}` : API_URL;

        const payload = {
            asal: $('#asal').val().trim(),
            tujuan: $('#tujuan').val().trim(),
            jarak: $('#jarak').val(),
            kendaraan_id: $('#kendaraan_id').val(),
            supir_id: $('#supir_id').val()
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
                showAlert(isUpdate ? 'Data rute berhasil diperbarui!' : 'Data rute berhasil ditambahkan!', 'success');
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
                $('#rute-id').val(data.id);
                $('#asal').val(data.asal);
                $('#tujuan').val(data.tujuan);
                $('#jarak').val(data.jarak);
                $('#kendaraan_id').val(data.kendaraan_id);
                $('#supir_id').val(data.supir_id);
                
                $('#form-title').html('<i class="bi bi-pencil-square me-2"></i>Edit Rute');
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
                showAlert('Data rute berhasil dihapus!', 'success');
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
