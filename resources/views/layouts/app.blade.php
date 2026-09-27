<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Ran's Cents - Kelola keuangan Anda dengan cerdas dan elegan">
  <title>@yield('title', 'Dashboard') — Ran's Cents</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

  <style>
    :root {
      /* PASTEL PEACH AS ACCENT */
      --gold-100: #FFF5F3;
      --gold-200: #FFE4DE;
      --gold-300: #FFC9BE;
      --gold-400: #FFA89B;
      --gold-500: #FF8A7A;
      --gold-600: #FA6B5B;
      --gold-700: #E05242;
      --gold-glow: rgba(255, 138, 122, 0.2);
      --gold-glow-strong: rgba(255, 138, 122, 0.4);

      /* SOFT PASTEL LIGHT THEME */
      --bg-900: #F3F4F6;
      --bg-800: #FFFFFF;
      --bg-700: #F9FAFB;
      --bg-600: #F3F4F6;
      --bg-500: #E5E7EB;
      --bg-400: #D1D5DB;

      /* DARK TEXT FOR LIGHT THEME */
      --text-100: #111827;
      --text-200: #374151;
      --text-300: #4B5563;
      --text-400: #6B7280;

      --success: #10B981; /* pastel green */
      --danger: #EF4444; /* pastel red */
      --warning: #F59E0B; /* pastel yellow */
      --info: #3B82F6; /* pastel blue */
      --purple: #A855F7;

      --border: #E5E7EB;
      --border-hover: #D1D5DB;

      --radius: 12px;
      --radius-lg: 20px;
      --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    body.dark-theme {
      --bg-900: #0A0A0F;
      --bg-800: #111118;
      --bg-700: #1A1A26;
      --bg-600: #22222E;
      --bg-500: #2A2A3A;
      --bg-400: #333345;
      
      --text-100: #F5F0E8;
      --text-200: #D4C8B0;
      --text-300: #9A8F78;
      --text-400: #6B6255;
      
      --border: rgba(255, 255, 255, 0.1);
      --border-hover: rgba(255, 255, 255, 0.2);
    }

    body.dark-theme {
      --bg-900: #111827;
      --bg-800: #1F2937;
      --bg-700: #374151;
      --bg-600: #4B5563;
      --bg-500: #6B7280;
      
      --text-100: #F9FAFB;
      --text-200: #F3F4F6;
      --text-300: #E5E7EB;
      --text-400: #D1D5DB;
      
      --border: rgba(255, 255, 255, 0.1);
      --border-hover: rgba(255, 255, 255, 0.2);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    html {
      scroll-behavior: smooth;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--bg-900);
      color: var(--text-100);
      min-height: 100vh;
      display: flex;
      overflow-x: hidden; /* Prevent horizontal scroll */
    }

    /* ===== SCROLLBAR ===== */
    ::-webkit-scrollbar { width: 6px; }
    ::-webkit-scrollbar-track { background: var(--bg-800); }
    ::-webkit-scrollbar-thumb { background: var(--gold-600); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--gold-500); }

    /* ===== SIDEBAR ===== */
    .sidebar {
      width: 260px;
      height: 100vh;
      height: 100dvh;
      height: -webkit-fill-available;
      background: var(--bg-800);
      border-right: 1px solid var(--border);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0;
      left: 0;
      bottom: 0;
      z-index: 100;
      overflow-y: auto;
      overflow-x: hidden;
    }

    .sidebar-brand {
      padding: 24px 20px;
      border-bottom: 1px solid var(--border);
    }

    .brand-logo {
      display: flex;
      align-items: center;
      gap: 12px;
      text-decoration: none;
    }

    .brand-icon {
      width: 42px;
      height: 42px;
      background: linear-gradient(135deg, var(--gold-500), var(--gold-700));
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      box-shadow: 0 4px 15px var(--gold-glow);
    }

    .brand-text {
      display: flex;
      flex-direction: column;
    }

    .brand-name {
      font-family: 'Quicksand', serif;
      font-size: 18px;
      font-weight: 700;
      background: linear-gradient(135deg, var(--gold-400), var(--gold-600));
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .brand-tagline {
      font-size: 11px;
      color: var(--text-300);
      letter-spacing: 0.5px;
    }

    .sidebar-nav {
      padding: 16px 12px;
      flex: 1;
    }

    .nav-section-title {
      font-size: 10px;
      font-weight: 600;
      color: var(--text-400);
      text-transform: uppercase;
      letter-spacing: 1.5px;
      padding: 8px 10px 6px;
      margin-top: 8px;
    }

    .nav-item {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 14px;
      border-radius: var(--radius);
      color: var(--text-200);
      text-decoration: none;
      font-size: 14px;
      font-weight: 500;
      transition: var(--transition);
      margin-bottom: 2px;
      position: relative;
      overflow: hidden;
    }

    .nav-item::before {
      content: '';
      position: absolute;
      left: 0;
      top: 0;
      bottom: 0;
      width: 3px;
      background: linear-gradient(180deg, var(--gold-400), var(--gold-600));
      border-radius: 0 2px 2px 0;
      opacity: 0;
      transition: var(--transition);
    }

    .nav-item:hover {
      background: rgba(212, 175, 55, 0.08);
      color: var(--gold-400);
    }

    .nav-item:hover::before { opacity: 1; }

    .nav-item.active {
      background: rgba(212, 175, 55, 0.12);
      color: var(--gold-400);
      box-shadow: inset 0 0 20px rgba(212, 175, 55, 0.05);
    }

    .nav-item.active::before { opacity: 1; }

    .nav-icon {
      width: 20px;
      text-align: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    .nav-badge {
      margin-left: auto;
      background: var(--danger);
      color: white;
      font-size: 10px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 20px;
    }

    .sidebar-footer {
      padding: 16px;
      padding-bottom: max(30px, env(safe-area-inset-bottom, 30px));
      border-top: 1px solid var(--border);
    }

    .sidebar-footer .date-time {
      text-align: center;
    }

    .sidebar-footer .date {
      font-size: 13px;
      color: var(--text-200);
      font-weight: 500;
    }

    .sidebar-footer .time {
      font-size: 20px;
      font-weight: 700;
      color: var(--gold-400);
      font-family: 'Quicksand', serif;
    }

    .table-responsive {
      width: 100%;
      overflow-x: auto;
      -webkit-overflow-scrolling: touch;
    }

    table {
      min-width: 600px;
      width: 100%;
      border-collapse: collapse;
    }

    table th {
      background: var(--bg-600);
      color: var(--text-300);
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.08em;
      padding: 12px 16px;
      text-align: left;
      border-bottom: 2px solid var(--border-hover);
      white-space: nowrap;
    }

    table td {
      padding: 13px 16px;
      font-size: 14px;
      color: var(--text-100);
      border-bottom: 1px solid var(--border);
      vertical-align: middle;
    }

    table tr:last-child td {
      border-bottom: none;
    }

    table tr:hover td {
      background: rgba(212, 175, 55, 0.04);
    }

    /* Column separator lines */
    table th + th,
    table td + td {
      border-left: 1px solid var(--border);
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
      margin-left: 260px;
      flex: 1;
      min-width: 0; /* Fix flex layout overflow */
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ===== TOP BAR ===== */
    .topbar {
      background: var(--bg-800);
      border-bottom: 1px solid var(--border);
      padding: 16px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      position: sticky;
      top: 0;
      z-index: 50;
    }

    .topbar-title {
      font-family: 'Quicksand', serif;
      font-size: 22px;
      font-weight: 600;
      color: var(--text-100);
    }

    .topbar-subtitle {
      font-size: 13px;
      color: var(--text-300);
      margin-top: 2px;
    }

    .topbar-left {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    .page-actions {
      display: flex;
      align-items: center;
      gap: 12px;
      margin-left: auto;
      margin-right: 24px;
    }

    .topbar-right {
      display: flex;
      align-items: center;
      gap: 16px;
    }

    /* ===== BUTTONS ===== */
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 10px 18px;
      border-radius: var(--radius);
      font-size: 14px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      text-decoration: none;
      white-space: nowrap;
    }

    .btn-gold {
      background: linear-gradient(135deg, var(--gold-500), var(--gold-700));
      color: #0A0A0F;
      box-shadow: 0 4px 15px var(--gold-glow);
    }

    .btn-gold:hover {
      transform: translateY(-1px);
      box-shadow: 0 6px 20px var(--gold-glow-strong);
      color: #0A0A0F;
    }

    .btn-outline {
      background: transparent;
      color: var(--text-200);
      border: 1px solid var(--border);
    }

    .btn-outline:hover {
      border-color: var(--gold-500);
      color: var(--gold-400);
      background: rgba(212, 175, 55, 0.05);
    }

    .btn-danger {
      background: rgba(239, 68, 68, 0.15);
      color: var(--danger);
      border: 1px solid rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
      background: var(--danger);
      color: white;
    }

    .btn-success {
      background: rgba(34, 197, 94, 0.15);
      color: var(--success);
      border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .btn-success:hover {
      background: var(--success);
      color: white;
    }

    .btn-sm {
      padding: 6px 12px;
      font-size: 12px;
    }

    /* ===== PAGE CONTENT ===== */
    .page-content {
      padding: 28px;
      flex: 1;
    }

    /* ===== CARDS ===== */
    .card {
      background: var(--bg-700);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 24px;
      transition: var(--transition);
      min-width: 0; /* Fix grid overflow */
    }

    .card:hover {
      border-color: var(--border-hover);
    }

    .card-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;
    }

    .card-title {
      font-size: 16px;
      font-weight: 600;
      color: var(--text-100);
    }

    /* ===== STAT CARDS ===== */
    .grid-2 {
      display: grid;
      grid-template-columns: repeat(2, minmax(0, 1fr));
      gap: 20px;
      margin-bottom: 28px;
    }

    .stats-grid {
      display: grid;
      grid-template-columns: repeat(3, minmax(0, 1fr));
      gap: 20px;
      margin-bottom: 28px;
    }

    .stat-card {
      background: var(--bg-700);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 22px;
      position: relative;
      overflow: hidden;
      transition: var(--transition);
      display: grid;
      grid-template-columns: auto 1fr;
      grid-template-areas: 
        "icon label"
        "icon value"
        "icon sub";
      column-gap: 16px;
      row-gap: 2px;
      align-items: center;
    }

    .stat-card::after {
      content: '';
      position: absolute;
      top: 0;
      right: 0;
      width: 80px;
      height: 80px;
      border-radius: 50%;
      opacity: 0.05;
      transform: translate(30%, -30%);
      transition: var(--transition);
    }

    .stat-card:hover {
      transform: translateY(-3px);
      border-color: var(--border-hover);
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .stat-card:hover::after { opacity: 0.1; }

    .stat-card.gold::after { background: var(--gold-500); }
    .stat-card.green::after { background: var(--success); }
    .stat-card.red::after { background: var(--danger); }
    .stat-card.blue::after { background: var(--info); }
    .stat-card.purple::after { background: var(--purple); }

    .stat-icon {
      grid-area: icon;
      width: 52px;
      height: 52px;
      border-radius: 14px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 24px;
      margin-bottom: 0;
    }

    .stat-icon.gold { background: #FFE4DE; color: #E05242; }
    .stat-icon.green { background: #D1FAE5; color: #059669; }
    .stat-icon.red { background: #FEE2E2; color: #DC2626; }
    .stat-icon.blue { background: #DBEAFE; color: #2563EB; }
    .stat-icon.purple { background: #EDE9FE; color: #7C3AED; }

    .stat-label {
      grid-area: label;
      font-size: 11px;
      font-weight: 600;
      color: var(--text-300);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 0;
      align-self: end;
    }

    .stat-value {
      grid-area: value;
      font-size: 20px;
      font-weight: 700;
      color: var(--text-100);
      margin-bottom: 0;
      font-family: 'Quicksand', serif;
      align-self: center;
    }

    .stat-value.gold { color: var(--gold-400); }
    .stat-value.green { color: var(--success); }
    .stat-value.red { color: var(--danger); }
    .stat-value.blue { color: var(--info); }
    .stat-value.purple { color: var(--purple); }

    .stat-sub {
      grid-area: sub;
      font-size: 11px;
      color: var(--text-400);
      align-self: start;
    }

    /* ===== TABLES ===== */
    .table-wrapper {
      overflow-x: auto;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead th {
      font-size: 11px;
      font-weight: 600;
      color: var(--text-300);
      text-transform: uppercase;
      letter-spacing: 1px;
      padding: 12px 16px;
      border-bottom: 1px solid var(--border);
      text-align: left;
      background: rgba(212, 175, 55, 0.03);
    }

    tbody td {
      padding: 14px 16px;
      border-bottom: 1px solid rgba(212, 175, 55, 0.05);
      font-size: 14px;
      color: var(--text-200);
      vertical-align: middle;
    }

    tbody tr {
      transition: var(--transition);
    }

    tbody tr:hover {
      background: rgba(212, 175, 55, 0.04);
    }

    tbody tr:last-child td {
      border-bottom: none;
    }

    /* ===== BADGES ===== */
    .badge {
      display: inline-flex;
      align-items: center;
      gap: 4px;
      padding: 4px 10px;
      border-radius: 20px;
      font-size: 11px;
      font-weight: 600;
    }

    .badge-gold { background: rgba(212, 175, 55, 0.15); color: var(--gold-400); }
    .badge-green { background: rgba(34, 197, 94, 0.15); color: var(--success); }
    .badge-red { background: rgba(239, 68, 68, 0.15); color: var(--danger); }
    .badge-blue { background: rgba(59, 130, 246, 0.15); color: var(--info); }
    .badge-purple { background: rgba(139, 92, 246, 0.15); color: var(--purple); }
    .badge-gray { background: rgba(107, 114, 128, 0.15); color: #9CA3AF; }
    .badge-orange { background: rgba(245, 158, 11, 0.15); color: var(--warning); }

    /* ===== FORMS ===== */
    .form-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-group.full {
      grid-column: 1 / -1;
    }

    .form-label {
      font-size: 13px;
      font-weight: 600;
      color: var(--text-200);
      letter-spacing: 0.3px;
    }

    .form-control {
      background: var(--bg-600);
      border: 1px solid var(--border);
      border-radius: var(--radius);
      color: var(--text-100);
      padding: 12px 16px;
      font-size: 14px;
      font-family: 'Inter', sans-serif;
      transition: var(--transition);
      outline: none;
      width: 100%;
    }

    .form-control:focus {
      border-color: var(--gold-500);
      box-shadow: 0 0 0 3px var(--gold-glow);
      background: var(--bg-500);
    }

    .form-control::placeholder {
      color: var(--text-400);
    }

    .form-control option {
      background: var(--bg-600);
      color: var(--text-100);
    }

    select.form-control {
      appearance: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%239CA3AF'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 14px center;
      background-size: 16px;
      padding-right: 40px;
    }

    textarea.form-control {
      resize: vertical;
      min-height: 100px;
    }

    .form-hint {
      font-size: 12px;
      color: var(--text-400);
    }

    /* ===== ALERTS ===== */
    .alert {
      padding: 14px 18px;
      border-radius: var(--radius);
      font-size: 14px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .alert-success {
      background: rgba(34, 197, 94, 0.1);
      border: 1px solid rgba(34, 197, 94, 0.3);
      color: var(--success);
    }

    .alert-danger {
      background: rgba(239, 68, 68, 0.1);
      border: 1px solid rgba(239, 68, 68, 0.3);
      color: var(--danger);
    }

    /* ===== PAGINATION ===== */
    .pagination {
      display: flex;
      gap: 6px;
      justify-content: center;
      padding: 20px 0 0;
    }

    .pagination a, .pagination span {
      padding: 8px 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 500;
      text-decoration: none;
      transition: var(--transition);
    }

    .pagination a {
      color: var(--text-200);
      border: 1px solid var(--border);
    }

    .pagination a:hover {
      border-color: var(--gold-500);
      color: var(--gold-400);
    }

    .pagination .active {
      background: linear-gradient(135deg, var(--gold-500), var(--gold-700));
      color: #0A0A0F;
      border: none;
    }

    /* ===== PROGRESS BAR ===== */
    .progress-bar {
      height: 6px;
      background: var(--bg-500);
      border-radius: 3px;
      overflow: hidden;
    }

    .progress-fill {
      height: 100%;
      border-radius: 3px;
      background: linear-gradient(90deg, var(--gold-600), var(--gold-400));
      transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ===== EMPTY STATE ===== */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
    }

    .empty-state .empty-icon {
      font-size: 48px;
      margin-bottom: 16px;
      opacity: 0.6;
    }

    .empty-state h3 {
      font-size: 18px;
      color: var(--text-200);
      margin-bottom: 8px;
    }

    .empty-state p {
      font-size: 14px;
      color: var(--text-300);
      margin-bottom: 20px;
    }

    /* ===== DIVIDER ===== */
    .divider {
      height: 1px;
      background: var(--border);
      margin: 20px 0;
    }

    /* ===== GRID ===== */
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }

    /* ===== AMOUNT FORMATTING ===== */
    .amount-positive { color: var(--success); font-weight: 600; }
    .amount-negative { color: var(--danger); font-weight: 600; }
    .amount-gold { color: var(--gold-400); font-weight: 600; }

    /* ===== MOBILE MENU ===== */
    .mobile-menu-btn {
      display: none;
      background: none;
      border: none;
      color: var(--text-100);
      font-size: 22px;
      cursor: pointer;
      padding: 4px;
    }

    /* ===== ANIMATIONS ===== */
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes shimmer {
      0% { background-position: -200px 0; }
      100% { background-position: calc(200px + 100%) 0; }
    }

    .animate-in {
      animation: fadeInUp 0.5s ease forwards;
    }

    /* ===== GOLD DIVIDER ===== */
    .gold-divider {
      height: 1px;
      background: linear-gradient(90deg, transparent, var(--gold-500), transparent);
      margin: 4px 0 16px;
    }

    /* ===== FILTER BAR ===== */
    .filter-bar {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 20px;
      align-items: flex-end;
    }

    .filter-bar .form-group {
      flex: 1;
      min-width: 160px;
      max-width: 220px;
    }

    /* ===== MODAL ===== */
    .modal-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.7);
      backdrop-filter: blur(4px);
      z-index: 1000;
      align-items: center;
      justify-content: center;
    }

    .modal-overlay.active {
      display: flex;
    }

    .modal {
      background: var(--bg-700);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 28px;
      max-width: 480px;
      width: 90%;
      animation: fadeInUp 0.3s ease;
    }

    .modal-title {
      font-family: 'Quicksand', serif;
      font-size: 20px;
      color: var(--gold-400);
      margin-bottom: 20px;
    }

    /* ===== DESKTOP (DEFAULT) TOPBAR & ACTIONS ===== */
    .topbar-left {
      display: flex; align-items: center; gap: 16px;
    }
    .page-actions {
      display: flex; gap: 12px; align-items: center; margin-left: auto; margin-right: 20px;
    }
    .topbar-right {
      display: flex; align-items: center; gap: 12px; flex-shrink: 0;
    }
    .theme-btn {
      background: transparent; border: 1px solid var(--border); color: var(--text-100); border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; cursor: pointer; flex-shrink: 0;
    }
    .user-pill {
      display: flex; align-items: center; gap: 8px; padding: 6px 12px; background: var(--bg-600); border: 1px solid var(--border); border-radius: 10px; flex-shrink: 0;
    }
    .user-avatar {
      width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, var(--gold-500), var(--gold-700)); display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 700; color: #0A0A0F; flex-shrink: 0;
    }
    .user-name {
      font-size: 13px; font-weight: 500; color: var(--text-200); white-space: nowrap;
    }

    /* ===== RESPONSIVE ===== */
    @media (max-width: 768px) {
      .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
      }

      .sidebar-footer {
        padding-bottom: max(60px, env(safe-area-inset-bottom, 60px)) !important;
      }

      .sidebar.open {
        transform: translateX(0);
      }

      .mobile-menu-btn {
        display: block;
      }

      body {
        display: block; /* Fix scrolling on mobile */
        overflow-y: auto !important;
      }
      
      .main-content {
        display: block; /* Fix scrolling on mobile */
        margin-left: 0;
        width: 100%;
        max-width: 100vw;
      }

      /* Mobile Topbar */
      .topbar {
        flex-wrap: wrap; gap: 10px;
      }
      .topbar-left {
        gap: 10px; order: 1; flex: 1;
      }
      .topbar-right {
        order: 2; gap: 8px; margin-left: 0;
      }
      .page-actions {
        order: 3; width: 100%; margin-top: 4px; gap: 8px; margin-left: 0; margin-right: 0;
      }
      .page-actions .btn {
        flex: 1; justify-content: center; padding: 8px 12px !important; font-size: 11px !important;
      }
      .theme-btn { width: 28px; height: 28px; }
      .theme-btn svg { width: 14px; height: 14px; }
      .user-pill { padding: 4px 8px; gap: 6px; }
      .user-avatar { width: 24px; height: 24px; font-size: 11px; }
      .user-name { font-size: 11px; }
      .desktop-only-text { display: none; }
      .topbar-title { font-size: 15px; }

      .stats-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 12px;
      }
      
      .stats-grid > .stat-card:last-child:nth-child(odd) {
        grid-column: 1 / -1;
      }

      .grid-2 {
        grid-template-columns: 1fr !important;
      }

      .wishlist-grid {
        grid-template-columns: 1fr !important;
      }

      .stat-card { padding: 14px; column-gap: 12px; row-gap: 2px; }
      .stat-icon { width: 42px; height: 42px; font-size: 18px; border-radius: 10px; }
      .stat-value { font-size: 16px; }
      .stat-label { font-size: 9px; }
      .stat-sub { font-size: 9px; }

      .topbar-subtitle { font-size: 10px; }
      .topbar-actions { gap: 8px !important; }
      .topbar-actions .btn { padding: 4px 8px !important; font-size: 11px !important; }
      .page-content { padding: 16px; }
    }
  </style>
  @yield('styles')
</head>
<body>
  <!-- Sidebar -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
      <a href="{{ route('dashboard') }}" class="brand-logo">
        <div class="brand-icon"><svg viewBox="0 0 24 24" fill="none" stroke="var(--gold-400)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></div>
        <div class="brand-text">
          <span class="brand-name">Ran's Cents</span>
          <span class="brand-tagline">Making sense of every cent.</span>
        </div>
      </a>
    </div>

    <nav class="sidebar-nav">
      <div class="nav-section-title">Menu Utama</div>
      <div class="gold-divider"></div>

      <a href="{{ route('dashboard') }}"
        class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg></span> Dashboard
      </a>

      <div class="nav-section-title" style="margin-top: 16px;">Keuangan</div>
      <div class="gold-divider"></div>

      <a href="{{ route('incomes.index') }}"
        class="nav-item {{ request()->routeIs('incomes.*') ? 'active' : '' }}">
        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg></span> Pemasukan
      </a>

      <a href="{{ route('expenses.index') }}"
        class="nav-item {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg></span> Pengeluaran
      </a>

      <a href="{{ route('debts.index') }}"
        class="nav-item {{ request()->routeIs('debts.*') ? 'active' : '' }}">
        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect><line x1="1" y1="10" x2="23" y2="10"></line></svg></span> Utang & Piutang
        @php $overdueDebts = \App\Models\Debt::where('user_id', auth()->id())->where('status','!=','paid')->where('due_date','<',now()->toDateString())->count(); @endphp
        @if($overdueDebts > 0)
          <span class="nav-badge">{{ $overdueDebts }}</span>
        @endif
      </a>

      <div class="nav-section-title" style="margin-top: 16px;">Perencanaan</div>
      <div class="gold-divider"></div>

      <a href="{{ route('wishlists.index') }}"
        class="nav-item {{ request()->routeIs('wishlists.*') ? 'active' : '' }}">
        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path><line x1="7" y1="7" x2="7.01" y2="7"></line></svg></span> Things to Buy
      </a>

      <a href="{{ route('savings.index') }}"
        class="nav-item {{ request()->routeIs('savings.*') ? 'active' : '' }}">
        <span class="nav-icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px;"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg></span> Tabungan
      </a>
    </nav>

    <div class="sidebar-footer">
      <div style="margin-bottom:12px;">
        <div class="time" id="sidebar-time" style="font-size:20px;font-weight:700;color:var(--gold-400);font-family:'Quicksand',serif;text-align:center;">--:--</div>
        <div class="date" id="sidebar-date" style="font-size:12px;color:var(--text-300);text-align:center;">Loading...</div>
      </div>
      <div style="text-align:center;margin-bottom:10px;padding:6px 0;">
        <div style="display:inline-flex;align-items:center;gap:5px;">
          <span style="font-size:14px;font-weight:700;color:var(--gold-500);font-family:'Quicksand',serif;">¢</span>
          <span style="font-size:11px;color:var(--text-400);letter-spacing:0.08em;font-weight:600;">ur-ran</span>
        </div>
      </div>
      <div style="border-top:1px solid var(--border);padding-top:12px;">
        @auth
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;">
          <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--gold-500),var(--gold-700));display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:700;color:#0A0A0F;flex-shrink:0;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
          <div style="flex:1;min-width:0;">
            <div style="font-size:13px;font-weight:600;color:var(--text-100);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
            <div style="font-size:11px;color:var(--text-400);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->email }}</div>
          </div>
        </div>
        <form action="{{ route('auth.logout') }}" method="POST">
          @csrf
          <button type="submit" style="width:100%;padding:9px;background:rgba(239,68,68,0.1);border:1px solid rgba(239,68,68,0.25);border-radius:8px;color:#F87171;font-size:13px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;transition:all 0.3s;" onmouseover="this.style.background='rgba(239,68,68,0.2)'" onmouseout="this.style.background='rgba(239,68,68,0.1)'">
             Keluar
          </button>
        </form>
        @else
        <div style="display:flex; flex-direction:column; gap:8px;">
          <a href="{{ route('auth.login') }}" style="width:100%;padding:9px;background:var(--gold-500);border:none;border-radius:8px;color:#fff;font-size:13px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;text-align:center;text-decoration:none;">Masuk</a>
          <a href="{{ route('auth.register') }}" style="width:100%;padding:9px;background:transparent;border:1px solid var(--border);border-radius:8px;color:var(--text-100);font-size:13px;font-weight:600;cursor:pointer;font-family:'Inter',sans-serif;text-align:center;text-decoration:none;">Daftar</a>
        </div>
        @endauth
      </div>
    </div>
  </aside>

  <!-- Main Content -->
  <div class="main-content">
    <!-- Top Bar -->
    <header class="topbar">
      <!-- Title Group -->
      <div class="topbar-left">
        <button class="mobile-menu-btn" onclick="toggleSidebar()" id="menu-btn"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></button>
        <div>
          <h1 class="topbar-title">@yield('page-title', 'Dashboard')</h1>
          <p class="topbar-subtitle desktop-only-text">@yield('page-subtitle', "Welcome to Ran's Cents")</p>
        </div>
      </div>
      
      <!-- Actions Group -->
      @hasSection('topbar-actions')
      <div class="page-actions">
        @yield('topbar-actions')
      </div>
      @endif

      <!-- User Group -->
      <div class="topbar-right">
        <button onclick="toggleTheme()" class="theme-btn" title="Ganti Tema">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 16px; height: 16px;"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
        </button>
        @auth
        <div class="user-pill">
          <div class="user-avatar">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
          <span class="user-name">{{ Str::words(auth()->user()->name, 1, '') }}</span>
        </div>
        @else
        <a href="{{ route('auth.login') }}" class="btn btn-gold btn-sm" style="text-decoration:none;">Login</a>
        @endauth
      </div>
    </header>

    <!-- Page Content -->
    <main class="page-content">
      @if(session('success'))
        <div class="alert alert-success animate-in">
          <span></span> {{ session('success') }}
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger animate-in">
          <span></span> {{ session('error') }}
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger animate-in">
          <span></span>
          <div>
            @foreach($errors->all() as $error)
              <div>{{ $error }}</div>
            @endforeach
          </div>
        </div>
      @endif

      @yield('content')
    </main>
  </div>

  <!-- Mobile Overlay -->
  <div style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:99;"
     id="sidebar-overlay" onclick="toggleSidebar()"></div>

  <script>
    // Clock
    function updateClock() {
      const now = new Date();
      const timeEl = document.getElementById('sidebar-time');
      const dateEl = document.getElementById('sidebar-date');

      if (timeEl) {
        timeEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
      }
      if (dateEl) {
        dateEl.textContent = now.toLocaleDateString('id-ID', {
          weekday: 'long', day: 'numeric', month: 'long'
        });
      }
    }
    updateClock();
    setInterval(updateClock, 1000);

    // Mobile sidebar
    function toggleSidebar() {
      const sidebar = document.getElementById('sidebar');
      const overlay = document.getElementById('sidebar-overlay');
      sidebar.classList.toggle('open');
      overlay.style.display = sidebar.classList.contains('open') ? 'block' : 'none';
    }

    // Auto-dismiss alerts
    document.querySelectorAll('.alert').forEach(alert => {
      setTimeout(() => {
        alert.style.transition = 'opacity 0.5s ease';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }, 5000);
    });

    // Theme Toggle
    function toggleTheme() {
      document.body.classList.toggle('dark-theme');
      localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
    }
    
    if (localStorage.getItem('theme') === 'dark') {
      document.body.classList.add('dark-theme');
    }

    // Theme Toggle
    function toggleTheme() {
      document.body.classList.toggle('dark-theme');
      localStorage.setItem('theme', document.body.classList.contains('dark-theme') ? 'dark' : 'light');
    }
    
    if (localStorage.getItem('theme') === 'dark') {
      document.body.classList.add('dark-theme');
    }

    // Animate elements on page load
    document.querySelectorAll('.stat-card, .card').forEach((el, i) => {
      el.style.opacity = '0';
      el.style.transform = 'translateY(20px)';
      setTimeout(() => {
        el.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        el.style.opacity = '1';
        el.style.transform = 'translateY(0)';
      }, i * 80);
    });
  </script>

  @yield('scripts')
</body>
</html>
