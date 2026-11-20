@extends('layouts.app')


@section('extra_css')
<link rel="stylesheet" href="{{ asset('static/budget_app/vendor/jexcel/jexcel.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('static/budget_app/vendor/jsuites/jsuites.css') }}" type="text/css" />
<link rel="stylesheet" href="{{ asset('static/budget_app/vendor/tom-select/tom-select.bootstrap5.min.css') }}">
<style>
    /* ==========================================================================
       DataTable Visual Consistency & Fixes (Matching Image 2 Reference)
       ========================================================================== */
    .datatable-wrapper {
        width: 100%;
    }
    .datatable-top {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 0 0 0.75rem 0 !important;
        margin-bottom: 0.25rem !important;
    }
    .datatable-top > .datatable-dropdown {
        display: flex !important;
        align-items: center !important;
        font-size: 0.875rem !important;
        color: #475569 !important;
    }
    .datatable-top > .datatable-dropdown label {
        display: flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
        font-size: 0.875rem !important;
        color: #475569 !important;
    }
    /* Fixed selector: plenty of padding, clean chevron, zero text clipping */
    .datatable-selector {
        width: auto !important;
        min-width: 4.25rem !important;
        display: inline-block !important;
        padding: 0.375rem 2rem 0.375rem 0.75rem !important;
        margin-right: 0.5rem !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        line-height: 1.5 !important;
        color: #334155 !important;
        background-color: #ffffff !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23475569' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
        background-repeat: no-repeat !important;
        background-position: right 0.65rem center !important;
        background-size: 14px 10px !important;
        border: 1px solid #c5ccd6 !important;
        border-radius: 0.35rem !important;
        cursor: pointer !important;
    }
    .datatable-selector:focus {
        border-color: #0061f2 !important;
        outline: 0;
        box-shadow: 0 0 0 0.25rem rgba(0, 97, 242, 0.25) !important;
    }

    /* Search input - Original compact format matching reference image */
    .datatable-search {
        display: flex !important;
        align-items: center !important;
        margin-bottom: 0 !important;
    }
    .datatable-input {
        display: inline-block !important;
        width: auto !important;
        min-width: 9.25rem !important;
        max-width: 10rem !important;
        height: 2rem !important;
        padding: 6px 12px !important;
        font-size: 0.875rem !important;
        font-weight: 400 !important;
        line-height: 1 !important;
        color: #69707a !important;
        background-color: #ffffff !important;
        border: 1px solid #c5ccd6 !important;
        border-radius: 0.35rem !important;
        box-shadow: none !important;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out !important;
    }
    .datatable-input::-webkit-input-placeholder,
    .datatable-input::-moz-placeholder,
    .datatable-input::placeholder {
        color: #a7aeb8 !important;
        opacity: 1 !important;
    }
    .datatable-input:focus {
        color: #69707a !important;
        background-color: #ffffff !important;
        border-color: #0061f2 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 0.25rem rgba(0, 97, 242, 0.25) !important;
    }

    /* Table Grid: Full table-bordered matching Image 2 */
    .datatable-container {
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.375rem !important;
        overflow-x: auto !important;
    }
    .datatable-table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin-bottom: 0 !important;
    }
    .datatable-table > thead > tr > th {
        background-color: #ffffff !important;
        color: #1e293b !important;
        font-weight: 700 !important;
        font-size: 0.875rem !important;
        padding: 0.85rem 1rem !important;
        border: 1px solid #e2e8f0 !important;
        vertical-align: middle !important;
        white-space: nowrap !important;
    }
    .datatable-table > tbody > tr > td {
        padding: 0.85rem 1rem !important;
        border: 1px solid #e2e8f0 !important;
        vertical-align: middle !important;
        font-size: 0.875rem !important;
        color: #334155 !important;
    }
    .datatable-table > tbody > tr:hover {
        background-color: #f8fafc !important;
    }

    /* Sorter icons */
    .datatable-sorter {
        display: flex !important;
        align-items: center !important;
        justify-content: space-between !important;
        width: 100% !important;
        padding-right: 0 !important;
    }
    .datatable-sorter::before,
    .datatable-sorter::after {
        opacity: 0.3 !important;
    }
    .datatable-ascending .datatable-sorter::after,
    .asc .datatable-sorter::after {
        opacity: 0.8 !important;
    }
    .datatable-descending .datatable-sorter::before,
    .desc .datatable-sorter::before {
        opacity: 0.8 !important;
    }

    /* Bottom footer: Info & Pagination */
    .datatable-bottom {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        padding: 1rem 0 0 0 !important;
        margin-top: 0.5rem !important;
        flex-wrap: wrap !important;
        gap: 0.75rem !important;
    }
    .datatable-info {
        font-size: 0.875rem !important;
        color: #475569 !important;
        margin: 0 !important;
    }
    .datatable-pagination ul {
        margin: 0 !important;
        padding-left: 0 !important;
        display: flex !important;
        list-style: none !important;
        gap: 3px !important;
    }
    .datatable-pagination li {
        list-style: none !important;
    }
    .datatable-pagination a {
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-width: 2.25rem !important;
        height: 2.25rem !important;
        padding: 0 0.6rem !important;
        font-size: 0.875rem !important;
        font-weight: 500 !important;
        color: #0061f2 !important;
        background-color: #ffffff !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.375rem !important;
        text-decoration: none !important;
        transition: all 0.15s ease-in-out !important;
    }
    .datatable-pagination a:hover {
        background-color: #f1f5f9 !important;
        border-color: #cbd5e1 !important;
        color: #004ecc !important;
    }
    .datatable-pagination .datatable-active a,
    .datatable-pagination .active a {
        background-color: #0061f2 !important;
        border-color: #0061f2 !important;
        color: #ffffff !important;
        font-weight: 700 !important;
        cursor: default !important;
    }
    .datatable-pagination .datatable-disabled a,
    .datatable-pagination .disabled a {
        color: #94a3b8 !important;
        background-color: #f8fafc !important;
        border-color: #e2e8f0 !important;
        cursor: not-allowed !important;
        opacity: 0.7 !important;
    }

    /* Type Badge Styling matching Image 2 */
    .badge-doc-type {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        padding: 0.35rem 0.65rem !important;
        font-size: 0.72rem !important;
        font-weight: 600 !important;
        line-height: 1.2 !important;
        border-radius: 6px !important;
        white-space: nowrap !important;
        color: #ffffff !important;
        letter-spacing: 0.2px !important;
    }

    /* Cancelled Badge */
    .badge-cancelled {
        display: inline-flex !important;
        align-items: center !important;
        padding: 0.2rem 0.55rem !important;
        background-color: #fee2e2 !important;
        color: #dc2626 !important;
        border: 1px solid #fecaca !important;
        font-size: 0.72rem !important;
        font-weight: 600 !important;
        border-radius: 9999px !important;
        letter-spacing: 0.2px !important;
    }

    /* Action eye button */
    .btn-datatable.btn-transparent-dark {
        color: #64748b !important;
        background-color: transparent !important;
        border: none !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        width: 2rem !important;
        height: 2rem !important;
        border-radius: 0.35rem !important;
    }
    .btn-datatable.btn-transparent-dark:hover {
        color: #0061f2 !important;
        background-color: #eff6ff !important;
    }
    .btn-datatable.btn-transparent-dark svg {
        width: 1.15rem !important;
        height: 1.15rem !important;
    }
    .jexcel_container {
        width: 100%;
        max-width: 100%;
    }
    .jexcel_content {
        width: 100%;
        max-width: 100%;
        overflow-x: auto;
    }
    .jexcel {
        width: 100% !important;
    }
    .jexcel tbody tr td {
        vertical-align: middle;
    }
    /* GL Actual Template's section-header/subtotal rows (see
       renderGlatSpreadsheet in budget_list.js) — same shading as the add
       page (budget_add_gl_actual_template.html). */
    .jexcel tbody tr.gl-actual-row-header td {
        background-color: #eef2f7;
        font-weight: 600;
    }
    .jexcel tbody tr.gl-actual-row-subtotal td {
        background-color: #f7f3e8;
        font-weight: 600;
    }
    /* jexcel/jsuites' own default cell text color reads as a light gray,
       hard to read against this grid's 181 dense rows — darkened for
       readability, per request. Scoped to this doc type's own grid
       container only (not every other modal's .jexcel grid on this page). */
    #glatDetailTableContainer .jexcel tbody tr td {
        color: #212529 !important;
    }
    /* ---- GLAT modal: table stretches edge-to-edge & full height ---- */
    #glatDocumentDetailModal .modal-body {
        padding-left: 0;
        padding-right: 0;
        padding-top: 1rem;
        padding-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
        overflow: hidden;   /* inner scroll handled by .jexcel_content */
    }
    /* The loading spinner and content wrapper both need to participate
       in the flex layout; content stretches to fill remaining space. */
    #glatDocumentDetailModal #glatModalContent {
        flex: 1 1 0;
        min-height: 0;      /* allow shrinking below content size */
        overflow: hidden;
    }
    #glatDocumentDetailModal #glatModalContent[style*="display: none"] {
        display: none !important;
    }
    #glatDocumentDetailModal #glatModalContent:not([style*="display: none"]) {
        display: flex !important;
        flex-direction: column !important;
    }
    /* Keep horizontal padding for the Document Info section (h6 + table)
       but not for the card wrapping the jexcel grid. */
    #glatDocumentDetailModal .modal-body > #glatModalContent > h6,
    #glatDocumentDetailModal .modal-body > #glatModalContent > .table-responsive {
        padding-left: 1rem;
        padding-right: 1rem;
        flex-shrink: 0;      /* don't collapse the info section */
    }
    #glatDocumentDetailModal .modal-body > #glatModalContent > .table-responsive {
        margin-bottom: 0.75rem !important;
    }
    /* The card wrapping the jexcel table: remove side borders & border radius
       so it touches modal sides, flex stretches down towards footer with clean spacing. */
    #glatDocumentDetailModal .modal-body .card {
        border-left: 0;
        border-right: 0;
        border-radius: 0;
        margin-bottom: 0 !important;
        flex: 1 1 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }
    #glatDocumentDetailModal .modal-body .card > .card-header {
        flex-shrink: 0;
    }
    #glatDocumentDetailModal .modal-body .card > .card-body {
        flex: 1 1 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding: 0;
    }
    /* The jexcel mount point fills the card body. */
    #glatDetailTableContainer {
        flex: 1 1 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }
    /* jexcel's tableOverflow wrapper — fill both width and full available height.
       max-height: none !important overrides jexcel's inline max-height: 300px. */
    #glatDetailTableContainer .jexcel_container {
        width: 100% !important;
        max-width: 100% !important;
        height: 100% !important;
        flex: 1 1 0 !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    #glatDetailTableContainer .jexcel_content {
        width: 100% !important;
        max-width: 100% !important;
        height: 100% !important;
        max-height: none !important;
        flex: 1 1 0 !important;
        min-height: 0 !important;
        display: block !important;
        overflow: auto !important;
    }
    /* Make the jexcel <table> stretch its columns to fill the full
       available width instead of summing fixed pixel widths. */
    #glatDetailTableContainer .jexcel {
        width: 100% !important;
    }
    /* ---- GL / Revenue modal: table stretches edge-to-edge & full height ---- */
    #glDocumentDetailModal .modal-body {
        padding-left: 0;
        padding-right: 0;
        padding-top: 1rem;
        padding-bottom: 1.25rem;
        display: flex;
        flex-direction: column;
        overflow: hidden;   /* inner scroll handled by .jexcel_content */
    }
    #glDocumentDetailModal #glModalContent {
        flex: 1 1 0;
        min-height: 0;      /* allow shrinking below content size */
        overflow: hidden;
    }
    #glDocumentDetailModal #glModalContent[style*="display: none"] {
        display: none !important;
    }
    #glDocumentDetailModal #glModalContent:not([style*="display: none"]) {
        display: flex !important;
        flex-direction: column !important;
    }
    #glDocumentDetailModal .modal-body > #glModalContent > h6,
    #glDocumentDetailModal .modal-body > #glModalContent > .table-responsive {
        padding-left: 1rem;
        padding-right: 1rem;
        flex-shrink: 0;      /* don't collapse the info section */
    }
    #glDocumentDetailModal .modal-body > #glModalContent > .table-responsive {
        margin-bottom: 0.75rem !important;
    }
    #glDocumentDetailModal .modal-body .card {
        border-left: 0;
        border-right: 0;
        border-radius: 0;
        margin-bottom: 0 !important;
        flex: 1 1 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
    }
    #glDocumentDetailModal .modal-body .card > .card-header {
        flex-shrink: 0;
    }
    #glDocumentDetailModal .modal-body .card > .card-body {
        flex: 1 1 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        padding: 0;
    }
    #glDetailTableContainer {
        flex: 1 1 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }
    #glDetailTableContainer[data-doc-type="Budget Plan"] {
        overflow-y: auto !important;
    }
    #glDetailTableContainer:not([data-doc-type="Budget Plan"]) > div {
        flex: 1 1 0;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        width: 100%;
        height: 100%;
    }
    #glDetailTableContainer .jexcel_container {
        width: 100% !important;
        max-width: 100% !important;
        height: 100% !important;
        flex: 1 1 0 !important;
        min-height: 0 !important;
        display: flex !important;
        flex-direction: column !important;
    }
    #glDetailTableContainer .jexcel_content {
        width: 100% !important;
        max-width: 100% !important;
        height: 100% !important;
        max-height: none !important;
        flex: 1 1 0 !important;
        min-height: 0 !important;
        display: block !important;
        overflow: auto !important;
    }
    #glDetailTableContainer[data-doc-type="Budget Plan"] .jexcel_content {
        max-height: 350px !important;
    }
    #glDetailTableContainer .jexcel {
        width: 100% !important;
    }
    /* Distinct from btn-success/bg-success (used by the Medical Equipment
       badge elsewhere in this page) so the approve action never reads as
       the same color as an unrelated document-type label. */
    .btn-approve-doc {
        background-color: #047857 !important;
        border-color: #047857 !important;
        color: #fff !important;
    }
    .btn-approve-doc:hover {
        background-color: #036450 !important;
        border-color: #036450 !important;
        color: #fff !important;
    }
    /* buildCostCenterSelect/buildBranchSelect (budget_list.js) set
       dropdownParent: document.body so the panel escapes .table-responsive's
       forced overflow clipping — but that makes it a sibling of the
       Bootstrap modal in the DOM, not a descendant, so plain DOM order no
       longer puts it on top. TomSelect's default z-index (1000) loses to
       both Bootstrap's modal (1055) and its backdrop (1050), rendering the
       panel completely invisible behind them (not clipped, not empty —
       genuinely covered). 1075 clears both with room to spare.  */
    .ts-dropdown {
        z-index: 1075;
    }
    /* Text/dropdown columns are set to align:'left' on their column
       definition (see budget_list.js / this page's inline <script>) so
       their cell content reads left-aligned — but jexcel ties a column's
       header alignment to the same `align` property, so without this the
       header text would also shift left. jexcel sets header alignment as
       a plain (non-!important) inline style, so this cheaply overrides it
       back to centered without touching the data cells below. */
    .jexcel > thead > tr > td {
        text-align: center !important;
    }
    /* แถว "ยอดรวมแต่ละเดือน" ที่ inject เข้า thead ของตาราง EXPENSE/Revenue
       ใน popup รายละเอียดเอกสาร (ดู injectGlEntryMonthlyTotalsRow ใน
       budget_list.js) — คัดลอกมาจาก budget_add_revenue.html/
       budget_add_gl_entry.html ให้หน้าตาเหมือนกัน (top/left ตั้ง inline
       จาก JS ตามขนาดจริงของหัวคอลัมน์/คอลัมน์ GL+รายละเอียด) */
    .monthly-totals-row td {
        position: sticky;
        z-index: 2;
        background-color: #eef6ff;
        font-weight: 600;
        text-align: center !important;
        border-top: 2px solid #b6d4fe;
    }
    .monthly-totals-row td.monthly-totals-label {
        /* !important จำเป็นจริง — ตรวจสอบด้วย headless browser จริงแล้วว่า
           ถ้าไม่ใส่ z-index: 3 จะไม่ชนะ jexcel.css's blanket thead rule
           (.jexcel > thead > tr > td { z-index: 2 }) ทั้งที่ selector นี้มี
           specificity สูงกว่าตามทฤษฎี — ผลคือ cell เดือนที่เลื่อนผ่านเข้ามา
           paint ทับ label (z-index เท่ากันตัดสินด้วยลำดับ DOM แทน) ทำให้
           ข้อความ "ยอดรวมแต่ละเดือน" ถูกคอลัมน์เดือนที่กำลังเลื่อนบังเวลา scroll */
        z-index: 3 !important;
        text-align: left !important;
        padding-left: 10px;
        color: #0061f2;
    }
