<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #RES-{{ $reservation->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', sans-serif; color: #333; font-size: 14px; margin: 0; padding: 20px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, 0.15); font-size: 16px; line-height: 24px; }
        .invoice-box table { width: 100%; line-height: inherit; text-align: left; border-collapse: collapse; }
        .invoice-box table td { padding: 5px; vertical-align: top; }
        .invoice-box table tr.top table td { padding-bottom: 20px; }
        .invoice-box table tr.top table td.title { font-size: 45px; line-height: 45px; color: #333; }
        .invoice-box table tr.information table td { padding-bottom: 40px; }
        .invoice-box table tr.heading td { background: #eee; border-bottom: 1px solid #ddd; font-weight: bold; }
        .invoice-box table tr.details td { padding-bottom: 20px; }
        .invoice-box table tr.item td { border-bottom: 1px solid #eee; }
        .invoice-box table tr.item.last td { border-bottom: none; }
        .invoice-box table tr.total td:nth-child(2) { border-top: 2px solid #eee; font-weight: bold; font-size: 20px; color: #10B981;}
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 50px; font-size: 12px; font-weight: bold; text-transform: uppercase; border: 1px solid #ccc; background: #f9f9f9;}
    </style>
</head>
<body>
    <div class="invoice-box">
        <table>
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <strong>{{ $reservation->room->hotel->name ?? 'Grand Plaza Hotel' }}</strong>
                            </td>
                            <td style="text-align: right;">
                                <strong>Invoice #:</strong> RES-{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}<br>
                                <strong>Generated:</strong> {{ \Carbon\Carbon::now()->format('F d, Y') }}<br>
                                <span class="status-badge">{{ ucfirst($reservation->status) }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{ $reservation->room->hotel->address ?? '123 Luxury Ave' }}<br>
                                {{ $reservation->room->hotel->city ?? 'Metropolis' }}, {{ $reservation->room->hotel->country ?? 'USA' }}<br>
                                {{ $reservation->room->hotel->email ?? 'contact@hotel.com' }}
                            </td>
                            <td style="text-align: right;">
                                <strong>Billed To:</strong><br>
                                {{ $reservation->user->name ?? 'Guest User' }}<br>
                                {{ $reservation->user->email ?? 'guest@hms.com' }}<br>
                                {{ $reservation->user->phone ?? 'N/A' }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="heading">
                <td>Reservation Details</td>
                <td style="text-align: right;">Information</td>
            </tr>

            <tr class="item">
                <td>Room Configuration</td>
                <td style="text-align: right;">#{{ $reservation->room->room_number ?? 'N/A' }} ({{ ucfirst($reservation->room->type ?? 'Standard') }})</td>
            </tr>

            <tr class="item">
                <td>Check-In Date</td>
                <td style="text-align: right;">{{ \Carbon\Carbon::parse($reservation->check_in_date)->format('M d, Y') }}</td>
            </tr>

            <tr class="item last">
                <td>Check-Out Date</td>
                <td style="text-align: right;">{{ \Carbon\Carbon::parse($reservation->check_out_date)->format('M d, Y') }}</td>
            </tr>

            <tr class="heading">
                <td>Charges</td>
                <td style="text-align: right;">Subtotal</td>
            </tr>

            <tr class="item">
                <td>Duration of Stay</td>
                <td style="text-align: right;">
                    @php
                        $days = \Carbon\Carbon::parse($reservation->check_in_date)->diffInDays(\Carbon\Carbon::parse($reservation->check_out_date));
                        if($days == 0) $days = 1;
                    @endphp
                    {{ $days }} Night(s) at ${{ number_format($reservation->room->price_per_night ?? 0, 2) }}
                </td>
            </tr>

            <tr class="total">
                <td></td>
                <td style="text-align: right;">Total Amount: ${{ number_format($reservation->total_price, 2) }}</td>
            </tr>
        </table>
        @if($reservation->special_requests)
        <div style="margin-top: 40px; border-top: 1px solid #eee; padding-top: 20px;">
            <p><strong>Note:</strong> {{ $reservation->special_requests }}</p>
        </div>
        @endif
        <div style="margin-top: 20px; text-align: center; color: #777; font-size: 12px;">
            <p>Thank you for choosing {{ $reservation->room->hotel->name ?? 'our Hotel' }}. We hope you enjoy your stay!</p>
        </div>
    </div>
</body>
</html>
