@extends('layout_main.app')

@section('content')
    <div class="main-content-container overflow-hidden">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 mt-1">
            <h3 class="mb-0">User Profile</h3>
        </div>

        <div class="row">
            <div class="col-xxl-3 col-xxxl-3">
                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <h3 class="mb-20">Profile Information</h3>

                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('assets/images/profile.jpg') }}" class="rounded-circle" style="width: 75px;"
                                alt="profile">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fs-18" style="margin-bottom: 2px;">Micheal Collins</h3>
                            <span class="fs-16 me-2">0701234567</span>
                            <span
                                class="text-white bg-primary bg-opacity-10 mt-2 fs-15 fw-normal d-inline-block default-badge">Pending</span>
                        </div>

                    </div>
                    <hr class="my-3">
                    <ul class="p-0 mb-0 list-unstyled last-child-none">
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">ID: <span
                                class="text-secondary text-end">7001</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Email: <span
                                class="text-secondary text-end">micheal@gmail.com</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Role: <span
                                class="text-secondary text-end">Reseller</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Address: <span
                                class="text-secondary text-end">New York, USA</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">NIC: <span
                                class="text-secondary text-end">123456789V</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Service Charge: <span
                                class="text-secondary text-end">$50</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Join Date: <span
                                class="text-secondary text-end">May 10, 2025</span></li>
                    </ul>
                </div>

                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <h3 class="mb-20">Company Information</h3>

                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('assets/images/profile.jpg') }}" class="rounded-circle" style="width: 75px;"
                                alt="profile">
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="fs-18" style="margin-bottom: 2px;">Acqur Ceylon Holdings</h3>
                            <span class="fs-16">0711234567</span>
                        </div>
                    </div>
                    <hr class="my-3">
                    <ul class="p-0 mb-0 list-unstyled last-child-none">
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">ID: <span
                                class="text-secondary text-end">7001</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Invoice Name: <span
                                class="text-secondary text-end">BuySmart</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Email: <span
                                class="text-secondary text-end">micheal@gmail.com</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Address: <span
                                class="text-secondary text-end">New York, USA</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">BR: <span
                                class="text-secondary text-end"><a href="#">123456789</a></span></li>
                    </ul>
                </div>
                <div class="card bg-white border border-white rounded-10 p-20 mb-4">
                    <h3 class="mb-20">Introducer Information</h3>

                    <hr class="my-3">
                    <ul class="p-0 mb-0 list-unstyled last-child-none">
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">ID: <span
                                class="text-secondary text-end">7001</span></li>

                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Name: <span
                                class="text-secondary text-end">John Doe</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Reseller Company: <span
                                class="text-secondary text-end">BuySmart</span></li>
                        <li class="mb-10 fs-16 d-flex justify-content-between gap-2">Active Member: <span
                                class="text-secondary text-end">05</span></li>
                    </ul>
                </div>
            </div>
            <div class="col-xxl-9 col-xxxl-9">
                <div class="card bg-white rounded-10 border border-white mb-4">
                    <div class="ustify-content-between align-items-center flex-wrap gap-3 p-20">

                        <div class="row">
                            <form class="d-flex align-items-stretch justify-content-between mt-3 w-100">
                                <div class="col-lg-8">
                                    <h2>Seller waiting for approval</h2>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-primary text-white w-100 h-100">Approve</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="card bg-white rounded-10 border border-white mb-4">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-20">
                        <h3>Bank Details</h3>
                        <form>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="mb-20">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingInput1"
                                                placeholder="First Name">
                                            <label for="floatingInput1">Account Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-20">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingInput2"
                                                placeholder="Last Name">
                                            <label for="floatingInput2">Account Number</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-20">
                                        <div class="form-floating">
                                            <select class="form-select form-control" id="floatingSelect7"
                                                aria-label="Floating label select example">
                                                <option selected>Select</option>
                                                <option value="1">Peoples'Bank</option>
                                                <option value="2">Bank of Ceylon</option>
                                                <option value="3">Sampath Bank</option>
                                                <option value="4">Commercial Bank</option>
                                                <option value="5">Hatton National Bank</option>
                                            </select>
                                            <label for="floatingSelect7">Bank Name</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-20">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingInput4"
                                                placeholder="Phone">
                                            <label for="floatingInput4">Bank Code</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-20">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingInput5"
                                                placeholder="Address">
                                            <label for="floatingInput5">Branch</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="mb-20">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingInput6"
                                                placeholder="Country">
                                            <label for="floatingInput6">Branch Code</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end align-items-center gap-2 mb-20">
                                <button type="button" class="btn btn-primary text-white">Add</button>
                                <button type="reset" class="btn btn-outline-secondary">Clear</button>
                            </div>
                        </form>

                    </div>

                    <div class="default-table-area mx-minus-1 table-all-projects">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col" class="fw-medium">Account Name</th>
                                        <th scope="col" class="fw-medium">Bank</th>
                                        <th scope="col" class="fw-medium">Bank #</th>
                                        <th scope="col" class="fw-medium">Branch</th>
                                        <th scope="col" class="fw-medium">Branch #</th>
                                        <th scope="col" class="fw-medium">Acc. No</th>
                                        <th scope="col" class="fw-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-body">#951</td>
                                        <td class="text-body">Hotel management system</td>
                                        <td class="text-secondary">Vaxo Corporation</td>
                                        <td class="text-body">1123</td>
                                        <td class="text-body">$5,250</td>
                                        <td class="text-body">$5,250</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                        <td class="text-body">#547</td>
                                        <td class="text-body">Product development</td>
                                        <td class="text-secondary">Beja Ltd</td>
                                        <td class="text-body">1124</td>
                                        <td class="text-body">$4,870</td>
                                        <td class="text-body">$4,870</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                        <td class="text-body">#658</td>
                                        <td class="text-body">Python upgrade</td>
                                        <td class="text-secondary">Aegis Industries</td>
                                        <td class="text-body">1125</td>
                                        <td class="text-body">3,500</td>
                                        <td class="text-body">3,500</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card bg-white rounded-10 border border-white mb-4">
                    <div class="ustify-content-between align-items-center flex-wrap gap-3 p-20">
                        <h3>Suppliers</h3>

                        <div class="row">
                            <form class="d-flex align-items-stretch justify-content-between mt-3 w-100">
                                <div class="col-lg-8">
                                    <div class="form-floating h-100">
                                        <select class="form-select form-control h-100" id="floatingSelectSupplier1"
                                            aria-label="Floating label select example">
                                            <option selected>Select</option>
                                            <option value="1">ABC Supplier</option>
                                            <option value="2">XYZ Supplier</option>
                                            <option value="3">LMN Supplier</option>
                                            <option value="4">OPQ Supplier</option>
                                            <option value="5">RST Supplier</option>
                                        </select>
                                        <label for="floatingSelectSupplier1">Supplier Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <button type="button" class="btn btn-primary text-white w-100 h-100">Add</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="default-table-area mx-minus-1 table-all-projects">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th scope="col" class="fw-medium">#</th>
                                        <th scope="col" class="fw-medium">Supplier Name</th>
                                        <th scope="col" class="fw-medium">Supplier #</th>
                                        <th scope="col" class="fw-medium">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-body">#951</td>
                                        <td class="text-body">Hotel management system</td>
                                        <td class="text-secondary">12</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                        <td class="text-body">#547</td>
                                        <td class="text-body">Product development</td>
                                        <td class="text-secondary">Beja Ltd</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                        <td class="text-body">#658</td>
                                        <td class="text-body">Python upgrade</td>
                                        <td class="text-secondary">Aegis Industries</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                        <td class="text-body">#658</td>
                                        <td class="text-body">Python upgrade</td>
                                        <td class="text-secondary">Aegis Industries</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                        <td class="text-body">#658</td>
                                        <td class="text-body">Python upgrade</td>
                                        <td class="text-secondary">Aegis Industries</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                        <td class="text-body">#658</td>
                                        <td class="text-body">Python upgrade</td>
                                        <td class="text-secondary">Aegis Industries</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
                                        <td class="text-body">#658</td>
                                        <td class="text-body">Python upgrade</td>
                                        <td class="text-secondary">Aegis Industries</td>
                                        <td>
                                            <div class="d-flex justify-content-end" style="gap: 12px;">
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