</style>
@endsection

@section('content')
<header class="page-header page-header-dark bg-gradient-primary-to-secondary pb-10">
    <div class="container-fluid px-4">
        <div class="page-header-content pt-4">
            <div class="row align-items-center justify-content-between">
                <div class="col-auto mt-4">
                    <h1 class="page-header-title">
                        <div class="page-header-icon"><i data-feather="file-text"></i></div>
                        Budget List
                    </h1>
                    <div class="page-header-subtitle">E-Budget Application</div>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="container-fluid px-4 mt-n10">
    <div class="row">
        @php
            $cardColors = ['warning', 'success', 'danger', 'primary', 'info', 'secondary'];
        @endphp
        @forelse($categories as $index => $category)
        @php
            $card_color = $cardColors[$index % count($cardColors)];
            $catCode = is_array($category) ? ($category['category_code'] ?? '') : ($category->category_code ?? '');
            $catName = is_array($category) ? ($category['category_name'] ?? '') : ($category->category_name ?? '');
        @endphp
        <div class="col-lg-4 mb-4">
            <a href="javascript:void(0);" onclick="fetchDocuments('{{ $catCode }}')" class="text-decoration-none">
                <div class="card h-100 border-start-lg border-start-{{ $card_color }} card-lift shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small fw-bold text-{{ $card_color }} text-uppercase mb-1">{{ $catCode }}</div>
                                <div class="h4 mb-0 fw-bold text-dark">{{ $catName }}</div>
                            </div>
                            <div class="text-{{ $card_color }} opacity-50">
                                <i data-feather="folder" style="width: 2em; height: 2em;"></i>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between small shadow-none bg-transparent">
                        <span class="text-{{ $card_color }}">View Budgets</span>
                        <div class="text-{{ $card_color }}"><i data-feather="arrow-right"></i></div>
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-12">
            <div class="alert alert-warning">No Categories Found.</div>
        </div>
        @endforelse
    </div>

    <!-- Table placeholder for selected category -->
    <div class="card mb-4 card-soft">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span id="tableTitle" class="fw-bold text-primary">Budget Entries</span>
            <div class="d-flex flex-wrap gap-2">
            <select id="docTypeFilter" class="form-select form-select-sm w-auto" style="display: none; cursor: pointer;" onchange="applyFilters()">
                <option value="ALL">ทั้งหมด (All Types)</option>
                <option value="VET">VET Manpower</option>
                <option value="NON VET">NON VET Manpower</option>
                <option value="Position Adjustment">Position Adjustment</option>
                <option value="Medical Equipment">Medical Equipment</option>
                <option value="Computer Equipment">Computer Equipment</option>
                <option value="Furniture">Furniture</option>
                <option value="Tools & Equipment">Tools & Equipment</option>
                <option value="CAR">CAR</option>
                <option value="Renovate">Renovate</option>
                <option value="Software">Software</option>
                <option value="GL Entry">EXPENSE</option>
                <option value="Revenue">Revenue</option>
                <option value="GL Actual Template">GL Actual Template</option>
            </select>
            @if($is_privileged_user || $is_view_only)
            <select id="filterCreateName" class="form-select form-select-sm w-auto" style="display: none; cursor: pointer;" onchange="applyFilters()">
                <option value="ALL">ผู้สร้างทั้งหมด</option>
            </select>
            <select id="filterBranch" class="form-select form-select-sm w-auto" style="display: none; cursor: pointer;" onchange="applyFilters()">
                <option value="ALL">ทุกสาขา</option>
            </select>
            <select id="filterCostCenter" class="form-select form-select-sm w-auto" style="display: none; cursor: pointer;" onchange="applyFilters()">
                <option value="ALL">ทุก Cost Center</option>
            </select>
            @endif
            </div>
        </div>
        <div class="card-body">
            <p class="text-muted" id="placeholderText">Select a category card above to view entries here.</p>
            
            <div id="loadingSpinner" style="display: none;" class="text-center py-5">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-2 text-muted">กำลังโหลดข้อมูล...</div>
            </div>

            <div id="tableContainer" style="display: none;">
                <table id="datatablesSimple" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Document No</th>
                            <th>Type</th>
                            <th>Branch</th>
                            <th>Budget Year</th>
                            <th>Total Budget</th>
                            <th>Date Created</th>
                            <th>Created By</th>
                            <!-- <th>สถานะ</th> -->
                            <th class="text-center text-nowrap" style="width: 80px;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Document Detail Modal -->
    <div class="modal fade" id="documentDetailModal" tabindex="-1" aria-labelledby="documentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="documentDetailModalLabel">รายละเอียดเอกสาร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="modalLoadingSpinner" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2 text-muted">กำลังโหลดข้อมูล...</div>
                    </div>
                    <div id="modalContent" style="display: none;">
                        <h6 class="text-primary mb-3"><i data-feather="file-text" class="me-2"></i>ข้อมูลเอกสาร (Document Info)</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>เลขที่เอกสาร</th>
                                        <th>ประเภท</th>
                                        <th>รหัสสาขา</th>
                                        <th>Cost Center</th>
                                        <th>ปีงบประมาณ</th>
                                        <th>ผู้สร้าง</th>
                                        <th>วันที่สร้าง</th>
                                        <th id="modalDocStatusTh" style="display: none;">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="modalDocNo"></td>
                                        <td id="modalDocType"></td>
                                        <td id="modalDocBranch"></td>
                                        <td id="modalDocCostCenter"></td>
                                        <td id="modalDocBudgetYear"></td>
                                        <td id="modalDocCreator"></td>
                                        <td id="modalDocDate"></td>
                                        <td id="modalDocStatus" style="display: none;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="modalCancelReasonAlert" class="alert alert-danger py-2 px-3 mb-3" style="display: none;">
                            <i data-feather="alert-circle" class="me-2"></i><strong>เหตุผลการยกเลิก:</strong> <span id="modalCancelReasonText"></span>
                        </div>

                        <h6 class="text-primary mb-3"><i data-feather="users" class="me-2"></i>รายละเอียดกำลังคน (Manpower Details)</h6>
                        <div class="card mb-3 card-soft">
                            <div class="card-header bg-light fw-bold text-dark d-flex justify-content-between align-items-center">
                                <span id="modalTable1HeaderTitle">ตารางส่วนแรก (จำนวนคน)</span>
                                <button type="button" class="btn btn-sm btn-primary" id="btnAddRow" style="display: none;" onclick="addSpreadsheetRow()">
                                    <i data-feather="plus" class="me-1"></i> เพิ่มรายการ
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div id="modalSpreadsheet1"></div>
                            </div>
                        </div>
                        <div class="card mb-3 card-soft">
                            <div class="card-header bg-light fw-bold text-dark" id="modalTable2HeaderTitle">ตารางส่วนที่สอง (คำนวณงบประมาณ)</div>
                            <div class="card-body p-0">
                                <div id="modalSpreadsheet2"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto d-flex gap-2">
                        @if($can_edit_own_documents)
                        <button type="button" class="btn btn-outline-danger" id="btnCancelDocument" onclick="cancelDocument()">
                            <i data-feather="x-circle" class="me-1"></i> ยกเลิกรายการ
                        </button>
                        @endif
                    </div>
                    @if($can_edit_own_documents)
                    <button type="button" class="btn btn-primary" id="btnEditMode" onclick="toggleEditMode()">
                        <i data-feather="edit-2" class="me-1"></i> แก้ไขข้อมูล
                    </button>
                    <button type="button" class="btn btn-success" id="btnSaveDocument" style="display: none;" onclick="saveDocument()">
                        <i data-feather="save" class="me-1"></i> บันทึกข้อมูล
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary" id="btnCloseModal" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Position Adjustment Detail Modal -->
    <div class="modal fade" id="adjustmentDetailModal" tabindex="-1" aria-labelledby="adjustmentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="adjustmentDetailModalLabel">รายละเอียดเอกสาร (Position Adjustment)</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="adjModalLoadingSpinner" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2 text-muted">กำลังโหลดข้อมูล...</div>
                    </div>
                    <div id="adjModalContent" style="display: none;">
                        <h6 class="text-primary mb-3"><i data-feather="file-text" class="me-2"></i>ข้อมูลเอกสาร (Document Info)</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>เลขที่เอกสาร</th>
                                        <th>ประเภท</th>
                                        <th>รหัสสาขา</th>
                                        <th>Cost Center</th>
                                        <th>ปีงบประมาณ</th>
                                        <th>ผู้สร้าง</th>
                                        <th>วันที่สร้าง</th>
                                        <th id="adjModalDocStatusTh" style="display: none;">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="adjModalDocNo"></td>
                                        <td id="adjModalDocType"><span class="badge bg-info">Position Adjustment</span></td>
                                        <td id="adjModalDocBranch"></td>
                                        <td id="adjModalDocCostCenter"></td>
                                        <td id="adjModalDocBudgetYear"></td>
                                        <td id="adjModalDocCreator"></td>
                                        <td id="adjModalDocDate"></td>
                                        <td id="adjModalDocStatus" style="display: none;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="adjModalCancelReasonAlert" class="alert alert-danger py-2 px-3 mb-3" style="display: none;">
                            <i data-feather="alert-circle" class="me-2"></i><strong>เหตุผลการยกเลิก:</strong> <span id="adjModalCancelReasonText"></span>
                        </div>

                        <h6 class="text-primary mb-3"><i data-feather="users" class="me-2"></i>รายละเอียดกำลังคน (Manpower Details)</h6>
                        <!-- Table 1: Headcount -->
                        <div class="card mb-3 card-soft">
                            <div class="card-header bg-light fw-bold text-dark d-flex justify-content-between align-items-center">
                                <span>ตารางที่ 1 (จำนวนคน)</span>
                                <button type="button" class="btn btn-sm btn-primary" id="btnAdjAddRow" style="display: none;" onclick="addAdjSpreadsheetRow()">
                                    <i data-feather="plus" class="me-1"></i> เพิ่มรายการ
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div id="adjModalSpreadsheet1"></div>
                            </div>
                        </div>

                        <!-- Table 2: Salary Diff -->
                        <div class="card mb-3 card-soft">
                            <div class="card-header bg-light fw-bold text-success">ตารางที่ 2 (งบประมาณผลต่างเงินเดือน)</div>
                            <div class="card-body p-0">
                                <div id="adjModalSpreadsheet2"></div>
                            </div>
                        </div>

                        <!-- Table 3: Allowance Diff -->
                        <div class="card mb-3 card-soft">
                            <div class="card-header bg-light fw-bold text-primary">ตารางที่ 3 (งบประมาณผลต่างค่าวัดระดับ/ค่าตำแหน่ง)</div>
                            <div class="card-body p-0">
                                <div id="adjModalSpreadsheet3"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto d-flex gap-2">
                        @if($can_edit_own_documents)
                        <button type="button" class="btn btn-outline-danger" id="btnAdjCancelDocument" onclick="cancelAdjDocument()">
                            <i data-feather="x-circle" class="me-1"></i> ยกเลิกรายการ
                        </button>
                        @endif
                    </div>
                    @if($can_edit_own_documents)
                    <button type="button" class="btn btn-primary text-white" id="btnAdjEditMode" onclick="toggleAdjEditMode()">
                        <i data-feather="edit-2" class="me-1"></i> แก้ไขข้อมูล
                    </button>
                    <button type="button" class="btn btn-success" id="btnAdjSaveDocument" style="display: none;" onclick="saveAdjDocument()">
                        <i data-feather="save" class="me-1"></i> บันทึกข้อมูล
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary" id="btnAdjCloseModal" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- General Ledger Document Detail Modal (GL Entry / Budget Plan — read-only) -->
    <div class="modal fade" id="glDocumentDetailModal" tabindex="-1" aria-labelledby="glDocumentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="glDocumentDetailModalLabel">รายละเอียดเอกสาร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="glModalLoadingSpinner" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2 text-muted">กำลังโหลดข้อมูล...</div>
                    </div>
                    <div id="glModalContent" style="display: none;">
                        <h6 class="text-primary mb-3"><i data-feather="file-text" class="me-2"></i>ข้อมูลเอกสาร (Document Info)</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>เลขที่เอกสาร</th>
                                        <th>ประเภท</th>
                                        <th>รหัสสาขา</th>
                                        <th>Cost Center</th>
                                        <th>ปีงบประมาณ</th>
                                        <th>ผู้สร้าง</th>
                                        <th>วันที่สร้าง</th>
                                        <th id="glModalDocStatusTh" style="display: none;">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="glModalDocNo"></td>
                                        <td id="glModalDocType"></td>
                                        <td id="glModalDocBranch"></td>
                                        <td id="glModalDocCostCenter"></td>
                                        <td id="glModalDocBudgetYear"></td>
                                        <td id="glModalDocCreator"></td>
                                        <td id="glModalDocDate"></td>
                                        <td id="glModalDocStatus" style="display: none;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="glModalCancelReasonAlert" class="alert alert-danger py-2 px-3 mb-3" style="display: none;">
                            <i data-feather="alert-circle" class="me-2"></i><strong>เหตุผลการยกเลิก:</strong> <span id="glModalCancelReasonText"></span>
                        </div>

                        <h6 class="text-primary mb-3"><i data-feather="list" class="me-2"></i>รายการ (Line Items)</h6>
                        <div class="card mb-3 card-soft">
                            <div class="card-header bg-light fw-bold text-dark d-flex justify-content-between align-items-center">
                                <span>ตารางรายการ</span>
                                <button type="button" class="btn btn-sm btn-primary" id="btnGlAddRow" style="display: none;" onclick="addGlSpreadsheetRow()">
                                    <i data-feather="plus" class="me-1"></i> เพิ่มรายการ
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div id="glDetailTableContainer"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto d-flex gap-2">
                        @if($can_edit_own_documents)
                        <button type="button" class="btn btn-outline-danger" id="btnGlCancelDocument" onclick="cancelGlDocument()">
                            <i data-feather="x-circle" class="me-1"></i> ยกเลิกรายการ
                        </button>
                        @endif
                    </div>
                    @if($can_edit_own_documents)
                    <button type="button" class="btn btn-primary" id="btnGlEditMode" onclick="toggleGlEditMode()">
                        <i data-feather="edit-2" class="me-1"></i> แก้ไขข้อมูล
                    </button>
                    <button type="button" class="btn btn-success" id="btnGlSaveDocument" style="display: none;" onclick="saveGlDocument()">
                        <i data-feather="save" class="me-1"></i> บันทึกข้อมูล
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary" id="btnGlCloseModal" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- GL Actual Template Document Detail Modal — fixed 181-row outline,
         5 plain numeric columns, no Cost Center, no monthly breakdown. -->
    <div class="modal fade" id="glatDocumentDetailModal" tabindex="-1" aria-labelledby="glatDocumentDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="glatDocumentDetailModalLabel">รายละเอียดเอกสาร</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="glatModalLoadingSpinner" class="text-center py-5" style="display: none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <div class="mt-2 text-muted">กำลังโหลดข้อมูล...</div>
                    </div>
                    <div id="glatModalContent" style="display: none;">
                        <h6 class="text-primary mb-3"><i data-feather="file-text" class="me-2"></i>ข้อมูลเอกสาร (Document Info)</h6>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered table-sm">
                                <thead class="table-light">
                                    <tr>
                                        <th>เลขที่เอกสาร</th>
                                        <th>ประเภท</th>
                                        <th>สาขา</th>
                                        <th>ปีงบประมาณ</th>
                                        <th>ปีที่ทำ Budget</th>
                                        <th>จำนวนเดือนข้อมูลจริง</th>
                                        <th>% การโต / 2 / 3</th>
                                        <th>ผู้สร้าง</th>
                                        <th>วันที่สร้าง</th>
                                        <th id="glatModalDocStatusTh" style="display: none;">สถานะ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td id="glatModalDocNo"></td>
                                        <td><span class="badge" style="background-color: #6f42c1;">GL Actual Template</span></td>
                                        <td id="glatModalDocBranch"></td>
                                        <td id="glatModalActualYear"></td>
                                        <td id="glatModalDocBudgetYear"></td>
                                        <td id="glatModalMonthCount"></td>
                                        <td id="glatModalGrowthPct"></td>
                                        <td id="glatModalDocCreator"></td>
                                        <td id="glatModalDocDate"></td>
                                        <td id="glatModalDocStatus" style="display: none;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div id="glatModalCancelReasonAlert" class="alert alert-danger py-2 px-3 mb-3" style="display: none;">
                            <i data-feather="alert-circle" class="me-2"></i><strong>เหตุผลการยกเลิก:</strong> <span id="glatModalCancelReasonText"></span>
                        </div>

                        <h6 class="text-primary mb-3"><i data-feather="list" class="me-2"></i>รายการ (Line Items)</h6>
                        <div class="card mb-3 card-soft">
                            <div class="card-header bg-light fw-bold text-dark">ตารางรายการ (181 รายการคงที่)</div>
                            <div class="card-body p-0">
                                <div id="glatDetailTableContainer"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="me-auto d-flex gap-2">
                        @if($can_edit_own_documents)
                        <button type="button" class="btn btn-outline-danger" id="btnGlatCancelDocument" onclick="cancelGlatDocument()">
                            <i data-feather="x-circle" class="me-1"></i> ยกเลิกรายการ
                        </button>
                        @endif
                    </div>
                    @if($can_edit_own_documents)
                    <button type="button" class="btn btn-primary" id="btnGlatEditMode" onclick="toggleGlatEditMode()">
                        <i data-feather="edit-2" class="me-1"></i> แก้ไขข้อมูล
                    </button>
                    <button type="button" class="btn btn-success" id="btnGlatSaveDocument" style="display: none;" onclick="saveGlatDocument()">
                        <i data-feather="save" class="me-1"></i> บันทึกข้อมูล
                    </button>
                    @endif
                    <button type="button" class="btn btn-secondary" id="btnGlatCloseModal" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
