@extends('../.app')

@section('title')
    <title>IoT Dashboard | {{ $user->name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@include('user.inc.header_user')

@section('body')
<style>
    :root{
        --bg0:#070A12;
        --bg1:#0B0F1A;
        --card: rgba(255,255,255,.06);
        --card2: rgba(255,255,255,.08);
        --stroke: rgba(255,255,255,.10);
        --text:#EAF0FF;
        --muted: rgba(234,240,255,.62);

        --accent:#00D2FF;
        --accent2:#7C3AED;
        --good:#00FF88;
        --bad:#FF4D4D;
        --warn:#FFB020;

        --r16:16px;
        --r20:20px;
        --shadow: 0 18px 55px rgba(0,0,0,.45);
    }

    *{ box-sizing:border-box; }
    body{
        margin:0;
        font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Helvetica Neue", sans-serif;
        color:var(--text);
        background:
          radial-gradient(1200px 700px at 15% 10%, rgba(0,210,255,.12), transparent 55%),
          radial-gradient(900px 600px at 85% 20%, rgba(124,58,237,.14), transparent 60%),
          radial-gradient(900px 600px at 60% 95%, rgba(0,255,136,.08), transparent 55%),
          linear-gradient(180deg, var(--bg0), var(--bg1));
    }

    .wrap{
        max-width: 1200px;
        margin: 0 auto;
        padding: 18px 14px 40px;
    }

    /* TOP BAR */
    .topbar{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        flex-wrap:wrap;
        margin-bottom: 18px;
    }
    .hello{
        display:flex; flex-direction:column; gap:4px;
    }
    .hello h2{
        margin:0;
        font-size: 1.25rem;
        letter-spacing:.2px;
    }
    .hello small{
        color:var(--muted);
        font-size:.9rem;
    }

    .actions{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
    }

    .chip{
        display:inline-flex;
        align-items:center;
        gap:8px;
        padding:10px 12px;
        border-radius:999px;
        background: rgba(255,255,255,.05);
        border: 1px solid var(--stroke);
        box-shadow: 0 10px 25px rgba(0,0,0,.25);
        color: var(--muted);
        font-size:.9rem;
        backdrop-filter: blur(10px);
    }
    .dot{
        width:10px; height:10px; border-radius:50%;
        background: var(--good);
        box-shadow: 0 0 0 4px rgba(0,255,136,.10);
    }

    .btn{
        appearance:none;
        border:0;
        border-radius: 999px;
        padding:10px 14px;
        font-weight:700;
        cursor:pointer;
        transition: transform .12s ease, filter .12s ease, background .12s ease, border-color .12s ease;
        user-select:none;
        display:inline-flex;
        align-items:center;
        gap:8px;
    }
    .btn:active{ transform: translateY(1px) scale(.99); }

    .btn-ghost{
        background: rgba(255,255,255,.04);
        border: 1px solid var(--stroke);
        color: var(--text);
        backdrop-filter: blur(10px);
    }
    .btn-ghost:hover{ filter: brightness(1.08); }

    .btn-danger{
        background: rgba(255,77,77,.10);
        border: 1px solid rgba(255,77,77,.25);
        color: #FFD7D7;
    }
    .btn-danger:hover{ filter: brightness(1.08); }

    /* GRID */
    .grid{
        display:grid;
        grid-template-columns: repeat(12, 1fr);
        gap:14px;
        margin-top: 10px;
    }

    .card{
        background: linear-gradient(180deg, rgba(255,255,255,.07), rgba(255,255,255,.04));
        border: 1px solid var(--stroke);
        border-radius: var(--r20);
        box-shadow: var(--shadow);
        backdrop-filter: blur(12px);
    }

    /* SENSOR CARD */
    .metric{
        grid-column: span 3;
        padding: 16px 16px 14px;
        position:relative;
        overflow:hidden;
        min-height: 98px;
    }
    @media (max-width: 1100px){ .metric{ grid-column: span 4; } }
    @media (max-width: 820px){ .metric{ grid-column: span 6; } }
    @media (max-width: 520px){ .metric{ grid-column: span 12; } }

    .metric:before{
        content:"";
        position:absolute;
        inset:-60px -60px auto auto;
        width:180px; height:180px;
        background: radial-gradient(circle at 30% 30%, rgba(0,210,255,.22), transparent 60%);
        transform: rotate(20deg);
        pointer-events:none;
    }
    .metric .k{
        color: var(--muted);
        font-size: .78rem;
        letter-spacing:.10em;
        text-transform:uppercase;
        margin-bottom: 10px;
    }
    .metric .v{
        display:flex;
        align-items:baseline;
        gap:10px;
    }
    .metric .num{
        font-size: 2.15rem;
        font-weight: 800;
        line-height: 1;
        letter-spacing: .2px;
    }
    .metric .unit{
        color: var(--muted);
        font-size: .95rem;
        font-weight: 600;
    }

    /* RELAY PANEL */
    .relays{
        grid-column: span 4;
        padding: 16px;
    }
    @media (max-width: 1100px){ .relays{ grid-column: span 12; } }

    .relays-head{
        display:flex; align-items:center; justify-content:space-between;
        margin-bottom: 12px;
        gap:12px;
    }
    .relays-head .title{
        font-size: .9rem;
        letter-spacing:.10em;
        text-transform:uppercase;
        color: var(--muted);
    }
    .relays-list{
        display:flex;
        flex-direction:column;
        gap:10px;
        max-height: 360px;
        overflow:auto;
        padding-right: 4px;
    }
    .relays-list::-webkit-scrollbar{ width: 8px; }
    .relays-list::-webkit-scrollbar-thumb{
        background: rgba(255,255,255,.10);
        border-radius: 99px;
    }

    .relay-row{
        display:flex;
        flex-direction:column;
        gap:8px;
        padding: 12px 12px;
        border-radius: var(--r16);
        background: rgba(255,255,255,.04);
        border: 1px solid rgba(255,255,255,.08);
    }
    .relay-top{
        display:flex; align-items:center; justify-content:space-between; gap:12px;
    }
    .relay-name{
        font-weight:800;
        letter-spacing:.06em;
        font-size:.9rem;
    }
    .relay-meta{
        color: var(--muted);
        font-size: .78rem;
    }

    /* SWITCH */
    .switch{
        position: relative;
        width: 56px;
        height: 32px;
        flex: 0 0 auto;
    }
    .switch input{ display:none; }
    .track{
        position:absolute; inset:0;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.14);
        transition: background .18s ease, border-color .18s ease, box-shadow .18s ease;
    }
    .thumb{
        position:absolute;
        top: 4px; left: 4px;
        width: 24px; height: 24px;
        border-radius: 50%;
        background: rgba(255,255,255,.92);
        box-shadow: 0 8px 20px rgba(0,0,0,.35);
        transition: transform .18s ease;
    }
    .switch input:checked + .track{
        background: linear-gradient(90deg, rgba(0,255,136,.35), rgba(0,210,255,.25));
        border-color: rgba(0,255,136,.35);
        box-shadow: 0 0 0 6px rgba(0,255,136,.10);
    }
    .switch input:checked + .track .thumb{
        transform: translateX(24px);
        background: #EFFFF7;
    }

    /* CHART CARD */
    .chart{
        grid-column: span 12;
        padding: 16px;
    }
    .chart-head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        flex-wrap:wrap;
        gap:12px;
        margin-bottom: 12px;
    }
    .chart-title{
        font-size: .9rem;
        letter-spacing:.10em;
        text-transform:uppercase;
        color: var(--muted);
    }

    .controls{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        align-items:center;
        justify-content:flex-end;
    }
    .controls label{
        color: var(--muted);
        font-size:.85rem;
    }
    input[type="datetime-local"], select{
        background: rgba(0,0,0,.25);
        border: 1px solid rgba(255,255,255,.14);
        color: var(--text);
        padding: 10px 12px;
        border-radius: 12px;
        outline:none;
        box-shadow: inset 0 0 0 1px rgba(255,255,255,.05);
    }
    input[type="datetime-local"]:focus{
        border-color: rgba(0,210,255,.45);
        box-shadow: 0 0 0 6px rgba(0,210,255,.10);
    }
    .btn-accent{
        background: linear-gradient(90deg, rgba(0,210,255,.95), rgba(124,58,237,.85));
        color: #061018;
        padding: 10px 16px;
        border-radius: 14px;
        font-weight: 900;
    }
    .btn-accent:hover{ filter: brightness(1.05); }

    .chart-box{
        position:relative;
        height: 420px;
        width: 100%;
        border-radius: var(--r16);
        background: rgba(0,0,0,.18);
        border: 1px solid rgba(255,255,255,.08);
        overflow:hidden;
    }

    /* Small helper */
    .muted{ color: var(--muted); }
