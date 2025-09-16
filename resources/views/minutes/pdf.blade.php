<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Notulen Rapat</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 15px; }
        .label { font-weight: bold; display: block; margin-bottom: 5px; }
        .box { border: 1px solid #ccc; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h2>Notulen Rapat</h2>

    <div class="section">
        <span class="label">Judul Meeting:</span>
        {{-- Gunakan optional() supaya tidak error jika meeting null --}}
        <div class="box">{{ optional($minute->meeting)->title ?? 'Tidak ada judul meeting' }}</div>
    </div>

    <div class="section">
        <span class="label">Tanggal:</span>
        <div class="box">{{ $minute->created_at ? $minute->created_at->format('d M Y') : '-' }}</div>
    </div>

    <div class="section">
        <span class="label">Notulen:</span>
        <div class="box">{!! nl2br(e($minute->content)) !!}</div>
    </div>

    @if(!empty($minute->action_items))
    <div class="section">
        <span class="label">Tindak Lanjut:</span>
        <div class="box">{!! nl2br(e($minute->action_items)) !!}</div>
    </div>
    @endif

    <div class="section">
        <span class="label">Dibuat oleh:</span>
        {{-- Pastikan creator ada supaya tidak error --}}
        <div class="box">{{ optional($minute->creator)->name ?? 'Tidak diketahui' }}</div>
    </div>
</body>
</html>