<script src="{{ asset('static/budget_app/vendor/jexcel/jexcel.js') }}"></script>
<script src="{{ asset('static/budget_app/vendor/jsuites/jsuites.js') }}"></script>
<script defer src="{{ asset('static/budget_app/vendor/tom-select/tom-select.complete.min.js') }}"></script>

<script id="vet-items-data" type="application/json">{!! json_encode($items_vet_json ?? []) !!}</script>
<script id="nonvet-items-data" type="application/json">{!! json_encode($items_non_vet_json ?? []) !!}</script>
<script id="med-items-data" type="application/json">{!! json_encode($items_med_json ?? []) !!}</script>
<script id="comp-items-data" type="application/json">{!! json_encode($items_comp_json ?? []) !!}</script>
<script id="furniture-items-data" type="application/json">{!! json_encode($items_furniture_json ?? []) !!}</script>
<script id="tools-items-data" type="application/json">{!! json_encode($items_tools_json ?? []) !!}</script>
<script id="car-items-data" type="application/json">{!! json_encode($items_car_json ?? []) !!}</script>
<script id="renovate-items-data" type="application/json">{!! json_encode($items_renovate_json ?? []) !!}</script>
<script id="software-items-data" type="application/json">{!! json_encode($items_software_json ?? []) !!}</script>
<script id="cost-centers-data" type="application/json">{!! json_encode($cost_centers_json ?? []) !!}</script>
<script id="branch-options-data" type="application/json">{!! json_encode($branch_options_json ?? []) !!}</script>
<script id="glat-template-rows-data" type="application/json">{!! json_encode($glat_template_rows_json ?? []) !!}</script>
<script id="glat-branches-data" type="application/json">{!! json_encode($glat_branches_json ?? []) !!}</script>

