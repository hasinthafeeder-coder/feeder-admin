@extends('layout_main.app')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="row">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1 w-100">
                <div class="card bg-white rounded-10 border border-white mb-4 w-100">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20 pb-0">
                        <h3 class="mb-0">Pending Sellers</h3>
                    </div>

                    <div class="default-table-area table-contact-list">
                        <div class="table-responsive">
                            <table class="table align-middle w-100" id="myTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="fw-medium pe-0 rtl-pe">#</th>
                                        <th scope="col" class="fw-medium">Company</th>
                                        <th scope="col" class="fw-medium">Reseller</th>
                                        <th scope="col" class="fw-medium">Email</th>
                                        <th scope="col" class="fw-medium">Role</th>
                                        <th scope="col" class="fw-medium">Status</th>
                                        <th scope="col" class="fw-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#1</td>
                                        <td class="text-body">ABC Corporation</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user15.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user15">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcia Baker</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">marcia@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#2</td>
                                        <td class="text-body">XYZ Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user2.jpg') }}" class="rounded-circle"
                                                        style="width: 35px; height: 35px;" alt="user2">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Carolyn Barnes</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">barnes@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1 w-100">
                <div class="card bg-white rounded-10 border border-white mb-4 w-100">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20 pb-0">
                        <h3 class="mb-0">Active Sellers</h3>
                    </div>

                    <div class="default-table-area table-contact-list">
                        <div class="table-responsive">
                            <table class="table align-middle w-100" id="activeTable">
                                <thead>
                                    <tr>
                                        <th scope="col" class="fw-medium pe-0 rtl-pe">#</th>
                                        <th scope="col" class="fw-medium">Company</th>
                                        <th scope="col" class="fw-medium">Reseller</th>
                                        <th scope="col" class="fw-medium">Email</th>
                                        <th scope="col" class="fw-medium">Role</th>
                                        <th scope="col" class="fw-medium">Status</th>
                                        <th scope="col" class="fw-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#1</td>
                                        <td class="text-body">ABC Corporation</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user15.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user15">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcia Baker</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">marcia@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#2</td>
                                        <td class="text-body">XYZ Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user2.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user2">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Carolyn Barnes</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">barnes@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#3</td>
                                        <td class="text-body">Tech Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user12.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user12">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Donna Miller</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">donna@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#4</td>
                                        <td class="text-body">Global Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user5.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user5">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Barbara Cross</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">cross@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#5</td>
                                        <td class="text-body">Acma Industries</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user16.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user16">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Rebecca Block</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">block@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#6</td>
                                        <td class="text-body">Synergy Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user9.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user9">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Ramiro McCarty</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">ramiro@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#7</td>
                                        <td class="text-body">Summit Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user1.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user1">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Robert Fairweather</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">robert@example.com</td>
                                        <td class="text-body">Supervisor</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#8</td>
                                        <td class="text-body">Strategies Ltd</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user6.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user6">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Marcelino Haddock</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">haddock@example.com</td>
                                        <td class="text-body">User</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#9</td>
                                        <td class="text-body">Tech Enterprises</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user13.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user13">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Thomas Wilson</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">wildon@example.com</td>
                                        <td class="text-body">Manager</td>
                                        <td>
                                            <span
                                                class="text-danger bg-danger bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Inactive</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="text-body pe-0 rtl-pe">#10</td>
                                        <td class="text-body">Synetic Solutions</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/user14.jpg') }}"
                                                        class="rounded-circle" style="width: 35px; height: 35px;"
                                                        alt="user14">
                                                </div>
                                                <div class="flex-grow-1 ms-12">
                                                    <h4 class="fw-medium fs-16 mb-0">Nathaniel Hulsey</h4>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-body">hulsey@example.com</td>
                                        <td class="text-body">Admin</td>
                                        <td>
                                            <span
                                                class="text-success bg-success bg-opacity-10 fs-15 fw-normal d-inline-block default-badge">Active</span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 fw-normal"
                                                        style="color: #EF4923;">visibility</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0" data-bs-toggle="tooltip"
                                                    data-bs-placement="top" data-bs-title="Edit">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">edit</i>
                                                </button>
                                                <button class="bg-transparent p-0 border-0 hover-text-danger"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    data-bs-title="Delete">
                                                    <i
                                                        class="material-symbols-outlined fs-16 fw-normal text-body">delete</i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-grow-1"></div>
@endsection

<style>
    .default-table-area .d-flex .bg-transparent[data-bs-toggle="tooltip"] i[style*="color: #EF4923"]:hover {
        color: #D43D1F !important;
    }

    .default-table-area .d-flex .bg-transparent:hover i[style*="color: #EF4923"] {
        color: #D43D1F !important;
    }

    .search-input-group .form-control {
        border: 1px solid #e5e7eb;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
    }

    .search-input-group .form-control:focus {
        border-color: #EF4923;
        box-shadow: 0 0 0 0.2rem rgba(239, 73, 35, 0.25);
    }

    .form-select-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border: 1px solid #e5e7eb;
    }

     .form-select-sm:focus {
        border-color: #EF4923;
        box-shadow: 0 0 0 0.2rem rgba(239, 73, 35, 0.25);
    }

    /* DataTables styling */
    .dataTables_wrapper {
        width: 100%;
    }

    .dataTables_length {
        margin-bottom: 1rem;
    }

    .dataTables_filter {
        margin-bottom: 1rem;
    }

    .dataTables_filter input {
        border: 1px solid #e5e7eb;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        margin-left: 0.5rem;
    }

    .dataTables_filter input:focus {
        border-color: #EF4923;
        box-shadow: 0 0 0 0.2rem rgba(239, 73, 35, 0.25);
    }

    .dataTables_length select {
        border: 1px solid #e5e7eb;
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        margin: 0 0.5rem;
    }

    .dataTables_length select:focus {
        border-color: #EF4923;
        box-shadow: 0 0 0 0.2rem rgba(239, 73, 35, 0.25);
    }

    .dataTables_paginate {
        margin-top: 1rem;
    }

    .paginate_button {
        padding: 0.375rem 0.75rem;
        margin: 0 0.25rem;
        border: 1px solid #e5e7eb;
        border-radius: 0.5rem;
        cursor: pointer;
        background-color: white;
        color: #0f172a;
    }

    .paginate_button:hover,
    .paginate_button.current {
        background-color: #EF4923;
        color: white;
        border-color: #EF4923;
    }

    .paginate_button.current {
        font-weight: bold;
    }

    .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script>
    $(document).ready(function() {
        // Initialize Active Sellers as DataTable only
        // Pending Sellers table remains as a normal table
        $('#activeTable').DataTable({
            pageLength: 10,
            lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
            searching: true,
            ordering: true,
            info: true,
            paging: true,
            autoWidth: false,
            responsive: true,
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });
    });
</script>