</style>

<div class="wrap">
    <div class="topbar">
        <div class="hello">
            <h2>Привет, {{ $user->name }}</h2>
            <small class="muted">Мониторинг датчиков и управление реле</small>
        </div>

        <div class="actions">
            <div class="chip">
                <span class="dot"></span>
                <span>Online</span>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger">Выйти</button>
            </form>
        </div>
    </div>

    <div class="grid">
        {{-- SENSOR CARDS --}}
        @if($data && $data->sensors_data)
            @foreach($data->sensors_data as $k => $v)
                <div class="card metric">
                    <div class="k">{{ strtoupper(str_replace('_', ' ', $k)) }}</div>
                    <div class="v">
                        <div class="num">{{ $v }}</div>
                        <div class="unit">
                            @if(str_contains($k, 'temp')) °C
                            @elseif(str_contains($k, 'hum')) %
                            @elseif(str_contains($k, 'co2')) ppm
                            @else val
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

        {{-- RELAYS --}}
        <div class="card relays">
            <div class="relays-head">
                <div class="title">Реле</div>
                <div class="muted" style="font-size:.85rem;">
                    @if($data) Обновлено: {{ $data->updated_at->format('d.m.Y H:i:s') }} @endif
                </div>
            </div>

            <div class="relays-list">
                @if($data && $data->rele_data)
                    @foreach($data->rele_data as $name => $status)
                        <div class="relay-row">
                            <div class="relay-top">
                                <div class="relay-name">{{ strtoupper($name) }}</div>

                                <label class="switch" title="Переключить">
                                    <input type="checkbox"
                                           {{ $status == 1 ? 'checked' : '' }}
                                           onchange="toggleRele('{{ $name }}')">
                                    <span class="track">
                                        <span class="thumb"></span>
                                    </span>
                                </label>
                            </div>

                            <div class="relay-meta">
                                Последнее изменение: {{ $data->updated_at->format('d.m.Y H:i:s') }}
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="muted">Нет данных по реле</div>
                @endif
            </div>
        </div>

        {{-- CHART --}}
        <div class="card chart">
            <div class="chart-head">
                <div class="chart-title">История показаний</div>

                <div class="controls">
                    <label>От:</label>
                    <input type="datetime-local"
                           id="start-date"
                           min="{{ $minDate }}"
                           max="{{ $maxDate }}"
                           value="{{ $minDate }}">

                    <label>До:</label>
                    <input type="datetime-local"
                           id="end-date"
                           min="{{ $minDate }}"
                           max="{{ $maxDate }}"
                           value="{{ $maxDate }}">

                    <button class="btn btn-accent" type="button" onclick="loadChart()">Показать</button>
                </div>
            </div>

            <div class="chart-box">
                <canvas id="myChart"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
    // 1) RELAY
    function toggleRele(releName) {
        fetch("{{ route('rele.update') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ rele_name: releName })
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                // можно без перезагрузки, но самый простой вариант:
                location.reload();
            } else {
                alert("Ошибка при переключении!");
            }
        })
        .catch(() => alert("Ошибка сети"));
    }

    // 2) CHART
    let myChart = null;

    function loadChart() {
        const start = document.getElementById('start-date').value;
        const end = document.getElementById('end-date').value;
        if (!start || !end) return alert("Выберите даты!");

        fetch("{{ route('data.history') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ start, end })
        })
        .then(res => res.json())
        .then(data => renderChart(data.labels, data.datasets))
        .catch(() => alert("Ошибка загрузки графика"));
    }

    function renderChart(labels, datasetsData) {
        const ctx = document.getElementById('myChart').getContext('2d');

        const colors = ['#00d2ff', '#7c3aed', '#00ff88', '#ff4d4d', '#ffb020', '#38bdf8', '#22c55e'];
        let datasets = [];
        let i = 0;

        for (const [key, values] of Object.entries(datasetsData)) {
            datasets.push({
                label: key.toUpperCase(),
                data: values,
                borderColor: colors[i % colors.length],
                backgroundColor: 'transparent',
                borderWidth: 2,
                tension: 0.35,
                pointRadius: 0
            });
            i++;
        }

        if (myChart) myChart.destroy();

        myChart = new Chart(ctx, {
            type: 'line',
            data: { labels, datasets },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { labels: { color: '#EAF0FF' } },
                    tooltip: {
                        backgroundColor: 'rgba(10,12,18,.92)',
                        titleColor: '#EAF0FF',
                        bodyColor: 'rgba(234,240,255,.85)',
                        borderColor: 'rgba(255,255,255,.12)',
                        borderWidth: 1
                    }
                },
                scales: {
                    y: { grid: { color: 'rgba(255,255,255,.08)' }, ticks: { color: 'rgba(234,240,255,.65)' } },
                    x: { grid: { color: 'rgba(255,255,255,.06)' }, ticks: { color: 'rgba(234,240,255,.55)' } }
                }
            }
        });
    }
</script>
@endsection