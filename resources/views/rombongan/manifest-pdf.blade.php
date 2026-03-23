<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manifest Penumpang - {{ $rombongan->nama }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 3px solid #15803d;
            padding-bottom: 15px;
        }
        .logo-section {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        .logo-box {
            width: 60px;
            height: 60px;
            background-color: #15803d;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }
        .logo-text {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }
        .header-title {
            text-align: left;
        }
        .header h1 {
            font-size: 22px;
            color: #15803d;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .header h2 {
            font-size: 16px;
            color: #333;
            margin-bottom: 3px;
        }
        .header p {
            font-size: 10px;
            color: #666;
        }
        .manifest-title {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f0fdf4;
            border: 2px solid #15803d;
            border-radius: 5px;
        }
        .manifest-title h2 {
            font-size: 18px;
            color: #15803d;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .manifest-title h3 {
            font-size: 14px;
            color: #333;
        }
        .info-section {
            margin-bottom: 20px;
            padding: 12px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 5px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
            color: #555;
            padding: 4px 0;
        }
        .info-value {
            display: table-cell;
            color: #333;
            padding: 4px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 10px;
        }
        thead {
            background-color: #15803d;
            color: white;
        }
        th {
            padding: 8px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #0d5a2a;
        }
        th.text-center {
            text-align: center;
        }
        td {
            padding: 6px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .signature-section {
            margin-top: 40px;
            display: table;
            width: 100%;
        }
        .signature-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 20px;
        }
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 50px;
            padding-top: 5px;
            text-align: center;
            font-size: 10px;
        }
        .signature-line strong {
            font-size: 11px;
        }
        .footer {
            margin-top: 25px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-lunas {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-belum {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .summary-box {
            margin-top: 15px;
            padding: 12px;
            background-color: #f0fdf4;
            border: 1px solid #15803d;
            border-radius: 5px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header / Kop Surat dengan Logo -->
    <div class="header">
        <div class="logo-section">
            <div class="logo-box">
                <span class="logo-text">IK</span>
            </div>
            <div class="header-title">
                <h1>IKPM KALBAR</h1>
                <h2>Ikatan Keluarga Pondok Modern</h2>
                <p>Kalimantan Barat</p>
            </div>
        </div>
    </div>

    <!-- Judul Manifest -->
    <div class="manifest-title">
        <h2>MANIFEST PENUMPANG {{ strtoupper($rombongan->moda_transportasi) }}</h2>
        <h3>{{ strtoupper($rombongan->nama) }}</h3>
    </div>

    <!-- Info Rombongan -->
    <div class="info-section">
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Nama Bus/Rombongan:</div>
                <div class="info-value">{{ $rombongan->nama }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Waktu Berangkat:</div>
                <div class="info-value">{{ $rombongan->waktu_berangkat->format('d F Y, H:i') }} WIB</div>
            </div>
            <div class="info-row">
                <div class="info-label">Titik Kumpul:</div>
                <div class="info-value">{{ $rombongan->titik_kumpul ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Penanggung Jawab:</div>
                <div class="info-value">-</div>
            </div>
            <div class="info-row">
                <div class="info-label">Kapasitas:</div>
                <div class="info-value">{{ $rombongan->santris->count() }} / {{ $rombongan->kapasitas }} kursi</div>
            </div>
        </div>
    </div>

    <!-- Tabel Manifest -->
    <table>
        <thead>
            <tr>
                <th style="width: 4%;" class="text-center">No</th>
                <th style="width: 10%;">Stambuk</th>
                <th style="width: 25%;">Nama Lengkap</th>
                <th style="width: 8%;" class="text-center">Kelas</th>
                <th style="width: 15%;">No HP (Wali)</th>
                <th style="width: 12%;" class="text-center">No Kursi</th>
                <th style="width: 16%;" class="text-center">Status Bayar</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rombongan->santris as $index => $santri)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $santri->stambuk }}</td>
                <td>{{ $santri->nama }}</td>
                <td class="text-center">{{ $santri->kelas ?? '-' }}</td>
                <td>
                    @if($santri->user && $santri->user->no_hp)
                        {{ $santri->user->no_hp }}
                    @else
                        -
                    @endif
                </td>
                <td class="text-center">{{ $santri->pivot->nomor_kursi ?? '-' }}</td>
                <td class="text-center">
                    <span class="badge {{ $santri->pivot->status_pembayaran === 'lunas' ? 'badge-lunas' : 'badge-belum' }}">
                        {{ ucfirst(str_replace('_', ' ', $santri->pivot->status_pembayaran)) }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 20px; color: #999;">
                    Belum ada santri yang terdaftar dalam rombongan ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Summary -->
    @if($rombongan->santris->count() > 0)
    <div class="summary-box">
        <div style="display: table; width: 100%;">
            <div style="display: table-cell; width: 50%;">
                <strong>Total Penumpang:</strong> {{ $rombongan->santris->count() }} orang
            </div>
            <div style="display: table-cell; width: 50%; text-align: right;">
                <strong>Pembayaran Lunas:</strong> {{ $rombongan->santris->where('pivot.status_pembayaran', 'lunas')->count() }} orang
            </div>
        </div>
    </div>
    @endif

    <!-- Tanda Tangan -->
    <div class="signature-section">
        <div class="signature-box">
            <div class="signature-line">
                <strong>Supir</strong><br>
                {{ $rombongan->nama }}
            </div>
        </div>
        <div class="signature-box">
            <div class="signature-line">
                Pontianak, {{ date('d F Y') }}<br>
                <strong>Ketua Rombongan</strong>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh Sistem Manajemen IKPM Kalbar</p>
        <p>Dicetak pada: {{ date('d F Y, H:i:s') }} WIB</p>
    </div>
</body>
</html>
