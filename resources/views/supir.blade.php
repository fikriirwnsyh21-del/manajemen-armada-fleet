@extends('layouts.app')

@section('title', 'Supir - Manajemen Armada')

@section('page-icon')
<i class="bi bi-person-badge"></i>
@endsection

@section('page-title', 'Manajemen Supir')

@section('top-actions')
<button class="btn btn-primary-custom" onclick="resetForm(); $('#form-card').slideDown(300);">
    <i class="bi bi-plus-lg me-1"></i> Tambah Supir
</button>
@endsection

@section('content')

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #dbeafe; color: #2563eb;">
                    <i class="bi bi-person-badge"></i>
                </div>
                <div>
                    <div class="stat-number" id="stat-total">0</div>
                    <div class="stat-label">Total Supir</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #dcfce7; color: #16a34a;">
                    <i class="bi bi-person-check"></i>
                </div>
                <div>
                    <div class="stat-number" id="stat-aktif" style="color: #16a34a;">0</div>
                    <div class="stat-label">Aktif</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-4">
        <div class="stat-card">
            <div class="d-flex align-items-center gap-3">
                <div class="stat-icon" style="background: #fee2e2; color: #dc2626;">
                    <i class="bi bi-person-dash"></i>
                </div>
                <div>
                    <div class="stat-number" id="stat-tidak-aktif" style="color: #dc2626;">0</div>
                    <div class="stat-label">Tidak Aktif</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Form --}}
<div class="card-custom mb-4" id="form-card" style="display: none;">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 id="form-title"><i class="bi bi-plus-circle me-2"></i>Tambah Supir</h5>
        <button type="button" class="btn-close" onclick="$('#form-card').slideUp(300);"></button>
    </div>
    <div class="card-body">
        <form id="supir-form">
            <input type="hidden" id="supir-id" value="">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="nama" class="form-label">Nama Supir</label>
                    <input type="text" class="form-control" id="nama" placeholder="Masukkan nama supir" required>
                </div>
                <div class="col-md-6">
                    <label for="sim" class="form-label">Nomor SIM</label>
                    <input type="text" class="form-control" id="sim" placeholder="Contoh: 1234567890" required>
                </div>
                <div class="col-md-6">
                    <label for="no_hp" class="form-label">No. Handphone</label>
                    <input type="text" class="form-control" id="no_hp" placeholder="Contoh: 08123456789" required>
                </div>
                <div class="col-md-6">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Tidak Aktif">Tidak Aktif</option>
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
        <h5><i class="bi bi-list-ul me-2"></i>Data Supir</h5>
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
                        <th>Nama Supir</th>
                        <th>No. SIM</th>
                        <th>No. HP</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
        <div class="empty-state" id="empty-state" style="display: none;">
            <i class="bi bi-inbox d-block"></i>
            <p class="mb-0">Belum ada data supir.</p>
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
                <p>Apakah Anda yakin ingin menghapus supir <strong id="delete-label"></strong>?</p>
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
    const API_URL = '/api/supir';
    let currentPage = 1;
    let deleteId = null;

    function getStatusBadge(status) {
        switch (status) {
            case 'Aktif': return '<span class="badge-aktif"><i class="bi bi-check-circle me-1"></i>Aktif</span>';
            case 'Tidak Aktif': return '<span class="badge-tidak-aktif"><i class="bi bi-x-circle me-1"></i>Tidak Aktif</span>';
            default: return status;
        }
    }

    function resetForm() {
        $('#supir-id').val('');
        $('#supir-form')[0].reset();
        $('#form-title').html('<i class="bi bi-plus-circle me-2"></i>Tambah Supir');
        $('#save-btn').html('<i class="bi bi-check-lg me-1"></i> Simpan');
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
                    tbody.append(`
                        <tr>
                            <td>${no}</td>
                            <td><strong>${escapeHtml(item.nama)}</strong></td>
                            <td>${escapeHtml(item.sim)}</td>
                            <td>${escapeHtml(item.no_hp)}</td>
                            <td>${getStatusBadge(item.status)}</td>
                            <td>
                                <button class="btn-action btn-edit me-1" onclick="editItem(${item.id})">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button class="btn-action btn-delete" onclick="confirmDelete(${item.id}, '${escapeHtml(item.nama)}')">
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
                showAlert('Gagal memuat data supir.', 'danger');
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
                $('#stat-aktif').text(all.filter(k => k.status === 'Aktif').length);
                $('#stat-tidak-aktif').text(all.filter(k => k.status === 'Tidak Aktif').length);
            }
        });
    }

    // Form submit
    $('#supir-form').on('submit', function (e) {
        e.preventDefault();
        const id = $('#supir-id').val();
        const isUpdate = id !== '';
        const method = isUpdate ? 'PUT' : 'POST';
        const url = isUpdate ? `${API_URL}/${id}` : API_URL;

        const payload = {
            nama: $('#nama').val().trim(),
            sim: $('#sim').val().trim(),
            no_hp: $('#no_hp').val().trim(),
            status: $('#status').val()
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
                showAlert(isUpdate ? 'Data supir berhasil diperbarui!' : 'Data supir berhasil ditambahkan!', 'success');
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
                $('#supir-id').val(data.id);
                $('#nama').val(data.nama);
                $('#sim').val(data.sim);
                $('#no_hp').val(data.no_hp);
                $('#status').val(data.status);
                
                $('#form-title').html('<i class="bi bi-pencil-square me-2"></i>Edit Supir');
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
                showAlert('Data supir berhasil dihapus!', 'success');
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

    $(document).ready(function () { loadData(); });
</script>
@endsection
