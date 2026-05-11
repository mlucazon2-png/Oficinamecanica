<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AutoTech Pro') — AutoTech</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --red:          #C40000;
            --red-h:        #E00000;
            --red-dim:      rgba(196,0,0,.10);
            --red-glow:     rgba(196,0,0,.20);
            --red-border:   rgba(196,0,0,.28);
            --bg:           #070707;
            --surface:      #0F0F0F;
            --surface2:     #151515;
            --surface3:     #1C1C1C;
            --border:       rgba(255,255,255,.055);
            --border2:      rgba(255,255,255,.10);
            --border3:      rgba(255,255,255,.16);
            --text:         #F0F0F0;
            --text2:        #BBBBBB;
            --text3:        #888888;
            --success:      #1F7A3A;
            --success-bg:   rgba(31,122,58,.12);
            --success-text: #4BC970;
            --warning:      #C8860A;
            --warning-bg:   rgba(200,134,10,.12);
            --warning-text: #EFB34F;
            --info:         #2D7DD2;
            --info-bg:      rgba(45,125,210,.12);
            --info-text:    #5BA8F5;
            --danger-bg:    rgba(196,0,0,.12);
            --danger-text:  #E05555;
            --sidebar-w:    228px;
            --topbar-h:     54px;
            --radius:       10px;
            --radius-sm:    7px;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
            overflow-x: hidden;
        }

        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #2a2a2a; border-radius: 99px; }
        ::-webkit-scrollbar-thumb:hover { background: #3a3a3a; }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='1'/%3E%3C/svg%3E");
            opacity: .022;
            pointer-events: none;
            z-index: 9999;
        }

        #sidebar {
            width: var(--sidebar-w);
            min-height: 100vh;
            background: var(--surface);
            position: fixed;
            top: 0; left: 0;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            border-right: 1px solid var(--border);
            transition: transform .28s cubic-bezier(.4,0,.2,1);
        }

        .sidebar-brand {
            padding: 1rem 1.1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid var(--border);
            position: relative;
            overflow: hidden;
        }

        .sidebar-brand::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 1px;
            background: linear-gradient(90deg, var(--red) 0%, transparent 70%);
            opacity: .5;
        }

        .brand-icon-wrap {
            width: 36px; height: 36px;
            background: var(--red);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
            position: relative;
            overflow: hidden;
        }

        .brand-icon-wrap::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.15) 0%, transparent 60%);
        }

        .brand-icon-wrap i { font-size: 17px; color: #fff; position: relative; z-index: 1; }

        .brand-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 15px;
            letter-spacing: .04em;
            color: #fff;
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 9px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: .18em;
            display: block;
            margin-top: 1px;
        }

        .nav-scroll { flex: 1; overflow-y: auto; padding: .8rem 0; }

        .nav-label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: .18em;
            padding: .85rem 1.2rem .25rem;
            font-weight: 500;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 7px 1.1rem;
            font-size: 13px;
            color: #BEBEBE;
            border-left: 2px solid transparent;
            transition: all .15s;
            text-decoration: none;
            position: relative;
            margin: 1px 0;
        }

        .nav-link i { font-size: 15px; width: 18px; text-align: center; flex-shrink: 0; }

        .nav-link:hover {
            color: var(--text);
            background: rgba(255,255,255,.03);
        }

        .nav-link.active {
            color: #fff;
            background: var(--red-dim);
            border-left-color: var(--red);
        }

        .nav-link.active i { color: var(--red); }

        .nav-link .nav-badge {
            margin-left: auto;
            font-size: 9px;
            padding: 2px 6px;
            border-radius: 99px;
            background: var(--red-dim);
            color: var(--red-h);
            font-weight: 600;
        }

        .sidebar-footer {
            padding: .9rem 1.1rem;
            border-top: 1px solid var(--border);
        }

        .user-row {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-bottom: 10px;
        }

        .user-avatar {
            width: 32px; height: 32px;
            border-radius: 50%;
            background: var(--red);
            display: flex; align-items: center; justify-content: center;
            font-family: 'Syne', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
            position: relative;
        }

        .user-avatar::after {
            content: '';
            position: absolute;
            bottom: 0; right: 0;
            width: 8px; height: 8px;
            background: var(--success-text);
            border-radius: 50%;
            border: 2px solid var(--surface);
        }

        .user-info-name {
            font-size: 12.5px;
            font-weight: 500;
            color: var(--text);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-badge {
            margin-left: auto;
            font-size: 9px;
            background: rgba(255,255,255,.08);
            color: #AAAAAA;
            padding: 2px 7px;
            border-radius: 99px;
            text-transform: uppercase;
            letter-spacing: .1em;
            white-space: nowrap;
            flex-shrink: 0;
        }

        .btn-logout {
            width: 100%;
            padding: 6px;
            background: transparent;
            border: 1px solid rgba(196,0,0,.3);
            border-radius: var(--radius-sm);
            color: #D05555;
            font-size: 11.5px;
            font-family: 'DM Sans', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 6px;
            transition: all .18s;
        }

        .btn-logout:hover {
            background: var(--red-dim);
            border-color: var(--red);
            color: #fff;
        }

        #topbar {
            margin-left: var(--sidebar-w);
            height: var(--topbar-h);
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 900;
            gap: 1rem;
        }

        #topbar::after {
            content: '';
            position: absolute;
            bottom: -1px; left: 0;
            width: 180px; height: 1px;
            background: linear-gradient(90deg, var(--red), transparent);
            opacity: .7;
        }

        .breadcrumb-wrap {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #999;
        }

        .breadcrumb-wrap .bc-sep { opacity: .4; font-size: 10px; }

        .breadcrumb-wrap .bc-current {
            color: #F0F0F0;
            font-weight: 500;
        }

        .topbar-right { margin-left: auto; display: flex; gap: 8px; align-items: center; }

        .topbar-btn {
            width: 34px; height: 34px;
            background: transparent;
            border: 1px solid var(--border2);
            border-radius: var(--radius-sm);
            display: flex; align-items: center; justify-content: center;
            color: var(--text2);
            font-size: 15px;
            cursor: pointer;
            transition: all .15s;
            text-decoration: none;
            position: relative;
        }

        .topbar-btn:hover { background: var(--surface3); color: var(--text); border-color: var(--border3); }

        .notif-dot {
            position: absolute;
            top: 6px; right: 6px;
            width: 6px; height: 6px;
            background: var(--red);
            border-radius: 50%;
            border: 1.5px solid var(--surface);
        }

        .btn-nova-os {
            display: flex; align-items: center; gap: 7px;
            padding: 7px 15px;
            background: var(--red);
            border: none;
            border-radius: var(--radius-sm);
            color: #fff;
            font-size: 12.5px;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            letter-spacing: .04em;
            cursor: pointer;
            transition: all .18s;
            text-decoration: none;
            position: relative;
            overflow: hidden;
        }

        .btn-nova-os::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,.12) 0%, transparent 50%);
            opacity: 0;
            transition: opacity .18s;
        }

        .btn-nova-os:hover {
            background: var(--red-h);
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(196,0,0,.35);
        }

        .btn-nova-os:hover::before { opacity: 1; }

        .btn-nova-os:active { transform: translateY(0); }

        .sidebar-toggle {
            display: none;
            background: none;
            border: 1px solid var(--border2);
            border-radius: var(--radius-sm);
            color: var(--text2);
            padding: 5px 8px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 4px;
        }

        #content {
            margin-left: var(--sidebar-w);
            padding: 1.6rem 1.8rem;
            min-height: calc(100vh - var(--topbar-h));
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            color: var(--text);
            box-shadow: none;
        }

        .card-header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: .85rem 1.2rem;
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 13.5px;
            color: var(--text);
            border-radius: var(--radius) var(--radius) 0 0 !important;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .card-header .ch-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--red);
            flex-shrink: 0;
        }

        .card-footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
        }

        .stat-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.1rem 1.2rem;
            position: relative;
            overflow: hidden;
            transition: border-color .2s, transform .2s;
            cursor: default;
        }

        .stat-card:hover {
            border-color: var(--border2);
            transform: translateY(-2px);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 2px;
            border-radius: var(--radius) var(--radius) 0 0;
        }

        .stat-card.sc-red::before    { background: var(--red); }
        .stat-card.sc-blue::before   { background: var(--info); }
        .stat-card.sc-amber::before  { background: var(--warning); }
        .stat-card.sc-green::before  { background: var(--success); }

        .stat-card .sc-bg-icon {
            position: absolute;
            right: 14px; bottom: 8px;
            font-size: 52px;
            opacity: .045;
            line-height: 1;
        }

        .stat-card .sc-top {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 12px;
        }

        .stat-card .sc-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px;
        }

        .sc-red  .sc-icon { background: var(--danger-bg);  color: var(--danger-text); }
        .sc-blue .sc-icon { background: var(--info-bg);    color: var(--info-text); }
        .sc-amber .sc-icon { background: var(--warning-bg); color: var(--warning-text); }
        .sc-green .sc-icon { background: var(--success-bg); color: var(--success-text); }

        .sc-pill {
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 99px;
            font-weight: 500;
        }

        .sc-pill.up   { background: var(--success-bg); color: var(--success-text); }
        .sc-pill.neu  { background: rgba(255,255,255,.05); color: var(--text3); }
        .sc-pill.down { background: var(--danger-bg); color: var(--danger-text); }

        .stat-value {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 26px;
            color: #fff;
            line-height: 1;
            margin-bottom: 4px;
            letter-spacing: -.01em;
        }

        .stat-label {
            font-size: 10px;
            color: #AAAAAA;
            text-transform: uppercase;
            letter-spacing: .12em;
            font-weight: 500;
        }

        .table {
            color: #E8E8E8;
            --bs-table-bg: transparent;
            --bs-table-color: #E8E8E8;
            --bs-table-hover-bg: rgba(255,255,255,.04);
            margin: 0;
        }

        .table th {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #888 !important;
            border-color: rgba(255,255,255,.08) !important;
            padding: .75rem 1rem;
            font-weight: 600;
            font-family: 'DM Sans', sans-serif;
            white-space: nowrap;
            background: rgba(255,255,255,.02) !important;
        }

        .table td {
            vertical-align: middle;
            border-color: rgba(255,255,255,.06) !important;
            padding: .8rem 1rem;
            font-size: 13.5px;
            color: #E8E8E8 !important;
            background: transparent !important;
        }

        .table td a {
            color: #E8E8E8 !important;
            text-decoration: none;
        }

        .table td a:hover { color: var(--red-h) !important; }

        .table-hover tbody tr { transition: background .12s; cursor: default; }

        .table-hover tbody tr:hover td {
            background: rgba(255,255,255,.04) !important;
        }

        .table > :not(caption) > * > * {
            color: #E8E8E8;
            background-color: transparent;
        }

        .os-num {
            font-family: 'DM Mono', monospace;
            font-size: 11.5px;
            color: var(--red-h);
            font-weight: 500;
            letter-spacing: .03em;
        }

        .font-mono { font-family: 'DM Mono', monospace; font-size: .88rem; }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 10px;
            border-radius: 99px;
            font-size: 10px;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .status-badge::before {
            content: '';
            width: 5px; height: 5px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .badge-aberta               { background: rgba(255,255,255,.07); color: #999; }
        .badge-aberta::before       { background: #555; }
        .badge-em_diagnostico       { background: var(--info-bg);    color: var(--info-text); }
        .badge-em_diagnostico::before { background: var(--info); }
        .badge-aguardando_aprovacao { background: var(--warning-bg); color: var(--warning-text); }
        .badge-aguardando_aprovacao::before { background: var(--warning); }
        .badge-aprovada             { background: var(--info-bg);    color: var(--info-text); }
        .badge-aprovada::before     { background: var(--info); }
        .badge-em_execucao          { background: var(--danger-bg);  color: var(--danger-text); }
        .badge-em_execucao::before  { background: var(--red); animation: pulse-dot 1.4s ease infinite; }
        .badge-aguardando_pecas     { background: rgba(253,126,20,.12); color: #FD9B52; }
        .badge-aguardando_pecas::before { background: #fd7e14; }
        .badge-finalizada           { background: var(--success-bg); color: var(--success-text); }
        .badge-finalizada::before   { background: var(--success); }
        .badge-cancelada            { background: rgba(255,255,255,.05); color: #666; }
        .badge-cancelada::before    { background: #444; }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: .5; transform: scale(.7); }
        }

        .estoque-critico { color: var(--danger-text); font-weight: 600; }

        .btn-primary {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-weight: 500;
            transition: all .18s;
        }

        .btn-primary:hover {
            background: var(--red-h);
            border-color: var(--red-h);
            color: #fff;
            box-shadow: 0 4px 16px rgba(196,0,0,.3);
        }

        .btn-outline-primary {
            color: var(--red-h);
            border-color: var(--red-border);
            font-weight: 500;
        }

        .btn-outline-primary:hover {
            background: var(--red-dim);
            border-color: var(--red);
            color: #fff;
        }

        .btn-outline-secondary {
            color: var(--text2);
            border-color: var(--border2);
        }

        .btn-outline-secondary:hover {
            background: var(--surface3);
            color: var(--text);
            border-color: var(--border3);
        }

        .btn-outline-danger {
            color: var(--danger-text);
            border-color: rgba(196,0,0,.35);
        }

        .btn-outline-danger:hover {
            background: var(--red-dim);
            border-color: var(--red);
            color: #fff;
        }

        .btn-success {
            background: var(--success);
            border-color: var(--success);
        }

        .btn-warning {
            background: var(--warning);
            border-color: var(--warning);
            color: #000;
        }

        .form-control, .form-select {
            background: var(--surface2);
            border-color: var(--border2);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            border-radius: var(--radius-sm);
            transition: border-color .18s, box-shadow .18s;
        }

        .form-control:focus, .form-select:focus {
            background: var(--surface2);
            border-color: var(--red);
            color: var(--text);
            box-shadow: 0 0 0 3px rgba(196,0,0,.15);
        }

        .form-control::placeholder { color: var(--text3); }

        .form-label {
            color: #C0C0C0;
            font-size: .85rem;
            font-weight: 500;
            margin-bottom: .35rem;
        }

        .input-group-text {
            background: var(--surface3);
            border-color: var(--border2);
            color: var(--text3);
        }

        .form-check-input {
            background-color: var(--surface3);
            border-color: var(--border2);
        }

        .form-check-input:checked {
            background-color: var(--red);
            border-color: var(--red);
        }

        .form-text { color: var(--text3); font-size: .8rem; }

        .toast-container {
            position: fixed;
            top: calc(var(--topbar-h) + 16px);
            right: 20px;
            z-index: 9999;
        }

        .toast-at {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: var(--radius);
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
            padding: .75rem 1rem;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            min-width: 260px;
            animation: slideInRight .28s cubic-bezier(.34,1.56,.64,1);
        }

        .toast-at.toast-success { border-left: 3px solid var(--success-text); }
        .toast-at.toast-error   { border-left: 3px solid var(--red); }

        .toast-at i { font-size: 16px; flex-shrink: 0; }

        .toast-at.toast-success i { color: var(--success-text); }
        .toast-at.toast-error   i { color: var(--danger-text); }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }

        @keyframes slideOutRight {
            from { opacity: 1; transform: translateX(0); }
            to   { opacity: 0; transform: translateX(20px); }
        }

        .modal-content {
            background: var(--surface);
            border: 1px solid var(--border2);
            border-radius: var(--radius);
            color: var(--text);
            box-shadow: 0 24px 64px rgba(0,0,0,.7);
        }

        .modal-header {
            border-bottom: 1px solid var(--border);
            padding: 1rem 1.2rem;
        }

        .modal-footer {
            border-top: 1px solid var(--border);
            padding: .8rem 1.2rem;
        }

        .modal-title {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 15px;
        }

        .btn-close { filter: invert(1) brightness(.6); }

        .modal-backdrop { background: rgba(0,0,0,.75); }

        .pagination .page-link {
            background: var(--surface2);
            border-color: var(--border);
            color: var(--text2);
            font-size: 12.5px;
            transition: all .15s;
        }

        .pagination .page-link:hover {
            background: var(--surface3);
            border-color: var(--border2);
            color: var(--text);
        }

        .pagination .page-item.active .page-link {
            background: var(--red);
            border-color: var(--red);
            color: #fff;
        }

        .pagination .page-item.disabled .page-link { color: var(--text3); }

        .alert-danger {
            background: var(--danger-bg);
            border-color: var(--red-border);
            color: var(--danger-text);
        }

        .alert-success {
            background: var(--success-bg);
            border-color: rgba(31,122,58,.3);
            color: var(--success-text);
        }

        .alert-warning {
            background: var(--warning-bg);
            border-color: rgba(200,134,10,.3);
            color: var(--warning-text);
        }

        .alert-info {
            background: var(--info-bg);
            border-color: rgba(45,125,210,.3);
            color: var(--info-text);
        }

        a { color: var(--red-h); transition: color .15s; }

        a:hover { color: #ff6666; }

        .text-decoration-none { color: var(--text) !important; }

        .text-decoration-none:hover { color: var(--red-h) !important; }

        .page-header {
            margin-bottom: 1.4rem;
        }

        .page-title {
            font-family: 'Syne', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #fff;
            margin: 0 0 2px;
            letter-spacing: -.01em;
        }

        .page-subtitle {
            font-size: 12px;
            color: var(--text3);
        }

        .anim-fade-up {
            opacity: 0;
            transform: translateY(12px);
            animation: fadeUp .4s cubic-bezier(.4,0,.2,1) forwards;
        }

        @keyframes fadeUp {
            to { opacity: 1; transform: translateY(0); }
        }

        #sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.6);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        @media (max-width: 900px) {
            #sidebar {
                transform: translateX(-100%);
                box-shadow: none;
            }
            #sidebar.open {
                transform: translateX(0);
                box-shadow: 8px 0 40px rgba(0,0,0,.6);
            }
            #sidebar-overlay { display: block; opacity: 0; pointer-events: none; transition: opacity .25s; }
            #sidebar-overlay.show { opacity: 1; pointer-events: all; }
            #topbar, #content { margin-left: 0; }
            .sidebar-toggle { display: flex; align-items: center; }
        }

        @media (max-width: 576px) {
            #content { padding: 1rem; }
            .btn-nova-os .btn-text { display: none; }
        }

        @media print {
            #sidebar, #topbar, .no-print { display: none !important; }
            #content { margin: 0; padding: 0; }
            body { background: #fff; color: #000; }
            .card { border: 1px solid #ddd; background: #fff; }
        }

        #custom-cursor {
            width: 8px; height: 8px;
            background: var(--red);
            border-radius: 50%;
            position: fixed;
            top: 0; left: 0;
            pointer-events: none;
            z-index: 99999;
            transform: translate(-50%, -50%);
            transition: width .15s, height .15s, background .15s;
            mix-blend-mode: normal;
        }

        #custom-cursor.hovered {
            width: 20px; height: 20px;
            background: rgba(196,0,0,.4);
        }
    </style>

    @stack('styles')
</head>
<body>

<div id="custom-cursor"></div>

<div id="sidebar-overlay"></div>

<nav id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon-wrap">
            <i class="bi bi-gear-wide-connected"></i>
        </div>
        <div>
            <span class="brand-name">AutoTech</span>
            <span class="brand-sub">Oficina Pro</span>
        </div>
    </div>

    <div class="nav-scroll">
        <div class="nav-label">Principal</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-label">Cadastros</div>

        @if(auth()->user()->isCliente())
        <a href="{{ route('conta.veiculos') }}" class="nav-link {{ request()->routeIs('conta.veiculos') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> Veículos
        </a>
        @elseif(auth()->user()->isAtendente())
        <a href="{{ route('conta.clientes') }}" class="nav-link {{ request()->routeIs('conta.clientes') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Clientes
        </a>
        @else
        <a href="{{ route('veiculos.index') }}" class="nav-link {{ request()->routeIs('veiculos.*') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> Veículos
        </a>
        @endif
        @if(auth()->user()->isGerente() || auth()->user()->isAtendente())
        <a href="{{ route('mecanicos.index') }}" class="nav-link {{ request()->routeIs('mecanicos.*') ? 'active' : '' }}">
            <i class="bi bi-person-gear"></i> Mecânicos
        </a>
        @endif

        <div class="nav-label">Oficina</div>
        <a href="{{ route('os.index') }}" class="nav-link {{ request()->routeIs('os.*') ? 'active' : '' }}">
            <i class="bi bi-clipboard2-check"></i> Ordens de Serviço
        </a>
        <a href="{{ route('garantias.index') }}" class="nav-link {{ request()->routeIs('garantias.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> Garantias
        </a>

        @if(auth()->user()->isGerente() || auth()->user()->isAtendente())
        <a href="{{ route('notificacoes.index') }}" class="nav-link {{ request()->routeIs('notificacoes.*') ? 'active' : '' }}">
            <i class="bi bi-bell"></i> Notificações
            @php
            $nao_lidas = \App\Models\Notificacao::where('user_id', auth()->id())
                ->where('lida', false)
                ->where('status', 'pendente')
                ->count();
            @endphp
            @if($nao_lidas > 0)
            <span class="nav-badge" style="background: #e05555;">{{ $nao_lidas }}</span>
            @endif
        </a>
        @endif

        @if(auth()->user()->isGerente() || auth()->user()->isAtendente())
        <div class="nav-label">Estoque</div>
        <a href="{{ route('servicos.index') }}" class="nav-link {{ request()->routeIs('servicos.*') ? 'active' : '' }}">
            <i class="bi bi-tools"></i> Serviços
        </a>
        <a href="{{ route('pecas.index') }}" class="nav-link {{ request()->routeIs('pecas.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i> Peças
        </a>
        @endif

        @if(auth()->user()->isGerente())
        <div class="nav-label">Gestão</div>
        <a href="{{ route('relatorios.index') }}" class="nav-link {{ request()->routeIs('relatorios.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-line"></i> Relatórios
        </a>
        @endif
    </div>

    <div class="sidebar-footer">
        <div class="user-row">
            <div class="user-avatar" title="{{ auth()->user()->name }}">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? 'X', 0, 1)) }}
            </div>
            <div style="flex:1;min-width:0">
                <div class="user-info-name">{{ auth()->user()->name }}</div>
            </div>
            <div class="user-role-badge">{{ auth()->user()->role }}</div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn-logout">
                <i class="bi bi-box-arrow-left"></i> Sair
            </button>
        </form>
    </div>
