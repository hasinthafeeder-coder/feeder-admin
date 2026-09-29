@extends('layout_main.app')

@section('content')
    <div class="main-content-container overflow-hidden barcode-test-controls">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Product Barcode Test Sheet</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Product Barcode Test Sheet</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white rounded-10 border border-white mb-4">
            <div class="p-20">
                <form id="barcodeTestSheetForm" class="row g-3 align-items-end" autocomplete="off">
                    <div class="col-12 col-lg-6 position-relative">
                        <label for="supplierSearch" class="label fs-14 mb-2">Supplier</label>
                        <input type="text" class="form-control" id="supplierSearch"
                            placeholder="Search supplier by company or name" aria-autocomplete="list"
                            aria-controls="supplierResults" aria-expanded="false">
                        <input type="hidden" id="supplierUuid" value="">
                        <div id="supplierResults" class="barcode-supplier-results d-none" role="listbox"></div>
                    </div>
                    <div class="col-12 col-lg-6 d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary text-white" id="loadBarcodesButton" disabled>
                            Generate / Load Barcodes
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="printBarcodesButton" disabled>
                            Print Barcodes
                        </button>
                    </div>
                </form>
                <p class="text-muted fs-14 mb-0 mt-3" id="barcodeSheetStatus">Select one supplier, then load barcodes.</p>
            </div>
        </div>
    </div>

    <div id="barcodeSheet" class="barcode-sheet" hidden></div>
@endsection

