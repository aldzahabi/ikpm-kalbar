<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Santri — IKPM</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 9pt; color: #111; }
        h1 { font-size: 14pt; margin-bottom: 4px; }
        .meta { font-size: 8pt; color: #555; margin-bottom: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ccc; padding: 4px 6px; text-align: left; }
        th { background: #15803d; color: #fff; }
        tr:nth-child(even) { background: #f9fafb; }
    </style>
</head>
<body>
    @php
        $statusFilter = request('status');
        $isUstadFilter = $statusFilter === 'ustad';
    @endphp
    <h1>Daftar {{ $isUstadFilter ? 'Ustad' : ($statusFilter === 'alumni' ? 'Alumni' : 'Santri') }}</h1>
    <div class="meta">Dicetak: {{ $printedAt->format('d/m/Y H:i') }} — Total: {{ $santris->count() }} baris</div>
    <table>
        <thead>
            <tr>
                <th>Stambuk</th>
                <th>Nama</th>
                <th>NIK</th>
                <th>Provinsi</th>
                <th>Daerah</th>
                <th>{{ $isUstadFilter ? 'Tahun' : 'Kelas' }}</th>
                <th>Pondok</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($santris as $s)
            <tr>
                <td>{{ $s->stambuk }}</td>
                <td>{{ $s->nama }}</td>
                <td>{{ $s->nik ?? '—' }}</td>
                <td>{{ $s->provinsi }}</td>
                <td>{{ $s->daerah }}</td>
                <td>
                    @if($s->status === 'ustad' && $s->kelas)
                        Tahun Ke-{{ $s->kelas }}
                    @else
                        {{ $s->kelas ?? '—' }}
                    @endif
                </td>
                <td>
                    @php
                        $p = $s->pondok_cabang ? (\App\Models\Santri::getPondokCabangList()[$s->pondok_cabang] ?? $s->pondok_cabang) : '—';
                    @endphp
                    {{ $p }}
                </td>
                <td>{{ $s->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