</nav>

<header id="topbar">
    <button class="sidebar-toggle" onclick="toggleSidebar()" aria-label="Menu">
        <i class="bi bi-list"></i>
    </button>
    <div class="breadcrumb-wrap">
        <span>AutoTech</span>
        <span class="bc-sep">›</span>
        <span class="bc-current">@yield('breadcrumb', 'Dashboard')</span>
    </div>
    <div class="topbar-right">
        <button class="topbar-btn" title="Pesquisar" onclick="openSearch()">
            <i class="bi bi-search"></i>
        </button>
        <button class="topbar-btn no-print" title="Notificações" type="button" onclick="toggleMiniNotifs()">
            <i class="bi bi-bell"></i>
            <span class="notif-dot"></span>
        </button>

        <div id="mini-notificacoes" class="mini-notificacoes" style="display:none; position:absolute; top:62px; right:28px; width:360px; z-index:9500;">
            <div class="card" style="background:var(--surface); border:1px solid var(--border2); border-radius:var(--radius); overflow:hidden;">
                <div class="card-header" style="background:var(--surface); border-bottom:1px solid var(--border); border-radius:0 !important;">
                    <i class="bi bi-bell-fill me-2 text-warning"></i> Notificações
                    <span style="margin-left:auto; font-family:var(--radius-sm);"></span>
                </div>
                <div class="card-body" style="padding:.6rem .8rem;">
                    @php
                        $nao_lidas = \App\Models\Notificacao::where('user_id', auth()->id())
                            ->where('lida', false)
                            ->where('status', 'pendente')
                            ->orderByDesc('created_at')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if($nao_lidas->isEmpty())
                        <div class="text-center text-muted" style="padding:1rem 0; font-size:13px;">Nenhuma solicitação pendente.</div>
                    @else
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            @foreach($nao_lidas as $n)
                                <a href="{{ route('os.show', $n->os) }}" class="text-decoration-none" style="border:1px solid var(--border2); border-radius:10px; padding:.6rem .7rem; background:rgba(255,255,255,.02);">
                                    <div style="display:flex; align-items:center; justify-content:space-between; gap:8px;">
                                        <div style="font-family: 'DM Mono', monospace; color:var(--text); font-size:12.5px;">OS {{ $n->os->numero }}</div>
                                        <span style="font-size:10px; color:var(--warning-text); background:var(--warning-bg); padding:2px 8px; border-radius:99px;">Pendente</span>
                                    </div>
                                    <div style="margin-top:4px; font-size:12.5px; color:var(--text2);">
                                        {{ $n->os->cliente->nome }} · {{ $n->os->veiculo->marca }} {{ $n->os->veiculo->modelo }}
                                    </div>


                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="card-footer" style="background:var(--surface); border-top:1px solid var(--border); display:flex; justify-content:flex-end;">
                    <a href="{{ route('notificacoes.index') }}" class="btn btn-sm btn-outline-secondary">Ver todas</a>
                </div>
            </div>
        </div>

        <a href="{{ route('os.create') }}" class="btn-nova-os no-print">

            <i class="bi bi-plus-lg"></i>
            <span class="btn-text">Nova OS</span>
        </a>
    </div>
