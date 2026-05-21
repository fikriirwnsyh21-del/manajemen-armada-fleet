<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Manajemen Armada Fleet')</title>

    {{-- Bootstrap 5 CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Bootstrap Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e3a8a 0%, #2563eb 50%, #3b82f6 100%);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            padding: 0;
            box-shadow: 4px 0 24px rgba(30, 58, 138, 0.15);
            transition: transform 0.3s ease;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .sidebar-brand h4 {
            color: #fff;
            font-weight: 800;
            font-size: 1.1rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-brand small {
            color: rgba(255,255,255,0.6);
            font-size: 0.72rem;
            font-weight: 400;
            display: block;
            margin-top: 0.3rem;
        }

        .sidebar-nav {
            list-style: none;
            padding: 1rem 0.75rem;
            margin: 0;
        }

        .sidebar-nav .nav-label {
            color: rgba(255,255,255,0.45);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.75rem 0.75rem 0.4rem;
        }

        .sidebar-nav li a {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.65rem 0.75rem;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            font-size: 0.88rem;
            font-weight: 500;
            border-radius: 8px;
            margin-bottom: 2px;
            transition: all 0.2s ease;
        }

        .sidebar-nav li a:hover {
            background: rgba(255,255,255,0.15);
            color: #fff;
            transform: translateX(3px);
        }

        .sidebar-nav li a.active {
            background: rgba(255,255,255,0.2);
            color: #fff;
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .sidebar-nav li a i {
            font-size: 1.15rem;
            width: 24px;
            text-align: center;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: 260px;
            flex: 1;
            min-height: 100vh;
        }

        /* Top navbar */
        .top-bar {
            background: #fff;
            padding: 1rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 500;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .top-bar .page-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
        }

        .top-bar .page-title i {
            color: #3b82f6;
            margin-right: 0.5rem;
        }

        .content-area {
            padding: 1.5rem 2rem;
        }

        /* ===== CARDS ===== */
        .card-custom {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
            transition: box-shadow 0.3s ease;
        }

        .card-custom:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }

        .card-custom .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        .card-custom .card-header h5 {
            font-weight: 600;
            color: #1e293b;
            margin: 0;
            font-size: 1rem;
        }

        .card-custom .card-body {
            padding: 1.5rem;
        }

        /* ===== FORM CONTROLS ===== */
        .form-label {
            color: #475569;
            font-size: 0.82rem;
            font-weight: 600;
            margin-bottom: 0.35rem;
        }

        .form-control, .form-select {
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            padding: 0.55rem 0.85rem;
            font-size: 0.88rem;
            color: #1e293b;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        /* ===== BUTTONS ===== */
        .btn-primary-custom {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border: none;
            color: #fff;
            font-weight: 600;
            padding: 0.55rem 1.4rem;
            border-radius: 8px;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
            color: #fff;
        }

        .btn-secondary-custom {
            background: #f1f5f9;
            border: 1.5px solid #cbd5e1;
            color: #475569;
            font-weight: 500;
            padding: 0.55rem 1.4rem;
            border-radius: 8px;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .btn-secondary-custom:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        /* ===== TABLE ===== */
        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .table-custom thead th {
            background: #f8fafc;
            color: #64748b;
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.85rem 1rem;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
        }

        .table-custom tbody tr {
            transition: background 0.15s ease;
        }

        .table-custom tbody tr:hover {
            background: #f0f7ff;
        }

        .table-custom tbody td {
            padding: 0.8rem 1rem;
            border-bottom: 1px solid #f1f5f9;
            font-size: 0.88rem;
            color: #334155;
            vertical-align: middle;
        }

        /* ===== STATUS BADGES ===== */
        .badge-aktif {
            background: #dcfce7;
            color: #166534;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 600;
        }

        .badge-tidak-aktif {
            background: #fee2e2;
            color: #991b1b;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 600;
        }

        .badge-perbaikan {
            background: #fef3c7;
            color: #92400e;
            padding: 0.3rem 0.7rem;
            border-radius: 20px;
            font-size: 0.76rem;
            font-weight: 600;
        }

        /* ===== ACTION BUTTONS ===== */
        .btn-action {
            padding: 0.3rem 0.6rem;
            border-radius: 6px;
            font-size: 0.8rem;
            border: none;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        .btn-edit {
            background: #fef3c7;
            color: #92400e;
        }

        .btn-edit:hover {
            background: #fde68a;
            color: #78350f;
            transform: translateY(-1px);
        }

        .btn-delete {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete:hover {
            background: #fecaca;
            color: #7f1d1d;
            transform: translateY(-1px);
        }

        /* ===== STATS ===== */
        .stat-card {
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.2s ease;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .stat-card .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
        }

        .stat-card .stat-number {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e293b;
        }

        .stat-card .stat-label {
            font-size: 0.78rem;
            color: #64748b;
            font-weight: 500;
        }

        /* ===== ALERTS ===== */
        .alert-custom {
            border-radius: 10px;
            border: none;
            font-size: 0.88rem;
            padding: 0.8rem 1.2rem;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ===== PAGINATION ===== */
        .pagination-custom .page-item .page-link {
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.82rem;
            padding: 0.35rem 0.7rem;
            transition: all 0.2s ease;
        }

        .pagination-custom .page-item.active .page-link {
            background: #3b82f6;
            border-color: #3b82f6;
            color: #fff;
        }

        .pagination-custom .page-item .page-link:hover {
            background: #eff6ff;
            border-color: #3b82f6;
            color: #2563eb;
        }

        /* ===== MODAL ===== */
        .modal-content {
            border: none;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
        }

        .modal-header {
            border-bottom: 1px solid #e2e8f0;
            padding: 1.25rem 1.5rem;
        }

        .modal-footer {
            border-top: 1px solid #e2e8f0;
            padding: 1rem 1.5rem;
        }

        /* ===== LOADING ===== */
        .spinner-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem;
        }

        .spinner-wrapper .spinner-border { color: #3b82f6; }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: #94a3b8;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }

        /* ===== HAMBURGER MOBILE ===== */
        .sidebar-toggle {
            display: none;
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: inline-flex;
            }
            .content-area {
                padding: 1rem;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    {{-- Sidebar --}}
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4><i class="bi bi-truck"></i> Fleet Manager</h4>
            <small>Sistem Manajemen Armada</small>
        </div>
        <ul class="sidebar-nav">
            <li class="nav-label">Menu Utama</li>
            <li>
                <a href="/kendaraan" class="{{ request()->is('kendaraan') ? 'active' : '' }}">
                    <i class="bi bi-car-front"></i> Kendaraan
                </a>
            </li>
            <li>
                <a href="/supir" class="{{ request()->is('supir') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> Supir
                </a>
            </li>
            <li>
                <a href="/rute" class="{{ request()->is('rute') ? 'active' : '' }}">
                    <i class="bi bi-signpost-2"></i> Rute
                </a>
            </li>
            <li class="nav-label">Operasional</li>
            <li>
                <a href="/perawatan" class="{{ request()->is('perawatan') ? 'active' : '' }}">
                    <i class="bi bi-tools"></i> Perawatan
                </a>
            </li>
            <li>
                <a href="/bahan-bakar" class="{{ request()->is('bahan-bakar') ? 'active' : '' }}">
                    <i class="bi bi-fuel-pump"></i> Bahan Bakar
                </a>
            </li>
        </ul>
    </nav>

    {{-- Main Content --}}
    <div class="main-content">
        {{-- Top Bar --}}
        <div class="top-bar">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
                    <i class="bi bi-list"></i>
                </button>
                <span class="page-title">@yield('page-icon') @yield('page-title')</span>
            </div>
            <div>
                @yield('top-actions')
            </div>
        </div>

        {{-- Content --}}
        <div class="content-area">
            <div id="alert-placeholder"></div>
            @yield('content')
        </div>
    </div>

    {{-- jQuery --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    {{-- Bootstrap 5 JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        /**
         * Tampilkan alert pesan sukses/error
         */
        function showAlert(message, type = 'success') {
            const icon = type === 'success' ? 'check-circle-fill' : (type === 'danger' ? 'exclamation-triangle-fill' : 'info-circle-fill');
            const html = `
                <div class="alert alert-${type} alert-custom alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-${icon} me-2"></i>
                    <div>${message}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>`;
            $('#alert-placeholder').html(html);
            setTimeout(() => { $('#alert-placeholder .alert').alert('close'); }, 4000);
        }

        /**
         * Escape HTML (XSS protection)
         */
        function escapeHtml(text) {
            if (!text) return '';
            const map = { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' };
            return String(text).replace(/[&<>"']/g, m => map[m]);
        }

        /**
         * Format angka ke Rupiah
         */
        function formatRupiah(angka) {
            return 'Rp ' + Number(angka).toLocaleString('id-ID');
        }

        /**
         * Render pagination
         */
        function renderPagination(response, loadFunction) {
            if (response.last_page <= 1) return '';

            let html = `<div class="d-flex justify-content-between align-items-center p-3">
                <small class="text-muted">Menampilkan ${response.from || 0}-${response.to || 0} dari ${response.total || 0} data</small>
                <nav><ul class="pagination pagination-sm pagination-custom mb-0">`;

            html += `<li class="page-item ${response.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); ${loadFunction}(${response.current_page - 1})"><i class="bi bi-chevron-left"></i></a></li>`;

            for (let i = 1; i <= response.last_page; i++) {
                html += `<li class="page-item ${i === response.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="event.preventDefault(); ${loadFunction}(${i})">${i}</a></li>`;
            }

            html += `<li class="page-item ${response.current_page === response.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="event.preventDefault(); ${loadFunction}(${response.current_page + 1})"><i class="bi bi-chevron-right"></i></a></li>`;

            html += '</ul></nav></div>';
            return html;
        }

        // Close sidebar on mobile when clicking outside
        document.addEventListener('click', function(e) {
            const sidebar = document.getElementById('sidebar');
            if (window.innerWidth <= 991 && sidebar.classList.contains('show') && !sidebar.contains(e.target) && !e.target.classList.contains('sidebar-toggle')) {
                sidebar.classList.remove('show');
            }
        });
    </script>

    @yield('scripts')
</body>
</html>
