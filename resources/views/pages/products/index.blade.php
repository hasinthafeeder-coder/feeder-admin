@extends('layout_main.app')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">Create Product</h3>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb align-items-center mb-0 lh-1">
                    <li class="breadcrumb-item">
                        <a href="index.html" class="d-flex align-items-center text-decoration-none">
                            <i class="ri-home-8-line fs-15 text-primary me-1"></i>
                            <span class="text-body fs-14 hover">Dashboard</span>
                        </a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span>E-Commerce</span>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <span class="text-secondary">Create Product</span>
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card bg-white p-20 rounded-10 border border-white mb-4">
            <form>
                <div class="row g-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-20">
                            <h3 class="mb-0">Product Information</h3>
                            <span
                                class="badge bg-primary-subtle text-primary border border-primary border-opacity-10">Draft</span>
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-12">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Product Name / Title</label>
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="productTitle"
                                            placeholder="Product Name / Title">
                                        <label for="productTitle">Product Name / Title</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Product Category</label>
                                    <div class="form-floating">
                                        <select class="form-select form-control" id="productCategory"
                                            aria-label="Product category">
                                            <option selected>Select category</option>
                                            <optgroup label="Electronics">
                                                <option value="electronics-smartphones">Smartphones</option>
                                                <option value="electronics-laptops">Laptops</option>
                                                <option value="electronics-audio">Audio & Video</option>
                                            </optgroup>
                                            <optgroup label="Fashion">
                                                <option value="fashion-women">Women</option>
                                                <option value="fashion-men">Men</option>
                                                <option value="fashion-accessories">Accessories</option>
                                            </optgroup>
                                            <optgroup label="Home & Living">
                                                <option value="home-kitchen">Kitchen</option>
                                                <option value="home-decor">Decor</option>
                                                <option value="home-bedding">Bedding</option>
                                            </optgroup>
                                        </select>
                                        <label for="productCategory">Select category</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="form-group mb-0">
                                    <label class="label fs-16 mb-2">Description</label>
                                    <ul class="nav nav-tabs nav-tabs-separator" id="descriptionTabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="lang-en-tab" data-bs-toggle="tab"
                                                data-bs-target="#lang-en" type="button" role="tab"
                                                aria-controls="lang-en" aria-selected="true">English</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="lang-si-tab" data-bs-toggle="tab"
                                                data-bs-target="#lang-si" type="button" role="tab"
                                                aria-controls="lang-si" aria-selected="false">Sinhala</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="lang-ta-tab" data-bs-toggle="tab"
                                                data-bs-target="#lang-ta" type="button" role="tab"
                                                aria-controls="lang-ta" aria-selected="false">Tamil</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content border border-top-0 rounded-bottom-10 p-3 pt-0 bg-white">
                                        <div class="tab-pane fade show active" id="lang-en" role="tabpanel"
                                            aria-labelledby="lang-en-tab">
                                            <textarea class="form-control" rows="7" placeholder="English description"></textarea>
                                        </div>

                                        <div class="tab-pane fade" id="lang-si" role="tabpanel"
                                            aria-labelledby="lang-si-tab">
                                            <textarea class="form-control" rows="7" placeholder="Sinhala description"></textarea>
                                        </div>

                                        <div class="tab-pane fade" id="lang-ta" role="tabpanel"
                                            aria-labelledby="lang-ta-tab">
                                            <textarea class="form-control" rows="7" placeholder="Tamil description"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Selling Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">$</span>
                                        <input type="number" min="0" step="0.01" class="form-control"
                                            id="sellPrice" placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-5">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Suggested Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">$</span>
                                        <input type="number" min="0" step="0.01" class="form-control"
                                            id="suggestedPrice" placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-2">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Company Commission</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">$</span>
                                        <input type="number" min="0" step="0.01" class="form-control"
                                            id="suggestedPrice" placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Cost</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">$</span>
                                        <input type="number" min="0" step="0.01" class="form-control"
                                            id="cost" placeholder="0.00">
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Weight (kg)</label>
                                    <div class="input-group">
                                        <input type="number" min="1" step="0.1" class="form-control"
                                            id="weight" placeholder="1.0">
                                        <span class="input-group-text bg-light">kg</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Barcode</label>
                                    <input type="text" class="form-control" id="barcode"
                                        placeholder="Will be generated automatically" readonly>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Product Guideline (PDF)</label>
                                    <input type="file" class="form-control" id="productGuideline"
                                        accept="application/pdf">
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="mb-0">
                                    <label class="label fs-16 mb-2">Product Images</label>
                                    <div class="d-flex flex-wrap gap-2" id="imageUploadGrid">
                                        <div class="upload-box" data-upload-box>
                                            <label for="productImage1" class="mb-0 cursor"><i
                                                    class="ri-image-add-line"></i></label>
                                            <input id="productImage1" type="file" accept="image/*" class="d-none">
                                        </div>
                                        <div class="upload-box" data-upload-box>
                                            <label for="productImage2" class="mb-0 cursor"><i
                                                    class="ri-image-add-line"></i></label>
                                            <input id="productImage2" type="file" accept="image/*" class="d-none">
                                        </div>
                                        <div class="upload-box" data-upload-box>
                                            <label for="productImage3" class="mb-0 cursor"><i
                                                    class="ri-image-add-line"></i></label>
                                            <input id="productImage3" type="file" accept="image/*" class="d-none">
                                        </div>
                                        <div class="upload-box" data-upload-box>
                                            <label for="productImage4" class="mb-0 cursor"><i
                                                    class="ri-image-add-line"></i></label>
                                            <input id="productImage4" type="file" accept="image/*" class="d-none">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="border rounded-10 p-3 bg-light-subtle">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="label fs-16 mb-0">Attributes</label>
                                        <button type="button" class="btn btn-sm btn-primary btn-add-attribute"
                                            id="addAttributeBtn">+ Add Attribute</button>
                                    </div>

                                    <div id="attributeList" class="d-flex flex-column gap-3">
                                        <div class="attribute-row row g-2 align-items-end">
                                            <div class="col-md-5">
                                                <input type="text" class="form-control"
                                                    placeholder="Attribute name (e.g. Color)">
                                            </div>
                                            <div class="col-md-5">
                                                <input type="text" class="form-control"
                                                    placeholder="Value (e.g. Red, Blue)">
                                            </div>
                                            <div class="col-md-2 text-end">
                                                <button type="button"
                                                    class="btn btn-outline-danger remove-attribute">Remove</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-12">
                                <div class="border rounded-10 p-3 bg-light-subtle mt-2">
                                    <h4 class="mb-20">Visibility & Pricing</h4>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" id="systemVisible"
                                                    checked>
                                                <label class="form-check-label" for="systemVisible">System Visible</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" id="webVisible" checked>
                                                <label class="form-check-label" for="webVisible">Web Visible</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-check form-switch mb-0">
                                                <input class="form-check-input" type="checkbox" id="priceLock">
                                                <label class="form-check-label" for="priceLock">Price Lock</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 d-flex justify-content-end gap-2 pt-2">
                        <button type="button" class="btn btn-light border">Save Draft</button>
                        <button type="submit" class="btn btn-primary text-white">Publish Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="flex-grow-1"></div>

    <style>
        .nav-tabs-separator .nav-link {
            border: 1px solid #e5e7eb;
            border-bottom: 0;
            margin-right: 8px;
            border-radius: 8px 8px 0 0;
            color: #6b7280;
            background: #f8fafc;
        }

        .nav-tabs-separator .nav-link.active {
            background: #fff;
            color: #111827;
            border-color: #e5e7eb;
            font-weight: 600;
        }

        .upload-box {
            width: 120px;
            height: 120px;
            border: 1.5px dashed #d1d5db;
            border-radius: 10px;
            background: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .upload-box:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .upload-box label {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-size: 28px;
            color: #64748b;
        }

        .attribute-row {
            border: 1px solid #edf2f7;
            background: #fff;
            border-radius: 8px;
            padding: 12px;
        }
    </style>

    <script>
        (function() {
            const priceLock = document.getElementById('priceLock');
            const suggestedPrice = document.getElementById('suggestedPrice');

            if (priceLock && suggestedPrice) {
                const toggleSuggestionPrice = function() {
                    suggestedPrice.disabled = priceLock.checked;
                    suggestedPrice.closest('.mb-0').style.opacity = priceLock.checked ? '0.6' : '1';
                };

                priceLock.addEventListener('change', toggleSuggestionPrice);
                toggleSuggestionPrice();
            }

            const addAttributeBtn = document.getElementById('addAttributeBtn');
            const attributeList = document.getElementById('attributeList');

            if (addAttributeBtn && attributeList) {
                addAttributeBtn.addEventListener('click', function() {
                    const row = document.createElement('div');
                    row.className = 'attribute-row row g-2 align-items-end';
                    row.innerHTML = `
                        <div class="col-md-5">
                            <input type="text" class="form-control" placeholder="Attribute name (e.g. Color)">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" placeholder="Value (e.g. Red, Blue)">
                        </div>
                        <div class="col-md-2 text-end">
                            <button type="button" class="btn btn-outline-danger remove-attribute">Remove</button>
                        </div>
                    `;
                    attributeList.appendChild(row);
                });

                attributeList.addEventListener('click', function(event) {
                    const target = event.target;
                    if (target.classList.contains('remove-attribute')) {
                        const row = target.closest('.attribute-row');
                        if (row && attributeList.children.length > 1) {
                            row.remove();
                        }
                    }
                });
            }

            const uploadBoxes = document.querySelectorAll('[data-upload-box]');
            uploadBoxes.forEach(function(box) {
                const input = box.querySelector('input[type="file"]');
                const label = box.querySelector('label');

                if (input && label) {
                    input.addEventListener('change', function() {
                        const file = this.files && this.files[0];
                        if (!file) {
                            label.innerHTML = '<i class="ri-image-add-line"></i>';
                            return;
                        }

                        const reader = new FileReader();
                        reader.onload = function(event) {
                            box.style.background = 'linear-gradient(135deg, #f0f9ff, #ffffff)';
                            box.style.borderStyle = 'solid';
                            label.innerHTML = '<img src="' + event.target.result +
                                '" alt="Product preview" class="img-fluid h-100 w-100" style="object-fit: cover; border-radius: 8px;">';
                        };
                        reader.readAsDataURL(file);
                    });
                }
            });
        })();
    </script>
@endsection