</header>

<main id="content">

    @if(session('success'))
    <div class="toast-container" id="toast-container">
        <div class="toast-at toast-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
            <button onclick="dismissToast(this)" style="margin-left:auto;background:none;border:none;color:var(--text3);cursor:pointer;padding:0;font-size:14px">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="toast-container" id="toast-container">
        <div class="toast-at toast-error">
            <i class="bi bi-exclamation-circle-fill"></i>
            <span>{{ session('error') }}</span>
            <button onclick="dismissToast(this)" style="margin-left:auto;background:none;border:none;color:var(--text3);cursor:pointer;padding:0;font-size:14px">
                <i class="bi bi-x"></i>
            </button>
        </div>
    </div>
    @endif

    @yield('content')

    @if ($errors && $errors->any())
        <div class="toast-container" id="toast-container">
            <div class="toast-at toast-error">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Erro ao salvar: {{ $errors->first() }}</span>
            </div>
        </div>
    @endif
</main>

<div id="search-modal" style="
    display:none; position:fixed; inset:0; z-index:9998;
    background:rgba(0,0,0,.7); backdrop-filter:blur(4px);
    align-items:flex-start; justify-content:center; padding-top:10vh;
" onclick="if(event.target===this)closeSearch()">
    <div style="
        background:var(--surface); border:1px solid var(--border2);
        border-radius:var(--radius); width:100%; max-width:520px;
        box-shadow:0 24px 64px rgba(0,0,0,.6); overflow:hidden;
    ">
        <div style="display:flex;align-items:center;padding:.85rem 1rem;gap:10px;border-bottom:1px solid var(--border)">
            <i class="bi bi-search" style="color:var(--text3);font-size:15px"></i>
            <input id="search-input" type="text" placeholder="Pesquisar OS, veículos…"

                   style="flex:1;background:none;border:none;outline:none;color:var(--text);font-family:'DM Sans',sans-serif;font-size:14px"
                   oninput="handleSearch(this.value)">
            <kbd style="background:var(--surface3);border:1px solid var(--border2);border-radius:4px;padding:2px 7px;font-size:10px;color:var(--text3)">ESC</kbd>
        </div>
        <div id="search-results" style="padding:.8rem 1rem;min-height:80px;max-height:300px;overflow-y:auto">
            <div style="color:var(--text3);font-size:12px;text-align:center;padding:1rem 0">
                <i class="bi bi-search" style="display:block;font-size:24px;margin-bottom:6px;opacity:.3"></i>
                Digite para buscar…
            </div>
        </div>
        <div style="padding:.5rem 1rem;border-top:1px solid var(--border);display:flex;gap:12px">
            <span style="font-size:10px;color:var(--text3)"><kbd style="background:var(--surface3);border:1px solid var(--border2);border-radius:3px;padding:1px 5px">↵</kbd> selecionar</span>
            <span style="font-size:10px;color:var(--text3)"><kbd style="background:var(--surface3);border:1px solid var(--border2);border-radius:3px;padding:1px 5px">ESC</kbd> fechar</span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function() {
    const cursor = document.getElementById('custom-cursor');
    let mx = -100, my = -100, cx = -100, cy = -100;
    let raf;
    document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
    function loop() {
        cx += (mx - cx) * 0.18;
        cy += (my - cy) * 0.18;
        cursor.style.left = cx + 'px';
        cursor.style.top  = cy + 'px';
        raf = requestAnimationFrame(loop);
    }
    loop();
    const interactables = 'a, button, .nav-link, .stat-card, .topbar-btn, .btn-nova-os, input, select, textarea, [role="button"]';
    document.querySelectorAll(interactables).forEach(el => {
        el.addEventListener('mouseenter', () => cursor.classList.add('hovered'));
        el.addEventListener('mouseleave', () => cursor.classList.remove('hovered'));
    });
})();

