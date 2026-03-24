<!DOCTYPE html>
<html>

<head>
    <title>Receipt_{{ $booking->booking_no }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #333;
        }

        .receipt-main {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
        }

        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: #4e73df;
            letter-spacing: -1px;
        }

        .invoice-title {
            font-size: 24px;
            font-weight: bold;
            text-transform: uppercase;
            color: #999;
        }

        .table-custom th {
            background: #f8f9fa;
            color: #555;
            font-size: 12px;
            text-transform: uppercase;
        }

        .footer-note {
            font-size: 11px;
            color: #777;
            margin-top: 50px;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }

        @media print {
            .no-print {
                display: none;
            }

            .receipt-main {
                border: none;
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="receipt-main">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <div class="logo-text">SANGAM TOURS</div>
                <p class="mb-0 small text-muted">Patna, Bihar, India<br>Phone: +91 98765 43210</p>
            </div>
            <div class="text-end">
                <div class="invoice-title">Booking Receipt</div>
                <div class="fw-bold">No: {{ $booking->booking_no }}</div>
                <div class="small text-muted">Date: {{ date('d M, Y', strtotime($booking->created_at)) }}</div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h6 class="text-muted small fw-bold">CUSTOMER DETAILS</h6>
                <div class="fw-bold">{{ $booking->customer->name }}</div>
                <div class="small">{{ $booking->customer->phone }}</div>
                <div class="small text-muted">{{ $booking->customer->email }}</div>
            </div>
            <div class="col-6 text-end">
                <h6 class="text-muted small fw-bold">TRAVEL DETAILS</h6>
                <div class="fw-bold text-primary">{{ $booking->service_type }} Service</div>
                <div class="small">Travel Date: <b>{{ date('d M, Y', strtotime($booking->travel_date)) }}</b></div>
                <div class="small">Pax: <b>{{ $booking->pax_count }} Person(s)</b></div>
            </div>
        </div>

        <table class="table table-bordered table-custom mb-4">
            <thead>
                <tr>
                    <th>Service Description</th>
                    <th class="text-center">Route / Details</th>
                    <th class="text-end">Fare</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                  <td>
    <b>{{ $booking->service_type }} Booking</b><br>
    <small class="text-muted">
        Vehicle: {{ $booking->vehicle->vehicle_number ?? 'N/A' }} 
    </small>
</td>

<td class="text-center">
    @if($booking->route)
        {{ $booking->route->fromCity->city_location ?? 'N/A' }} 
        to 
        {{ $booking->route->toCity->city_location ?? 'N/A' }}
    @elseif($booking->package)
        {{ $booking->package->title ?? 'Package Booking' }}
    @else
        N/A
    @endif
</td>
                    <td class="text-end fw-bold">₹{{ number_format($booking->total_amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        @if($booking->passengers && count($booking->passengers) > 0)
        <div class="mb-4">
            <h6 class="text-muted small fw-bold">CO-PASSENGERS</h6>
            <table class="table table-sm small">
                @foreach($booking->passengers as $p)
                <tr>
                    <td>{{ $p->name }}</td>
                    <td>{{ $p->age }} Yrs</td>
                    <td>{{ $p->gender }}</td>
                </tr>
                @endforeach
            </table>
        </div>
        @endif

        <div class="row justify-content-end">
            <div class="col-5">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td class="text-muted">Total Amount:</td>
                        <td class="text-end fw-bold">₹{{ number_format($booking->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-muted">Paid Amount:</td>
                        <td class="text-end text-success fw-bold">₹{{ number_format($booking->paid_amount, 2) }}</td>
                    </tr>
                    <tr class="border-top">
                        <td class="fw-bold">Balance Due:</td>
                        <td class="text-end text-danger fw-bold" style="font-size: 18px;">₹{{ number_format($booking->due_amount, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer-note">
            <div class="row">
                <div class="col-8">
                    <strong>Terms & Conditions:</strong>
                    <ul>
                        <li>Please carry a valid ID proof during travel.</li>
                        <li>Reporting time is 30 mins before departure.</li>
                        <li>Cancellation charges apply as per company policy.</li>
                    </ul>
                </div>
                <div class="col-4 text-center mt-4">
                    <br><br>
                    <div style="border-top: 1px solid #333;" class="pt-1 small">Authorized Signatory</div>
                </div>
            </div>
            <div class="text-center mt-3 text-muted">*** Thank you for choosing Sangam Tours ***</div>
        </div>
    </div>

    <div class="text-center mt-3 no-print">
        <button onclick="window.print()" class="btn btn-primary">Print Again</button>
        <button onclick="window.close()" class="btn btn-secondary">Close Window</button>
    </div>

</body>

</html>