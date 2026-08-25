@extends('layout_main.app')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="row">
            <div class="col-12">
                <div class="card bg-white border border-white rounded-10 mb-4">
                    <div class="card-body p-20">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                            <h4 class="fs-18 fw-medium mb-0">Product Categories</h4>

                            @can('product_categories.create')
                                <button type="button" class="btn btn-primary text-white btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#categoryModal">
                                    + Add Category
                                </button>
                            @endcan
                        </div>

                        <div class="mb-3">
                            <label for="productCategorySearchInput" class="form-label mb-2 fw-medium">Search
                                categories</label>
                            <input id="productCategorySearchInput" type="text" class="form-control"
                                placeholder="Search by category name..." autocomplete="off" />
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert alert-danger" role="alert">
                                <ul class="mb-0 ps-3">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if ($rootCategories->isEmpty())
                            <div class="alert alert-info mb-0">No product categories are available.</div>
                        @else
                            <div class="product-category-tree mt-3">
                                @php
                                    $renderTree = function ($categories, $level = 0) use (&$renderTree) {
                                        foreach ($categories as $category) {
                                            $children = $category->children ?? collect();
                                            $hasChildren = $children->isNotEmpty();
                                            $collapseId = 'product-category-' . $category->id;
                                            $indent = $level * 18;
                                            echo '<div class="product-category-branch" data-category-id="' .
                                                e($category->id) .
                                                '" data-category-name="' .
                                                e($category->name) .
                                                '">';
                                            echo '<div class="product-category-row d-flex align-items-center gap-2 flex-wrap rounded-8" style="margin-left:' .
                                                $indent .
                                                'px;">';
                                            echo '<div class="product-category-toggle-wrap" style="width: 22px;">';
                                            if ($hasChildren) {
                                                echo '<button type="button" class="btn btn-link text-secondary p-0 product-category-toggle" data-bs-toggle="collapse" data-bs-target="#' .
                                                    $collapseId .
                                                    '" aria-expanded="true" aria-controls="' .
                                                    $collapseId .
                                                    '"><span class="material-symbols-outlined">keyboard_arrow_down</span></button>';
                                            } else {
                                                echo '<span class="d-inline-block" style="width: 22px; height: 22px;"></span>';
                                            }
                                            echo '</div>';
                                            echo '<div class="product-category-content flex-grow-1 d-flex align-items-center justify-content-between gap-2">';
                                            echo '<div class="d-flex align-items-center gap-2 flex-wrap">';
                                            echo '<span class="badge ' .
                                                ($category->is_active
                                                    ? 'bg-success-subtle text-success'
                                                    : 'bg-danger-subtle text-danger') .
                                                ' rounded-pill px-2 py-1">' .
                                                ($category->is_active ? 'Active' : 'Inactive') .
                                                '</span>';
                                            echo '<span class="fw-large">' . e($category->name) . '</span>';
                                            if (!empty($category->description)) {
                                                echo '<small class="text-muted">' .
                                                    e(\Illuminate\Support\Str::limit($category->description, 80)) .
                                                    '</small>';
                                            }
                                            echo '</div>';
                                            echo '<div class="d-flex align-items-center gap-2 flex-wrap">';
                                            if (auth()->user()?->can('product_categories.create')) {
                                                echo '<button type="button" class="btn btn-outline-secondary btn-xs px-2 py-1 product-category-add-child" data-parent-id="' .
                                                    e($category->id) .
                                                    '" data-bs-toggle="modal" data-bs-target="#categoryModal">Add Child</button>';
                                            }
                                            if (auth()->user()?->can('product_categories.update')) {
                                                echo '<button type="button" class="btn btn-outline-primary btn-xs px-2 py-1 product-category-edit" data-category-id="' .
                                                    e($category->id) .
                                                    '" data-category-name="' .
                                                    e($category->name) .
                                                    '" data-category-parent-id="' .
                                                    e($category->parent_id ?? '') .
                                                    '" data-category-description="' .
                                                    e($category->description ?? '') .
                                                    '" data-category-sort-order="' .
                                                    e($category->sort_order) .
                                                    '" data-category-active="' .
                                                    ($category->is_active ? '1' : '0') .
                                                    '" data-bs-toggle="modal" data-bs-target="#editCategoryModal">Edit</button>';
                                            }
                                            if (auth()->user()?->can('product_categories.update')) {
                                                $toggleRoute = $category->is_active
                                                    ? route('product-categories.deactivate', $category)
                                                    : route('product-categories.activate', $category);
                                                $toggleLabel = $category->is_active ? 'Deactivate' : 'Activate';
                                                $toggleMessage = $category->is_active
                                                    ? 'Deactivate this category?'
                                                    : 'Activate this category?';
                                                echo '<form action="' .
                                                    $toggleRoute .
                                                    '" method="POST" class="d-inline">';
                                                echo csrf_field();
                                                echo '<button type="submit" class="btn btn-outline-warning btn-xs px-2 py-1" onclick="return confirm(\'' .
                                                    addslashes($toggleMessage) .
                                                    '\')">' .
                                                    $toggleLabel .
                                                    '</button>';
                                                echo '</form>';
                                            }
                                            if (auth()->user()?->can('product_categories.delete')) {
                                                echo '<form action="' .
                                                    route('product-categories.destroy', $category) .
                                                    '" method="POST" class="d-inline">';
                                                echo csrf_field();
                                                echo method_field('DELETE');
                                                echo '<button type="submit" class="btn btn-outline-danger btn-xs px-2 py-1" onclick="return confirm(\'Are you sure you want to delete this category?\')">Delete</button>';
                                                echo '</form>';
                                            }
                                            echo '</div>';
                                            echo '</div>';
                                            echo '</div>';
                                            if ($hasChildren) {
                                                echo '<div id="' .
                                                    $collapseId .
                                                    '" class="collapse show product-category-children">';
                                                $renderTree($children, $level + 1);
                                                echo '</div>';
                                            }
                                            echo '</div>';
                                        }
                                    };
                                @endphp

                                @foreach ($rootCategories as $rootCategory)
                                    @php
                                        $renderTree([$rootCategory]);
                                    @endphp
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @can('product_categories.create')
        <div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('product-categories.store') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Add Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="categoryName" class="form-label">Category Name <span
                                            class="text-danger">*</span></label>
                                    <input id="categoryName" name="name" type="text" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label for="categoryParentSelect" class="form-label">Parent Category</label>
                                    <select id="categoryParentSelect" name="parent_id" class="form-select">
                                        <option value="">No Parent</option>
                                        @php
                                            $renderParentOptions = function ($categories, $prefix = '') use (
                                                &$renderParentOptions,
                                            ) {
                                                foreach ($categories as $category) {
                                                    echo '<option value="' .
                                                        e($category->id) .
                                                        '">' .
                                                        e($prefix . $category->name) .
                                                        '</option>';
                                                    if ($category->children->isNotEmpty()) {
                                                        $renderParentOptions($category->children, $prefix . '— ');
                                                    }
                                                }
                                            };
                                            $renderParentOptions($rootCategories);
                                        @endphp
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="categoryDescription" class="form-label">Description</label>
                                    <textarea id="categoryDescription" name="description" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="categorySortOrder" class="form-label">Sort Order</label>
                                    <input id="categorySortOrder" name="sort_order" type="number" min="0"
                                        class="form-control" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary text-white">Save Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    @can('product_categories.update')
        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <form id="editCategoryForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Category</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label for="editCategoryName" class="form-label">Category Name <span
                                            class="text-danger">*</span></label>
                                    <input id="editCategoryName" name="name" type="text" class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label for="editCategoryParentSelect" class="form-label">Parent Category</label>
                                    <select id="editCategoryParentSelect" name="parent_id" class="form-select">
                                        <option value="">No Parent</option>
                                        @php
                                            $renderEditOptions = function ($categories, $prefix = '') use (
                                                &$renderEditOptions,
                                            ) {
                                                foreach ($categories as $category) {
                                                    echo '<option value="' .
                                                        e($category->id) .
                                                        '">' .
                                                        e($prefix . $category->name) .
                                                        '</option>';
                                                    if ($category->children->isNotEmpty()) {
                                                        $renderEditOptions($category->children, $prefix . '— ');
                                                    }
                                                }
                                            };
                                            $renderEditOptions($rootCategories);
                                        @endphp
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label for="editCategoryDescription" class="form-label">Description</label>
                                    <textarea id="editCategoryDescription" name="description" class="form-control" rows="3"></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="editCategorySortOrder" class="form-label">Sort Order</label>
                                    <input id="editCategorySortOrder" name="sort_order" type="number" min="0"
                                        class="form-control" value="0">
                                </div>
                                <div class="col-md-6 d-flex align-items-end">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active"
                                            id="editCategoryIsActive" checked>
                                        <label class="form-check-label" for="editCategoryIsActive">Active</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary text-white">Update Category</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan
@endsection

@push('styles')
    <style>
        .product-category-tree {
            border: 1px solid var(--bs-border-color);
            border-radius: 0.75rem;
            padding: 1rem;
        }

        .product-category-branch {
            margin-bottom: 0.6rem;
        }

        .product-category-row {
            border: 1px solid var(--bs-border-color);
            padding: 0.8rem 0.9rem;
            min-height: 58px;
            border-radius: 10px;
        }

        .product-category-toggle {
            width: 25px;
            height: 25px;
            border: 1px solid var(--bs-border-color);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: transparent;
            color: var(--bs-body-color);
            text-decoration: none;
            box-shadow: none;
            line-height: 1;
            padding: 0;
        }

        .product-category-toggle:hover,
        .product-category-toggle:focus,
        .product-category-toggle:active,
        .product-category-toggle:focus-visible {
            text-decoration: none;
            box-shadow: none;
            background: rgba(var(--bs-primary-rgb), 0.08);
            border-color: rgba(var(--bs-primary-rgb), 0.3);
            color: var(--bs-primary);
        }

        .product-category-children {
            border-left: 0;
            margin-left: 0.9rem;
            padding-left: 0;
            margin-top: 0.5rem;
        }

        .product-category-children .product-category-branch:last-child {
            margin-bottom: 0;
        }

        .btn-xs {
            font-size: 0.76rem;
            padding: 0.4rem 0.7rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('productCategorySearchInput');
            const categoryNodes = Array.from(document.querySelectorAll('.product-category-branch'));

            searchInput.addEventListener('input', function() {
                const query = this.value.trim().toLowerCase();

                categoryNodes.forEach(function(branch) {
                    const name = (branch.dataset.categoryName || '').toLowerCase();
                    const match = !query || name.includes(query);
                    branch.style.display = match ? '' : 'none';

                    const container = branch.closest('.product-category-children');
                    if (container) {
                        const parentBranch = container.closest('.product-category-branch');
                        if (parentBranch && !match) {
                            const ancestorVisible = parentBranch.dataset.categoryName.toLowerCase()
                                .includes(query) || parentBranch.querySelectorAll(
                                    '.product-category-branch').length === 0;
                            if (!ancestorVisible) {
                                parentBranch.style.display = 'none';
                            }
                        }
                    }
                });

                document.querySelectorAll('.product-category-children').forEach(function(childrenWrap) {
                    const anyVisibleChild = Array.from(childrenWrap.querySelectorAll(
                        '.product-category-branch')).some(function(branch) {
                        return branch.style.display !== 'none';
                    });
                    const toggle = childrenWrap.closest('.product-category-branch')?.querySelector(
                        '.product-category-toggle');
                    if (toggle) {
                        if (query && anyVisibleChild) {
                            toggle.setAttribute('aria-expanded', 'true');
                            childrenWrap.classList.add('show');
                        } else if (!query) {
                            toggle.setAttribute('aria-expanded', 'true');
                            childrenWrap.classList.add('show');
                        }
                    }
                });
            });

            document.querySelectorAll('.product-category-add-child').forEach(function(button) {
                button.addEventListener('click', function() {
                    const parentId = button.dataset.parentId;
                    const select = document.getElementById('categoryParentSelect');
                    if (select) {
                        select.value = parentId;
                    }
                });
            });

            document.querySelectorAll('.product-category-edit').forEach(function(button) {
                button.addEventListener('click', function() {
                    const form = document.getElementById('editCategoryForm');
                    const id = button.dataset.categoryId;
                    const name = button.dataset.categoryName || '';
                    const parentId = button.dataset.categoryParentId || '';
                    const description = button.dataset.categoryDescription || '';
                    const sortOrder = button.dataset.categorySortOrder || 0;
                    const active = button.dataset.categoryActive === '1';

                    form.action = '{{ route('product-categories.index') }}/' + id;
                    document.getElementById('editCategoryName').value = name;
                    document.getElementById('editCategoryDescription').value = description;
                    document.getElementById('editCategorySortOrder').value = sortOrder;
                    document.getElementById('editCategoryParentSelect').value = parentId;
                    document.getElementById('editCategoryIsActive').checked = active;
                });
            });
        });
    </script>
@endpush