function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('sidebar-overlay');
    sb.classList.toggle('open');
    ov.classList.toggle('show');
}

document.getElementById('sidebar-overlay').addEventListener('click', () => {
    document.getElementById('sidebar').classList.remove('open');
    document.getElementById('sidebar-overlay').classList.remove('show');
});

function dismissToast(btn) {
    const toast = btn.closest('.toast-at');
    toast.style.animation = 'slideOutRight .25s forwards';
    setTimeout(() => toast.remove(), 250);
}

document.querySelectorAll('.toast-at').forEach(t => {
    setTimeout(() => {
        t.style.animation = 'slideOutRight .3s forwards';
        setTimeout(() => t.remove(), 300);
    }, 4500);
});

function openSearch() {
    const m = document.getElementById('search-modal');
    m.style.display = 'flex';
    setTimeout(() => document.getElementById('search-input').focus(), 50);
}

function closeSearch() {
    document.getElementById('search-modal').style.display = 'none';
    document.getElementById('search-input').value = '';
}

document.addEventListener('keydown', e => {
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') { e.preventDefault(); openSearch(); }
    if (e.key === 'Escape') closeSearch();
});

function handleSearch(val) {
    const res = document.getElementById('search-results');

}

function toggleMiniNotifs() {
    const el = document.getElementById('mini-notificacoes');
    if (!el) return;
    const isHidden = el.style.display === 'none' || !el.style.display;
    el.style.display = isHidden ? 'block' : 'none';
}

