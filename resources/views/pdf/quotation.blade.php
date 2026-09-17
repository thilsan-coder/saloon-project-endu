<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $booking->type === 'quotation' ? 'Salon Quotation' : 'Booking Statement' }} - {{ $booking->booking_number }}</title>
    <style>
        @page {
            margin: 14mm 16mm 16mm 16mm;
            size: a4 portrait;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #1e293b;
            font-size: 10.5px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background-color: #ffffff;
        }

        /* Utility Tables */
        .layout-table {
            width: 100%;
            border-collapse: collapse;
            border: 0;
            margin: 0;
            padding: 0;
        }
        .layout-table td {
            padding: 0;
            vertical-align: top;
            border: 0;
        }

        /* Document Header */
        .header-container {
            padding-bottom: 12px;
            border-bottom: 2.5px solid #be123c;
            margin-bottom: 14px;
        }
        .brand-title {
            font-size: 21px;
            font-weight: bold;
            color: #be123c;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin: 0 0 2px 0;
        }
        .brand-subtitle {
            font-size: 10px;
            font-weight: 600;
            color: #475569;
            margin: 0 0 2px 0;
        }
        .brand-contact {
            font-size: 9px;
            color: #64748b;
            margin: 0;
            line-height: 1.35;
        }
        .header-doc-meta {
            text-align: right;
        }
        .doc-badge {
            display: inline-block;
            background-color: #fff1f2;
            color: #be123c;
            border: 1px solid #fecdd3;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            padding: 3px 8px;
            border-radius: 4px;
            margin-bottom: 4px;
        }
        .doc-number {
            font-size: 12.5px;
            font-weight: bold;
            color: #0f172a;
            margin-bottom: 2px;
        }
        .doc-date {
            font-size: 9px;
            color: #64748b;
        }

        /* Two-Column Info Cards */
        .info-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 9px 12px;
        }
        .card-header-label {
            font-size: 8.5px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #94a3b8;
            margin-bottom: 6px;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 3px;
        }
        .card-table {
            width: 100%;
            border-collapse: collapse;
        }
        .card-table td {
            padding: 2.5px 0;
            font-size: 9.5px;
            vertical-align: top;
        }
        .c-label {
            color: #64748b;
            font-weight: normal;
        }
        .c-val {
            color: #0f172a;
        }

        /* Section Headings */
        .section-heading {
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #be123c;
            margin: 14px 0 6px 0;
        }

        /* Services Breakdown Table */
        .services-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .services-table th {
            background-color: #be123c;
            color: #ffffff;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.6px;
            padding: 6.5px 9px;
            text-align: left;
            border: 1px solid #be123c;
        }
        .services-table th.text-right {
            text-align: right;
        }
        .services-table th.text-center {
            text-align: center;
        }
        .module-group-header td {
            background-color: #fff1f2;
            color: #9f1239;
            font-size: 9.5px;
            font-weight: bold;
            padding: 5px 9px;
            border: 1px solid #fecdd3;
            border-left: 3px solid #be123c;
            letter-spacing: 0.4px;
        }
        .service-row td {
            padding: 6.5px 9px;
            border-bottom: 1px solid #e2e8f0;
            border-left: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            font-size: 9.5px;
            vertical-align: middle;
            page-break-inside: avoid;
        }
        .service-row:nth-child(even) td {
            background-color: #f8fafc;
        }
        .service-name {
            font-weight: bold;
            color: #0f172a;
        }
        .service-cat {
            color: #64748b;
            font-size: 9px;
        }
        .service-price {
            text-align: right;
            font-weight: bold;
            color: #0f172a;
            font-size: 10px;
        }

        /* Summary & Notes Section */
        .summary-wrapper {
            page-break-inside: avoid;
        }
        .policy-card {
            background-color: #fafaf9;
            border: 1px solid #e7e5e4;
            border-left: 3px solid #be123c;
            border-radius: 4px;
            padding: 9px 11px;
            font-size: 9px;
            color: #57534e;
            line-height: 1.4;
        }
        .policy-title {
            font-weight: bold;
            color: #be123c;
            margin-bottom: 3px;
            font-size: 9.5px;
            text-transform: uppercase;
        }

        .billing-summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 9px 12px;
        }
        .billing-table {
            width: 100%;
            border-collapse: collapse;
        }
        .billing-table td {
            padding: 2.5px 0;
            font-size: 9.5px;
        }
        .billing-table .label {
            color: #64748b;
        }
        .billing-table .value {
            text-align: right;
            font-weight: 600;
            color: #1e293b;
        }
        .billing-table .total-row td {
            border-top: 1.5px solid #cbd5e1;
            padding-top: 5px;
            margin-top: 3px;
        }
        .billing-table .total-label {
            font-size: 10px;
            font-weight: bold;
            color: #be123c;
            text-transform: uppercase;
        }
        .billing-table .total-value {
            font-size: 13.5px;
            font-weight: bold;
            color: #be123c;
            text-align: right;
        }

        /* Signature Section */
        .signature-box {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            padding: 9px 12px;
            text-align: center;
        }
        .signature-preview-area {
            height: 44px;
            line-height: 44px;
            text-align: center;
        }
        .signature-image {
            max-height: 40px;
            max-width: 150px;
            vertical-align: middle;
        }
        .signature-line {
            border-top: 1px solid #94a3b8;
            margin-top: 5px;
            padding-top: 3px;
        }
        .signature-signee {
            font-size: 9.5px;
            font-weight: bold;
            color: #0f172a;
        }
        .signature-role {
            font-size: 8px;
            color: #64748b;
        }

        /* Footer */
        .footer-container {
            margin-top: 18px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            line-height: 1.35;
        }
    </style>