@push('styles')
    <style>
        .barcode-supplier-results {
            position: absolute;
            z-index: 20;
            left: 12px;
            right: 12px;
            top: calc(100% - 8px);
            max-height: 240px;
            overflow: auto;
            background: #fff;
            border: 1px solid #d7dce3;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08);
        }

        .barcode-supplier-results button {
            display: block;
            width: 100%;
            text-align: left;
            background: #fff;
            color: #111;
            border: 0;
            border-bottom: 1px solid #eef1f4;
            padding: 10px 12px;
        }

        .barcode-supplier-results button:hover,
        .barcode-supplier-results button.is-active {
            background: #f4f6f8;
        }

        .barcode-sheet {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8mm;
            background: #fff;
            color: #000;
        }

        .barcode-card {
            break-inside: avoid;
            page-break-inside: avoid;
            background: #fff;
            color: #000;
            border: 1px solid #000;
            padding: 4mm 5mm 5mm;
            box-sizing: border-box;
        }

        .barcode-card-product {
            margin: 0 0 1mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            font-weight: 700;
            line-height: 1.25;
            color: #000;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .barcode-card-variant {
            margin: 0 0 3mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.25;
            color: #000;
            overflow-wrap: anywhere;
            word-break: break-word;
        }

        .barcode-card-graphic {
            display: flex;
            justify-content: center;
            align-items: center;
            background: #fff;
            padding: 4mm 6mm;
            margin: 0;
        }

        .barcode-card-graphic svg {
            display: block;
            width: auto;
            max-width: 100%;
            height: auto;
            background: #fff;
        }

        .barcode-card-value {
            margin: 1mm 0 0;
            text-align: center;
            font-family: "Courier New", Courier, monospace;
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 0.5px;
            line-height: 1.2;
            color: #000;
            background: #fff;
            overflow-wrap: anywhere;
        }

        .barcode-sheet-empty {
            grid-column: 1 / -1;
            border: 1px solid #000;
            background: #fff;
            color: #000;
            padding: 8mm;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            font-weight: 700;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }

        @media print {
            .barcode-test-controls,
            .sidebar-area,
            .header-area,
            .footer-area,
            .main-content > .rounded-10:last-child {
                display: none !important;
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/supplier-code128.js') }}"></script>
    <script>
        (function () {
            const searchUrl = @json(route('products.barcode-test-sheet.suppliers'));
            const barcodesUrl = @json(route('products.barcode-test-sheet.barcodes'));
            const searchInput = document.getElementById('supplierSearch');
            const supplierUuid = document.getElementById('supplierUuid');
            const results = document.getElementById('supplierResults');
            const form = document.getElementById('barcodeTestSheetForm');
            const loadButton = document.getElementById('loadBarcodesButton');
            const printButton = document.getElementById('printBarcodesButton');
            const status = document.getElementById('barcodeSheetStatus');
            const sheet = document.getElementById('barcodeSheet');
            let suppliers = [];
            let activeIndex = -1;
            let searchTimer = null;

            function escapeHtml(value) {
                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;');
            }

            function setStatus(message) {
                status.textContent = message;
            }

            function selectedSupplier() {
                return suppliers.find(function (supplier) {
                    return supplier.uuid === supplierUuid.value;
                }) || null;
            }

            function renderResults() {
                results.innerHTML = '';
                if (suppliers.length === 0) {
                    results.classList.add('d-none');
                    searchInput.setAttribute('aria-expanded', 'false');
                    return;
                }

                suppliers.forEach(function (supplier, index) {
                    const button = document.createElement('button');
                    button.type = 'button';
                    button.setAttribute('role', 'option');
                    button.dataset.uuid = supplier.uuid;
                    button.textContent = supplier.label;
                    if (index === activeIndex) {
                        button.classList.add('is-active');
                    }
                    button.addEventListener('click', function () {
                        chooseSupplier(supplier);
                    });
                    results.appendChild(button);
                });

                results.classList.remove('d-none');
                searchInput.setAttribute('aria-expanded', 'true');
            }

            function chooseSupplier(supplier) {
                supplierUuid.value = supplier.uuid;
                searchInput.value = supplier.label;
                loadButton.disabled = false;
                printButton.disabled = true;
                sheet.hidden = true;
                sheet.innerHTML = '';
                results.classList.add('d-none');
                searchInput.setAttribute('aria-expanded', 'false');
                setStatus('Supplier selected. Load barcodes to build the sheet.');
            }

            function searchSuppliers(term) {
                const url = new URL(searchUrl, window.location.origin);
                url.searchParams.set('q', term);

                fetch(url.toString(), {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Supplier search failed.');
                        }
                        return response.json();
                    })
                    .then(function (payload) {
                        suppliers = Array.isArray(payload.suppliers) ? payload.suppliers : [];
                        activeIndex = suppliers.length > 0 ? 0 : -1;
                        renderResults();
                    })
                    .catch(function () {
                        suppliers = [];
                        renderResults();
                        setStatus('Supplier search failed.');
                    });
            }

            function barcodeMarkup(value) {
                if (window.SupplierCode128 && typeof window.SupplierCode128.toSvg === 'function') {
                    return window.SupplierCode128.toSvg(String(value), {
                        height: 110,
                        moduleWidth: 2,
                        showText: false
                    });
                }

                return '';
            }

            function renderSheet(payload) {
                const rows = Array.isArray(payload.barcodes) ? payload.barcodes : [];
                sheet.innerHTML = '';

                if (rows.length === 0) {
                    sheet.hidden = false;
                    sheet.innerHTML = '<div class="barcode-sheet-empty">No product barcodes found for this supplier.</div>';
                    printButton.disabled = true;
                    setStatus('No product barcodes found for this supplier.');
                    return;
                }

                rows.forEach(function (row) {
                    const card = document.createElement('article');
                    card.className = 'barcode-card';
                    const variant = String(row.variant_name || '').trim();
                    const variantHtml = variant !== ''
                        ? '<p class="barcode-card-variant">Variant: ' + escapeHtml(variant) + '</p>'
                        : '';

                    card.innerHTML =
                        '<p class="barcode-card-product">' + escapeHtml(row.product_name || '') + '</p>' +
                        variantHtml +
                        '<div class="barcode-card-graphic">' + barcodeMarkup(row.barcode) + '</div>' +
                        '<p class="barcode-card-value">' + escapeHtml(row.barcode || '') + '</p>';
                    sheet.appendChild(card);
                });

                sheet.hidden = false;
                printButton.disabled = false;
                setStatus(rows.length + ' barcode' + (rows.length === 1 ? '' : 's') + ' ready to print.');
            }

            function printStyles() {
                return `
                    @page { size: A4 portrait; margin: 10mm; }
                    * { box-sizing: border-box; }
                    html, body { margin: 0; padding: 0; background: #fff; color: #000; }
                    .barcode-sheet {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 8mm;
                        background: #fff;
                        color: #000;
                    }
                    .barcode-card {
                        break-inside: avoid;
                        page-break-inside: avoid;
                        background: #fff;
                        color: #000;
                        border: 1px solid #000;
                        padding: 4mm 5mm 5mm;
                    }
                    .barcode-card-product {
                        margin: 0 0 1mm;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 12px;
                        font-weight: 700;
                        line-height: 1.25;
                        overflow-wrap: anywhere;
                        word-break: break-word;
                    }
                    .barcode-card-variant {
                        margin: 0 0 3mm;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 11px;
                        font-weight: 600;
                        line-height: 1.25;
                        overflow-wrap: anywhere;
                        word-break: break-word;
                    }
                    .barcode-card-graphic {
                        display: flex;
                        justify-content: center;
                        background: #fff;
                        padding: 4mm 6mm;
                    }
                    .barcode-card-graphic svg {
                        display: block;
                        width: auto;
                        max-width: 100%;
                        height: auto;
                        background: #fff;
                    }
                    .barcode-card-value {
                        margin: 1mm 0 0;
                        text-align: center;
                        font-family: "Courier New", Courier, monospace;
                        font-size: 16px;
                        font-weight: 700;
                        letter-spacing: 0.5px;
                        color: #000;
                        background: #fff;
                        overflow-wrap: anywhere;
                    }
                    .barcode-sheet-empty {
                        grid-column: 1 / -1;
                        font-family: Arial, Helvetica, sans-serif;
                        font-size: 14px;
                        font-weight: 700;
                    }
                `;
            }

            function printSheet() {
                const iframe = document.createElement('iframe');
                iframe.setAttribute('title', 'Barcode test sheet print');
                iframe.setAttribute('aria-hidden', 'true');
                iframe.style.cssText = 'position:fixed;right:0;bottom:0;width:0;height:0;border:0;';
                document.body.appendChild(iframe);

                const frameWindow = iframe.contentWindow;
                const frameDocument = frameWindow.document;
                frameDocument.open();
                frameDocument.write('<!DOCTYPE html><html><head><title>Product Barcode Test Sheet</title><style>' +
                    printStyles() + '</style></head><body>' + sheet.outerHTML + '</body></html>');
                frameDocument.close();

                const runPrint = function () {
                    frameWindow.focus();
                    frameWindow.print();
                    window.setTimeout(function () {
                        iframe.remove();
                    }, 1000);
                };

                window.setTimeout(runPrint, 150);
            }

            searchInput.addEventListener('focus', function () {
                if (supplierUuid.value !== '') {
                    return;
                }
                searchSuppliers(searchInput.value.trim());
            });

            searchInput.addEventListener('input', function () {
                supplierUuid.value = '';
                loadButton.disabled = true;
                printButton.disabled = true;
                window.clearTimeout(searchTimer);
                searchTimer = window.setTimeout(function () {
                    searchSuppliers(searchInput.value.trim());
                }, 200);
            });

            searchInput.addEventListener('keydown', function (event) {
                if (results.classList.contains('d-none') || suppliers.length === 0) {
                    return;
                }

                if (event.key === 'ArrowDown') {
                    event.preventDefault();
                    activeIndex = Math.min(activeIndex + 1, suppliers.length - 1);
                    renderResults();
                } else if (event.key === 'ArrowUp') {
                    event.preventDefault();
                    activeIndex = Math.max(activeIndex - 1, 0);
                    renderResults();
                } else if (event.key === 'Enter' && activeIndex >= 0) {
                    event.preventDefault();
                    chooseSupplier(suppliers[activeIndex]);
                } else if (event.key === 'Escape') {
                    results.classList.add('d-none');
                    searchInput.setAttribute('aria-expanded', 'false');
                }
            });

            document.addEventListener('click', function (event) {
                if (!results.contains(event.target) && event.target !== searchInput) {
                    results.classList.add('d-none');
                    searchInput.setAttribute('aria-expanded', 'false');
                }
            });

            form.addEventListener('submit', function (event) {
                event.preventDefault();
                if (!supplierUuid.value) {
                    setStatus('Select one supplier before loading barcodes.');
                    return;
                }

                loadButton.disabled = true;
                setStatus('Loading barcodes...');

                const url = new URL(barcodesUrl, window.location.origin);
                url.searchParams.set('supplier', supplierUuid.value);

                fetch(url.toString(), {
                    headers: { 'Accept': 'application/json' },
                    credentials: 'same-origin'
                })
                    .then(function (response) {
                        if (!response.ok) {
                            throw new Error('Unable to load barcodes.');
                        }
                        return response.json();
                    })
                    .then(function (payload) {
                        renderSheet(payload);
                    })
                    .catch(function () {
                        sheet.hidden = true;
                        sheet.innerHTML = '';
                        printButton.disabled = true;
                        setStatus('Unable to load barcodes for this supplier.');
                    })
                    .finally(function () {
                        loadButton.disabled = supplierUuid.value === '';
                    });
            });

            printButton.addEventListener('click', function () {
                if (sheet.hidden || sheet.childElementCount === 0) {
                    return;
                }
                printSheet();
            });
        })();
    </script>
@endpush
