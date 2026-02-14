@extends('../.app')



@section('title')

    <title>IoT Dashboard | {{ $user->name }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection




@include('user.inc.header_user')



@section('body')


    
    
    

<body>
<style>
        :root {
            --bg: #0b0e14; --card: #151c26; --text: #fff; --dim: #8a94a6;
            --accent: #00d2ff; --green: #00ff88; --red: #ff4d4d;
        }
        body { background: var(--bg); color: var(--text); font-family: sans-serif; margin: 0; padding: 15px; }

        /* HEADER */
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; flex-wrap: wrap; gap: 10px; }
        .logout-btn { background: transparent; border: 1px solid var(--red); color: var(--red); padding: 8px 15px; border-radius: 5px; cursor: pointer; }

        /* GRID SYSTEM (Адаптивная сетка) */
        .grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px; 
        }

        .card { background: var(--card); padding: 20px; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); }
        .card-title { color: var(--dim); font-size: 0.8rem; text-transform: uppercase; margin-bottom: 10px; }
        .val { font-size: 2.5rem; font-weight: bold; }
        .unit { font-size: 1rem; color: var(--dim); }

        /* TOGGLE SWITCH (РЕЛЕ) */
        .rele-row { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; padding: 10px; background: rgba(255,255,255,0.03); border-radius: 8px; }
        .switch { position: relative; display: inline-block; width: 50px; height: 26px; }
        .switch input { opacity: 0; width: 0; height: 0; }
        .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #333; transition: .4s; border-radius: 34px; }
        .slider:before { position: absolute; content: ""; height: 20px; width: 20px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
        input:checked + .slider { background-color: var(--green); }
        input:checked + .slider:before { transform: translateX(24px); }

        /* CHART SECTION */
        .chart-controls { display: flex; gap: 10px; margin-bottom: 15px; flex-wrap: wrap; }
        input[type="datetime-local"] { background: #222; border: 1px solid #444; color: white; padding: 8px; border-radius: 5px; }
        .btn-load { background: var(--accent); border: none; padding: 8px 20px; border-radius: 5px; color: #000; font-weight: bold; cursor: pointer; }
    </style>
    <div class="header">
        <h2>Привет, {{ $user->name }}</h2>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="logout-btn">Выйти</button>
        </form>
    </div>

    <div class="grid">
        @if($data && $data->sensors_data)
            @foreach($data->sensors_data as $k => $v)
                <div class="card">
                    <div class="card-title">{{ strtoupper(str_replace('_', ' ', $k)) }}</div>
                    <div class="val">{{ $v }} <span class="unit">
                        @if(str_contains($k, 'temp')) °C @elseif(str_contains($k, 'hum')) % @else val @endif
                    </span></div>
                </div>
            @endforeach
        @endif

        <div class="rele-container">
    @if($data && $data->rele_data)
        @foreach($data->rele_data as $name => $status)
            <div class="rele-row" style="display: flex; flex-direction: column; align-items: stretch; gap: 5px;">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span style="font-weight: bold;">{{ strtoupper($name) }}</span>
                    
                    <label class="switch">
                        <input type="checkbox" 
                               {{ $status == 1 ? 'checked' : '' }} 
                               onchange="toggleRele('{{ $name }}')">
                        <span class="slider"></span>
                    </label>
                </div>
                
                {{-- Добавляем время последнего изменения --}}
                <div style="font-size: 0.7rem; color: var(--dim); margin-top: -5px;">
                    Последнее изменение: {{ $data->updated_at->format('d.m.Y H:i:s') }}
                </div>
            </div>
        @endforeach
    @endif
</div>
    </div>

    <div class="card">
        <div class="card-title">История показаний</div>
        
        <div class="chart-controls">
    <label style="color: var(--text-dim); font-size: 0.9rem;">От:</label>
    <input type="datetime-local" 
           id="start-date" 
           min="{{ $minDate }}" 
           max="{{ $maxDate }}"
           value="{{ $minDate }}"> <label style="color: var(--text-dim); font-size: 0.9rem;">До:</label>
    <input type="datetime-local" 
           id="end-date" 
           min="{{ $minDate }}" 
           max="{{ $maxDate }}"
           value="{{ $maxDate }}"> <button class="btn-load" onclick="loadChart()">Показать</button>
</div>

        <div style="position: relative; height: 400px; width: 100%;">
            <canvas id="myChart"></canvas>
        </div>
    </div>

    <script>
        // 1. ЛОГИКА РЕЛЕ
        function toggleRele(releName) {
    fetch("{{ route('rele.update') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ rele_name: releName })
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            // Опционально: можно найти элемент с датой и обновить его текущим временем
            // Но проще всего просто подождать следующего обновления страницы 
            // или добавить вот такую строку для мгновенного эффекта:
            location.reload(); // Это самый простой способ обновить все метки времени сразу
        } else {
            alert("Ошибка при переключении!");
        }
    });
}
        // 2. ЛОГИКА ГРАФИКА
        let myChart = null;

        function loadChart() {
            const start = document.getElementById('start-date').value;
            const end = document.getElementById('end-date').value;

            if(!start || !end) return alert("Выберите даты!");

            fetch("{{ route('data.history') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ start: start, end: end })
            })
            .then(res => res.json())
            .then(data => {
                renderChart(data.labels, data.datasets);
            });
        }

        function renderChart(labels, datasetsData) {
            const ctx = document.getElementById('myChart').getContext('2d');
            
            // Генерация цветов для разных линий
            const colors = ['#00d2ff', '#a855f7', '#00ff88', '#ff4d4d', '#ffaa00'];
            let datasets = [];
            let i = 0;

            // Превращаем сырые данные в формат Chart.js
            for (const [key, values] of Object.entries(datasetsData)) {
                datasets.push({
                    label: key.toUpperCase(),
                    data: values,
                    borderColor: colors[i % colors.length], // Выбираем цвет по кругу
                    backgroundColor: 'transparent',
                    borderWidth: 2,
                    tension: 0.4 // Плавность линий
                });
                i++;
            }

            if (myChart) myChart.destroy(); // Удаляем старый график перед рисовкой нового

            myChart = new Chart(ctx, {
                type: 'line',
                data: { labels: labels, datasets: datasets },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { grid: { color: '#333' }, ticks: { color: '#888' } },
                        x: { grid: { color: '#333' }, ticks: { color: '#888' } }
                    },
                    plugins: { legend: { labels: { color: '#fff' } } }
                }
            });
        }
    </script>



@endsection