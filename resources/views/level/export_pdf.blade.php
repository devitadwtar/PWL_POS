<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            margin: 6px 20px 5px 20px;
            line-height: 1.4;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 4px 3px;
            border: 1px solid #000;
        }
        th {
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .d-block {
            display: block;
        }
        .font-10 { font-size: 10pt; }
        .font-11 { font-size: 11pt; }
        .font-12 { font-size: 12pt; }
        .font-13 { font-size: 13pt; }
        .font-bold { font-weight: bold; }
        .border-bottom-header {
            border-bottom: 1px solid #000;
        }
        img.image {
            height: 80px;
            max-height: 150px;
        }
    </style>
</head>
<body>

    <table class="border-bottom-header">
        <tr>
            <td width="15%" class="text-center">
                <img src="{{ public_path('polinema-bw.png') }}" class="image">
            </td>
            <td width="85%">
                <span class="text-center d-block font-11 font-bold">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</span>
                <span class="text-center d-block font-13 font-bold">POLITEKNIK NEGERI MALANG</span>
                <span class="text-center d-block font-10">Jl. Soekarno-Hatta No. 9 Malang 65141</span>
                <span class="text-center d-block font-10">Telepon (0341) 404424 Pes. 101-105, 0341-404420, Fax. (0341) 404420</span>
                <span class="text-center d-block font-10">Laman: www.polinema.ac.id</span>
            </td>
        </tr>
    </table>

    <h3 class="text-center" style="margin-top: 20px;">LAPORAN DATA LEVEL</h3>

    <table>
        <thead>
            <tr>
                <th class="text-center" style="width: 10%;">No</th>
                <th style="width: 30%;">Kode Level</th>
                <th style="width: 60%;">Nama Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($level as $l)
            <tr>
                <td class="text-center">{{ $loop->iteration }}</td>
                <td>{{ $l->level_kode ?? '-' }}</td>
                <td>{{ $l->level_nama }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