document.addEventListener('click', (e) => {
    const el = document.getElementById('mini-notificacoes');
    const btn = e.target.closest('.topbar-btn.no-print[title="Notificações"]');
    if (!el) return;
    if (btn) return;
    if (!el.contains(e.target)) {
        el.style.display = 'none';
    }
});

function handleSearch(val) {
    const res = document.getElementById('search-results');

    if (!val.trim()) {
        res.innerHTML = '<div style="color:var(--text3);font-size:12px;text-align:center;padding:1rem 0"><i class="bi bi-search" style="display:block;font-size:24px;margin-bottom:6px;opacity:.3"></i>Digite para buscar…</div>';
        return;
    }
    res.innerHTML = '<div style="color:var(--text3);font-size:12px;padding:.5rem 0"><i class="bi bi-arrow-repeat spin" style="margin-right:6px"></i>Buscando…</div>';
}

(function() {
    const cards = document.querySelectorAll('.stat-card, .card, .anim-fade-up');
    cards.forEach((el, i) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(14px)';
        el.style.transition = 'opacity .4s ease, transform .4s ease';
        el.style.transitionDelay = (i * 60) + 'ms';
        setTimeout(() => {
            el.style.opacity = '1';
            el.style.transform = 'translateY(0)';
        }, 80 + i * 60);
    });
})();

