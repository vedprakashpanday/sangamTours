@extends('admin.common_layout')

@section('admin_content')
<div class="container-fluid">
    <h4 class="fw-bold mb-4">Travel Admin Dashboard</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-primary text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">Total Revenue</h6>
                        <h3 class="mb-0">₹{{ number_format($data['total_revenue']) }}</h3>
                    </div>
                    <i class='bx bx-money fs-1 opacity-50'></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-success text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">Total Bookings</h6>
                        <h3 class="mb-0">{{ $data['total_bookings'] }}</h3>
                    </div>
                    <i class='bx bx-calendar-check fs-1 opacity-50'></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-info text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">Active Packages</h6>
                        <h3 class="mb-0">{{ $data['total_packages'] }}</h3>
                    </div>
                    <i class='bx bx-package fs-1 opacity-50'></i>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm bg-warning text-white p-3">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="mb-1">Total Hotels</h6>
                        <h3 class="mb-0">{{ $data['total_hotels'] }}</h3>
                    </div>
                    <i class='bx bx-building fs-1 opacity-50'></i>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Recent Bookings Status</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Service</th>
                                    <th>Customer</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><span class="badge bg-primary">Tour</span> PKG-102</td>
                                    <td>Ved Prakash</td>
                                    <td>₹15,000</td>
                                    <td><span class="badge bg-success">Confirmed</span></td>
                                </tr>
                                <tr>
                                    <td><span class="badge bg-info">Flight</span> G8-212</td>
                                    <td>Rahul Sharma</td>
                                    <td>₹4,500</td>
                                    <td><span class="badge bg-warning text-dark">Pending</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 fw-bold">Customer Security Status</h6>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class='bx bx-user-check text-success'></i> Active Customers</span>
                            <span class="badge bg-success rounded-pill">{{ $data['customer_stats']['active'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class='bx bx-user-x text-danger'></i> Blocked Users</span>
                            <span class="badge bg-danger rounded-pill">{{ $data['customer_stats']['blocked'] }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <span><i class='bx bx-user-minus text-warning text-dark'></i> Restricted Access</span>
                            <span class="badge bg-warning text-dark rounded-pill">{{ $data['customer_stats']['restricted'] }}</span>
                        </li>
                    </ul>
                    <div class="mt-4 p-3 bg-light rounded text-center">
                        <small class="text-muted d-block mb-2">Need to review blocked users?</small>
                        <a href="#" class="btn btn-sm btn-outline-danger w-100">Manage Customers</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection