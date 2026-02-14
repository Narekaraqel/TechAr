@extends('../.app')



@section('title')

<title>Admin home</title>

@endsection




@include('admin.inc.header_admin')



@section('body')



<div class="card">
    <a href="{{ route('admin.dashboard') }}" style="color: #888;">← Назад</a>
    <h2>Устройства пользователя: {{ $user->name }}</h2>

    @if($latestData)
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 20px;">
            <div class="card" style="border: 1px solid #00d2ff;">
                <h4>Датчики</h4>
                @foreach($latestData->sensors_data as $key => $val)
                    <p>{{ $key }}: <b>{{ $val }}</b></p>
                @endforeach
            </div>
            <div class="card" style="border: 1px solid #00ff88;">
                <h4>Реле</h4>
                @foreach($latestData->rele_data as $key => $val)
                    <p>{{ $key }}: <b>{{ $val == 1 ? 'ВКЛ' : 'ВЫКЛ' }}</b></p>
                @endforeach
                <p><small>Обновлено: {{ $latestData->updated_at }}</small></p>
            </div>
        </div>
    @else
        <p>У этого пользователя еще нет данных.</p>
    @endif
</div>

@endsection