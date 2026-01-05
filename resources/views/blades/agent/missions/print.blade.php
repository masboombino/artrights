<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Print Mission - {{ $mission->title }}</title>
    <style>
        @media print {
            .no-print { display: none; }
            @page { margin: 1cm; }
        }
        body {
            font-family: 'Arial', sans-serif;
            direction: ltr;
            padding: 20px;
            color: #000;
            background: #fff;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 18px;
            font-weight: bold;
            border-bottom: 2px solid #000;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px dotted #ccc;
        }
        .info-label {
            font-weight: bold;
            min-width: 150px;
        }
        .info-value {
            flex: 1;
            text-align: left;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        table th {
            background-color: #f0f0f0;
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #000;
            text-align: center;
            font-size: 12px;
        }
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #193948;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .payment-proof-image {
            max-width: 100%;
            max-height: 400px;
            border: 2px solid #000;
            margin-top: 10px;
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">🖨️ Print</button>

    <div class="header">
        <h1>Mission: {{ $mission->title }}</h1>
        <p>Mission Number: {{ $mission->id }} | Print Date: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Mission Information</div>
        <div class="info-row">
            <span class="info-label">Mission Title:</span>
            <span class="info-value">{{ $mission->title }}</span>
        </div>
        @if($mission->description)
        <div class="info-row">
            <span class="info-label">Description:</span>
            <span class="info-value">{{ $mission->description }}</span>
        </div>
        @endif
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">{{ $mission->status }}</span>
        </div>
        @if($mission->scheduled_at)
        <div class="info-row">
            <span class="info-label">Scheduled Date:</span>
            <span class="info-value">{{ $mission->scheduled_at->format('d/m/Y H:i') }}</span>
        </div>
        @endif
        @if($mission->location_text)
        <div class="info-row">
            <span class="info-label">Location:</span>
            <span class="info-value">{{ $mission->location_text }}</span>
        </div>
        @endif
        @if($mission->map_link)
        <div class="info-row">
            <span class="info-label">Map Link:</span>
            <span class="info-value">{{ $mission->map_link }}</span>
        </div>
        @endif
        @if($mission->agent)
        <div class="info-row">
            <span class="info-label">Assigned Agent:</span>
            <span class="info-value">{{ $mission->agent->user->name ?? 'N/A' }}</span>
        </div>
        @endif
        @if($mission->agent && $mission->agent->badge_number)
        <div class="info-row">
            <span class="info-label">Agent Badge Number:</span>
            <span class="info-value">{{ $mission->agent->badge_number }}</span>
        </div>
        @endif
        @if($mission->agency)
        <div class="info-row">
            <span class="info-label">Agency:</span>
            <span class="info-value">{{ $mission->agency->agency_name ?? 'N/A' }}</span>
        </div>
        @endif
    </div>

    @if($pv)
    <div class="section">
        <div class="section-title">PV Information</div>
        <div class="info-row">
            <span class="info-label">PV Number:</span>
            <span class="info-value">#{{ $pv->id }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Shop Name:</span>
            <span class="info-value">{{ $pv->shop_name }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Shop Type:</span>
            <span class="info-value">{{ $pv->shop_type }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Inspection Date:</span>
            <span class="info-value">{{ $pv->date_of_inspection?->format('d/m/Y H:i') ?? 'Not specified' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status:</span>
            <span class="info-value">{{ $pv->status }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Agency:</span>
            <span class="info-value">{{ $pv->agency->agency_name ?? 'Not specified' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Agent:</span>
            <span class="info-value">{{ $pv->agent->user->name ?? 'Not specified' }}</span>
        </div>
        @if($pv->agent->badge_number)
        <div class="info-row">
            <span class="info-label">Badge Number:</span>
            <span class="info-value">{{ $pv->agent->badge_number }}</span>
        </div>
        @endif
    </div>

    @if(count($pv->devices) > 0)
    <div class="section">
        <div class="section-title">Registered Devices</div>
        <table>
            <thead>
                <tr>
                    <th>Device Name</th>
                    <th>Type</th>
                    <th>Coefficient</th>
                    <th>Quantity</th>
                    <th>Assigned Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pv->devices as $device)
                <tr>
                    <td>{{ $device->name }}</td>
                    <td>{{ $device->type ?? 'Not specified' }}</td>
                    <td>{{ $device->coefficient }}</td>
                    <td>{{ $device->quantity }}</td>
                    <td>{{ number_format($device->amount, 2) }} DZD</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if(count($pv->artworkUsages) > 0)
    <div class="section">
        <div class="section-title">Artworks Used</div>
        <table>
            <thead>
                <tr>
                    <th>Artwork Title</th>
                    <th>Artist</th>
                    <th>Hours/Count</th>
                    <th>Device</th>
                    <th>Fine Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pv->artworkUsages as $usage)
                <tr>
                    <td>{{ $usage->artwork->title }}</td>
                    <td>{{ $usage->artwork->artist->user->name ?? 'Not specified' }}</td>
                    <td>{{ $usage->hours_used }}</td>
                    <td>{{ $usage->device->name ?? 'Not specified' }}</td>
                    <td>{{ number_format($usage->fine_amount, 2) }} DZD</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Payment Information</div>
        <div class="info-row">
            <span class="info-label">Payment Method:</span>
            <span class="info-value">{{ $pv->payment_method ?? 'Not specified' }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Payment Status:</span>
            <span class="info-value">{{ $pv->payment_status }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total Amount:</span>
            <span class="info-value"><strong>{{ number_format($pv->total_amount, 2) }} DZD</strong></span>
        </div>
        @if($pv->cash_received_amount)
        <div class="info-row">
            <span class="info-label">Amount Received:</span>
            <span class="info-value">{{ number_format($pv->cash_received_amount, 2) }} DZD</span>
        </div>
        @endif
        @php($photoCount = count($pv->evidenceFiles()))
        <div class="info-row">
            <span class="info-label">Uploaded Photos Count:</span>
            <span class="info-value">{{ $photoCount }} photos</span>
        </div>
        @if($pv->payment_proof_path)
        <div class="info-row" style="flex-direction: column; align-items: flex-start;">
            <span class="info-label" style="margin-bottom: 10px;">Payment Proof:</span>
            <img src="{{ \Illuminate\Support\Facades\Storage::url($pv->payment_proof_path) }}" alt="Payment Proof" class="payment-proof-image">
        </div>
        @endif
    </div>

    @if($pv->notes)
    <div class="section">
        <div class="section-title">PV Notes</div>
        <p style="white-space: pre-wrap;">{{ $pv->notes }}</p>
    </div>
    @endif
    @else
    <div class="section">
        <div class="section-title">PV Status</div>
        <div class="info-row">
            <span class="info-label">PV Status:</span>
            <span class="info-value">No PV created yet for this mission</span>
        </div>
    </div>
    @endif

    <div class="footer">
        <p>This document was automatically generated by ArtRights system</p>
        <p>© {{ date('Y') }} ArtRights. All rights reserved.</p>
    </div>
</body>
</html>