</head>
<body>

    <!-- Document Header -->
    <div class="header-container">
        <table class="layout-table">
            <tr>
                <td style="width: 58%;">
                    <div class="brand-title">VELVET &amp; CO. SALON</div>
                    <div class="brand-subtitle">Luxury Hair, Skin &amp; Body Wellness Sanctuary</div>
                    <div class="brand-contact">104 Beverly Hills Drive • Beverly Hills, CA 90210 • Tel: (555) 234-5678</div>
                    <div class="brand-contact">Web: www.velvetcosalon.com • Email: concierge@velvetcosalon.com</div>
                </td>
                <td style="width: 42%;" class="header-doc-meta">
                    <div class="doc-badge">
                        {{ $booking->type === 'quotation' ? 'OFFICIAL QUOTATION' : 'BOOKING STATEMENT' }}
                    </div>
                    <div class="doc-number"># {{ $booking->booking_number }}</div>
                    <div class="doc-date">Date Issued: {{ $booking->created_at->format('F d, Y') }}</div>
                    <div class="doc-date">Valid Until: {{ $booking->created_at->addDays(30)->format('F d, Y') }}</div>
                    <div class="doc-date" style="margin-top: 2px;">
                        Status: <strong style="color: {{ $booking->status === 'confirmed' ? '#059669' : '#d97706' }}; text-transform: uppercase;">{{ ucfirst($booking->status) }}</strong>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <!-- Client & Quotation Information Cards -->
    <table class="layout-table" style="margin-bottom: 12px;">
        <tr>
            <!-- Client Card -->
            <td style="width: 49%;">
                <div class="info-card">
                    <div class="card-header-label">Client Details</div>
                    <table class="card-table">
                        <tr>
                            <td class="c-label" style="width: 65px;">Client Name:</td>
                            <td class="c-val"><strong>{{ $booking->customer->name ?? 'Valued Client' }}</strong></td>
                        </tr>
                        <tr>
                            <td class="c-label">Phone:</td>
                            <td class="c-val">{{ $booking->customer->phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="c-label">Email:</td>
                            <td class="c-val">{{ $booking->customer->email ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="c-label">Address:</td>
                            <td class="c-val">{{ $booking->customer->address ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </td>

            <!-- Spacer -->
            <td style="width: 2%;"></td>

            <!-- Booking Meta Card -->
            <td style="width: 49%;">
                <div class="info-card">
                    <div class="card-header-label">Booking &amp; Reference Overview</div>
                    <table class="card-table">
                        <tr>
                            <td class="c-label" style="width: 85px;">Reference ID:</td>
                            <td class="c-val"><strong>{{ $booking->booking_number }}</strong></td>
                        </tr>
                        <tr>
                            <td class="c-label">Document Type:</td>
                            <td class="c-val">{{ $booking->type === 'quotation' ? 'Service Quotation' : 'Booking Statement' }}</td>
                        </tr>
                        <tr>
                            <td class="c-label">Total Services:</td>
                            <td class="c-val">{{ $booking->bookingItems->count() }} Selected Items</td>
                        </tr>
                        <tr>
                            <td class="c-label">Status:</td>
                            <td class="c-val">
                                <strong style="color: {{ $booking->status === 'confirmed' ? '#059669' : '#d97706' }};">
                                    {{ ucfirst($booking->status) }}
                                </strong>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>

    <!-- Services Breakdown Table -->
    <div class="section-heading">Selected Services Breakdown</div>

    <table class="services-table">
        <thead>
            <tr>
                <th style="width: 6%;" class="text-center">#</th>
                <th style="width: 52%;">Service Description</th>
                <th style="width: 26%;">Sub-Module / Category</th>
                <th style="width: 16%;" class="text-right">Price (USD)</th>
            </tr>
        </thead>
        <tbody>
            @php $itemIndex = 1; @endphp
            @foreach($groupedItems as $moduleName => $subModules)
                <tr class="module-group-header">
                    <td colspan="4">
                        {{ strtoupper($moduleName) }} MODULE &bull; SERVICES
                    </td>
                </tr>
                @foreach($subModules as $subModuleName => $items)
                    @foreach($items as $item)
                        <tr class="service-row">
                            <td style="text-align: center; color: #94a3b8; font-weight: bold;">
                                {{ $itemIndex++ }}
                            </td>
                            <td>
                                <div class="service-name">{{ $item['name'] }}</div>
                            </td>
                            <td>
                                <div class="service-cat">{{ $subModuleName }}</div>
                            </td>
                            <td class="service-price">
                                ${{ number_format($item['price'], 2) }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            @endforeach
        </tbody>
    </table>

    <!-- Summary & Notes Section (Kept together) -->
    <div class="summary-wrapper">
        <!-- Billing Summary & Policies (Side-by-side) -->
        <table class="layout-table" style="margin-bottom: 14px;">
            <tr>
                <!-- Salon Terms & Guarantee -->
                <td style="width: 53%;">
                    <div class="policy-card">
                        <div class="policy-title">Salon Guarantee &amp; Client Policies</div>
                        <div>• All treatments are performed by master stylists &amp; certified estheticians using sterilized equipment.</div>
                        <div style="margin-top: 2px;">• Quotation is valid for 30 days from date of issue. Prices include all standard salon materials.</div>
                        <div style="margin-top: 2px;">• Appointment rescheduling requires a minimum of 24 hours advance notice.</div>
                    </div>
                </td>

                <!-- Spacer -->
                <td style="width: 4%;"></td>

                <!-- Billing Totals Card -->
                <td style="width: 43%;">
                    <div class="billing-summary-box">
                        <table class="billing-table">
                            <tr>
                                <td class="label">Total Services:</td>
                                <td class="value">{{ $booking->bookingItems->count() }} Items</td>
                            </tr>
                            <tr>
                                <td class="label">Subtotal Amount:</td>
                                <td class="value">${{ number_format($booking->total_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="label">Tax (0% Standard):</td>
                                <td class="value">$0.00</td>
                            </tr>
                            <tr class="total-row">
                                <td class="total-label">Total Amount Due:</td>
                                <td class="total-value">${{ number_format($booking->total_amount, 2) }}</td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Authorization & Signatures -->
        <table class="layout-table" style="margin-bottom: 10px;">
            <tr>
                <!-- Salon Authorization -->
                <td style="width: 48%;">
                    <div class="signature-box">
                        <div class="card-header-label" style="text-align: center;">Salon Authorization</div>
                        <div class="signature-preview-area">
                            <span style="font-family: Georgia, serif; font-style: italic; font-size: 15px; color: #be123c;">
                                Velvet &amp; Co. Salon
                            </span>
                        </div>
                        <div class="signature-line">
                            <div class="signature-signee">Authorized Salon Director</div>
                            <div class="signature-role">Velvet &amp; Co. Management Executive</div>
                        </div>
                    </div>
                </td>

                <!-- Spacer -->
                <td style="width: 4%;"></td>

                <!-- Customer Digital Signature -->
                <td style="width: 48%;">
                    <div class="signature-box">
                        <div class="card-header-label" style="text-align: center;">Customer Digital Acknowledgement</div>
                        <div class="signature-preview-area">
                            @if($signatureBase64)
                                <img src="{{ $signatureBase64 }}" class="signature-image" alt="Customer Signature">
                            @else
                                <span style="color: #94a3b8; font-style: italic; font-size: 9.5px;">(Confirmed Online)</span>
                            @endif
                        </div>
                        <div class="signature-line">
                            <div class="signature-signee">{{ $booking->customer->name ?? 'Valued Client' }}</div>
                            <div class="signature-role">Verified Digital Client Signature</div>
                        </div>
                    </div>
                </td>
            </tr>
        </table>

        <!-- Document Footer -->
        <div class="footer-container">
            <div>Thank you for choosing Velvet &amp; Co. Salon. We look forward to providing you with an unforgettable experience!</div>
            <div style="margin-top: 2px;">Velvet &amp; Co. Salon Management &bull; Official Document # {{ $booking->booking_number }}</div>
        </div>
    </div>

</body>
</html>