<script>
    window.APP_CONFIG = {
        urls: {
            apiGetDocuments: "{{ route('api_get_documents', 'DUMMY') }}",
            apiDocumentDetail: "{{ route('api_document_detail', ['TYPE', 'DOCNO']) }}",
            apiDocumentUpdate: "{{ route('api_document_update', ['TYPE', 'DOCNO']) }}",
            apiDocumentApprove: "{{ route('api_document_approve', ['TYPE', 'DOCNO']) }}",
            apiDocumentCancel: "{{ route('api_document_cancel', ['TYPE', 'DOCNO']) }}",
            apiAdjustmentDetail: "{{ route('api_document_detail', ['Position Adjustment', 'DOCNO']) }}",
            apiAdjustmentUpdate: "{{ route('api_document_update', ['Position Adjustment', 'DOCNO']) }}",
            apiAdjustmentApprove: "{{ route('api_document_approve', ['Position Adjustment', 'DOCNO']) }}",
            apiAdjustmentCancel: "{{ route('api_document_cancel', ['Position Adjustment', 'DOCNO']) }}",
            apiGeneralLedgers: "{{ route('api_general_ledgers') }}"
        },
        csrfToken: '{{ csrf_token() }}',
        needsBranchSelection: {{ ($needs_branch_selection ?? false) ? 'true' : 'false' }}
    };
</script>
<script src="{{ asset('static/budget_app/js/budget_list.js') }}?v=20261120_04"></script>
@endsection
