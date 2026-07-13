<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-In Berhasil</title>
    <style>
        /* Reset & Base */
        body {
            margin: 0;
            padding: 0;
            background-color: #f4f6f9;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333333;
            line-height: 1.6;
        }

        /* Container utama email */
        .email-wrapper {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        }

        /* Header dengan gradient */
        .email-header {
            background: linear-gradient(135deg, #4A90D9, #357ABD);
            padding: 30px 20px;
            text-align: center;
            color: #ffffff;
        }

        .email-header h1 {
            margin: 0;
            font-size: 22px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .email-header p {
            margin: 8px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        /* Badge status */
        .status-badge {
            display: inline-block;
            margin-top: 15px;
            padding: 6px 18px;
            background-color: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Body email */
        .email-body {
            padding: 30px 25px;
        }

        .greeting {
            font-size: 16px;
            margin-bottom: 15px;
        }

        .greeting strong {
            color: #4A90D9;
        }

        .info-text {
            font-size: 14px;
            color: #555555;
            margin-bottom: 25px;
        }

        /* Tabel detail peminjaman */
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            border-radius: 8px;
            overflow: hidden;
        }

        .detail-table th {
            background-color: #f0f4f8;
            padding: 10px 15px;
            text-align: left;
            font-size: 13px;
            color: #666666;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 2px solid #e0e6ed;
            width: 40%;
        }

        .detail-table td {
            padding: 10px 15px;
            font-size: 14px;
            color: #333333;
            border-bottom: 1px solid #f0f0f0;
        }

        /* Info box */
        .info-box {
            background-color: #eef6ff;
            border-left: 4px solid #4A90D9;
            padding: 15px 18px;
            border-radius: 0 8px 8px 0;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 0;
            font-size: 13px;
            color: #3a6fa5;
        }

        /* Footer */
        .email-footer {
            background-color: #f8f9fb;
            padding: 20px 25px;
            text-align: center;
            border-top: 1px solid #eeeeee;
        }

        .email-footer p {
            margin: 4px 0;
            font-size: 12px;
            color: #999999;
        }

        .email-footer .app-name {
            font-weight: 600;
            color: #4A90D9;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        {{-- Header --}}
        <div class="email-header">
            <h1>✅ Check-In Berhasil!</h1>
            <p>Smart-Hub Management System</p>
            <span class="status-badge">Checked In</span>
        </div>

        {{-- Body --}}
        <div class="email-body">
            <p class="greeting">
                Halo, <strong>{{ $schedule->user->name ?? 'Member' }}</strong>! 👋
            </p>

            <p class="info-text">
                Proses check-in peminjaman peralatan Anda telah berhasil dilakukan.
                Berikut adalah detail peminjaman Anda:
            </p>

            {{-- Tabel Detail --}}
            <table class="detail-table">
                <tr>
                    <th>ID Peminjaman</th>
                    <td>#{{ $schedule->id }}</td>
                </tr>
                <tr>
                    <th>Nama Peralatan</th>
                    <td>{{ $schedule->inventory->name ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Tipe Peralatan</th>
                    <td>{{ $schedule->inventory->type ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Waktu Mulai</th>
                    <td>{{ $schedule->start_time->format('d M Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <th>Waktu Selesai</th>
                    <td>{{ $schedule->end_time->format('d M Y, H:i') }} WIB</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td><strong style="color: #27ae60;">{{ ucfirst(str_replace('_', ' ', $schedule->status)) }}</strong></td>
                </tr>
            </table>

            {{-- Info Box --}}
            <div class="info-box">
                <p>
                    📌 <strong>Penting:</strong> Pastikan Anda mengembalikan peralatan
                    sebelum waktu selesai yang telah ditentukan. Terima kasih atas
                    kerjasamanya!
                </p>
            </div>
        </div>

        {{-- Footer --}}
        <div class="email-footer">
            <p class="app-name">Smart-Hub Management System</p>
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} Smart-Hub. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
