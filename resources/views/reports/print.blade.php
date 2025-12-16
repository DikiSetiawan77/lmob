<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Kinerja Harian - {{ $user->name }}</title>
    <style>
        /* --- PERUBAHAN KUNCI DI SINI --- */
        @page {
            size: 33cm 21.5cm; /* Ukuran F4 Landscape (lebar x tinggi) */
            margin: 1.5cm 2cm; /* Margin Atas/Bawah 1.5cm, Kiri/Kanan 2cm */
        }
        /* --- AKHIR PERUBAHAN --- */

        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 12pt; 
            line-height: 1.5;
        }
        .header { 
            text-align: center; 
            margin-bottom: 20px; 
            line-height: 1.2;
        }
        .header h3, .header h4 { 
            margin: 0; 
            font-weight: bold;
        }
        .bio-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .bio-table td {
            padding: 2px 0;
        }
        .bio-table td:first-child {
            width: 100px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 8px; 
            text-align: left; 
            vertical-align: top;
        }
        th { 
            background-color: #e9ecef; 
            text-align: center;
            font-weight: bold;
        }
        .uraian-tugas { 
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .signature-section {
            margin-top: 50px;
            width: 100%;
        }
        .signature-block {
            width: 30%; /* Disesuaikan untuk landscape */
            float: right;
            text-align: center;
        }
        .signature-block .signature-space {
            height: 70px;
        }
        .signature-block .name {
            font-weight: bold;
            text-decoration: underline;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    <div class="header">
        <h3>LAPORAN KINERJA HARIAN PEGAWAI</h3>
        <h4>BKPSDM KABUPATEN SUBANG</h4>
    </div>

    <table class="bio-table" style="border: none !important;">
        <tr style="border: none !important;"><td style="border: none !important;"><strong>Nama</strong></td><td style="border: none !important;">: {{ $user->name }}</td></tr>
        <tr style="border: none !important;"><td style="border: none !important;"><strong>NIP</strong></td><td style="border: none !important;">: {{ $user->nip ?? '-' }}</td></tr>
        <tr style="border: none !important;"><td style="border: none !important;"><strong>Periode</strong></td><td style="border: none !important;">: {{ $monthName }} {{ $year }}</td></tr>
    </table>

    <table>
        <thead>
            <tr>
                <th style="width:12%;">Tanggal</th>
                <th style="width:15%;">Jam Kegiatan</th>
                <th style="width:20%;">Judul Kegiatan</th>
                <th>Uraian Tugas</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($reports as $report)
                <tr>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($report->date)->format('d/m/Y') }}</td>
                    <td style="text-align: center;">
                        {{ \Carbon\Carbon::parse($report->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($report->end_time)->format('H:i') }}
                    </td>
                    <td>{{ $report->title }}</td>
                    <td class="uraian-tugas">{{ $report->description }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; height: 100px;">Tidak ada laporan kinerja pada periode ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section clearfix">
        <div class="signature-block">
            <p>Subang, {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
            <p>Siswa yang bersangkutan,</p>
            <div class="signature-space"></div>
            <p class="name">{{ $user->name }}</p>
            <p>NIP. {{ $user->nip ?? '-' }}</p>
        </div>
    </div>
</body>
</html>