document.querySelectorAll('.btn-nova-os, .btn-primary, .btn-outline-primary').forEach(btn => {
    btn.style.position = 'relative';
    btn.style.overflow = 'hidden';
    btn.addEventListener('click', function(e) {
        const rect = this.getBoundingClientRect();
        const r = document.createElement('span');
        r.style.cssText = `
            position:absolute;border-radius:50%;
            width:6px;height:6px;
            background:rgba(255,255,255,.35);
            top:${e.clientY - rect.top - 3}px;
            left:${e.clientX - rect.left - 3}px;
            transform:scale(0);
            animation:ripple .5s linear;
            pointer-events:none;
        `;
        this.appendChild(r);
        setTimeout(() => r.remove(), 500);
    });
});

function animateCount(el) {
    const raw = el.textContent.trim();
    const isCurrency = raw.startsWith('R$');
    const num = parseFloat(raw.replace(/[^0-9,]/g, '').replace(',', '.')) || 0;
    if (num === 0) return;
    const duration = 800;
    const start = performance.now();
    const tick = (now) => {
        const p = Math.min((now - start) / duration, 1);
        const ease = 1 - Math.pow(1 - p, 3);
        const val = Math.round(num * ease);
        el.textContent = isCurrency
            ? 'R$ ' + val.toLocaleString('pt-BR', {minimumFractionDigits:2, maximumFractionDigits:2})
            : val.toString();
        if (p < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

document.querySelectorAll('.stat-value').forEach(animateCount);

const rippleStyle = document.createElement('style');
rippleStyle.textContent = `
  @keyframes ripple { to { transform: scale(30); opacity: 0; } }
  @keyframes spin { to { transform: rotate(360deg); } }
  .spin { animation: spin .7s linear infinite; display:inline-block; }
`;
document.head.appendChild(rippleStyle);
</script>

@stack('scripts')
</body>
</html>