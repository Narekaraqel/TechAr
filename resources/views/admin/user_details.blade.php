@extends('../.app')

@section('title')
<title>Admin | User Devices</title>
@endsection

@include('admin.inc.header_admin')

@section('body')

<style>
/* ✅ Только для этой страницы */
.admin-user-view{
  --bg:#070b14;
  --bg2:#0b1020;
  --card: rgba(255,255,255,.05);
  --border: rgba(255,255,255,.10);
  --text:#eaf0ff;
  --muted:#9aa6c1;
  --accent:#00d2ff;
  --green:#00ff88;
  --red:#ff4d4d;
  --shadow: 0 18px 40px rgba(0,0,0,.45);

  min-height: 100vh;
  padding: 18px;
  color: var(--text);
  font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
  background:
    radial-gradient(900px 500px at 15% 10%, rgba(0,210,255,.16), transparent 60%),
    radial-gradient(700px 400px at 85% 20%, rgba(0,255,136,.10), transparent 60%),
    linear-gradient(180deg, var(--bg), var(--bg2));
}

.admin-user-view .wrap{
  max-width: 1100px;
  margin: 0 auto;
}

.admin-user-view .topbar{
  display:flex;
  align-items:center;
  justify-content: space-between;
  gap: 12px;
  padding: 14px 16px;
  border-radius: 18px;
  border: 1px solid var(--border);
  background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
  backdrop-filter: blur(10px);
  box-shadow: var(--shadow);
  margin-bottom: 14px;
}

.admin-user-view .topbar h2{
  margin:0;
  font-size: 1.05rem;
  font-weight: 900;
  letter-spacing: .2px;
}

.admin-user-view .sub{
  margin: 4px 0 0;
  color: var(--muted);
  font-size: .85rem;
}

.admin-user-view .btn{
  display:inline-flex;
  align-items:center;
  gap: 8px;
  padding: 10px 14px;
  border-radius: 999px;
  border: 1px solid var(--border);
  background: rgba(255,255,255,.04);
  color: var(--text);
  text-decoration: none;
  font-weight: 800;
  transition: .2s ease;
}
.admin-user-view .btn:hover{
  transform: translateY(-1px);
  background: rgba(255,255,255,.07);
}

.admin-user-view .panel{
  border: 1px solid var(--border);
  background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
  border-radius: 18px;
  padding: 16px;
  box-shadow: var(--shadow);
  backdrop-filter: blur(10px);
}

.admin-user-view .grid{
  display:grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
  margin-top: 14px;
}

.admin-user-view .card{
  position: relative;
  overflow:hidden;
  border-radius: 18px;
  padding: 16px;
  border: 1px solid var(--border);
  background: linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.03));
  box-shadow: var(--shadow);
}

.admin-user-view .card.accent:before{
  content:"";
  position:absolute;
  inset:-2px;
  background: radial-gradient(420px 120px at 10% 0%, rgba(0,210,255,.20), transparent 60%);
  pointer-events:none;
}
.admin-user-view .card.green:before{
  content:"";
  position:absolute;
  inset:-2px;
  background: radial-gradient(420px 120px at 10% 0%, rgba(0,255,136,.16), transparent 60%);
  pointer-events:none;
}

.admin-user-view .card h4{
  margin:0 0 10px 0;
  font-size: .82rem;
  text-transform: uppercase;
  letter-spacing: .12em;
  color: var(--muted);
}

.admin-user-view .kv{
  display:flex;
  justify-content: space-between;
  gap: 12px;
  padding: 10px 12px;
  border-radius: 14px;
  border: 1px solid rgba(255,255,255,.08);
  background: rgba(255,255,255,.03);
  margin-bottom: 10px;
}

.admin-user-view .k{
  color: var(--muted);
  font-weight: 700;
  font-size: .9rem;
  word-break: break-word;
}
.admin-user-view .v{
  font-weight: 900;
  font-size: .95rem;
  text-align: right;
}

.admin-user-view .badge{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  padding: 6px 10px;
  border-radius: 999px;
  font-weight: 900;
  font-size: .78rem;
  border: 1px solid rgba(255,255,255,.12);
}

.admin-user-view .on{
  background: rgba(0,255,136,.14);
  border-color: rgba(0,255,136,.28);
  color: #d7ffe8;
}
.admin-user-view .off{
  background: rgba(255,77,77,.12);
  border-color: rgba(255,77,77,.26);
  color: #ffd6d6;
}

.admin-user-view .updated{
  margin-top: 10px;
  color: var(--muted);
  font-size: .8rem;
}

.admin-user-view .empty{
  margin-top: 12px;
  color: var(--muted);
  padding: 14px;
  border-radius: 14px;
  border: 1px dashed rgba(255,255,255,.18);
  background: rgba(255,255,255,.03);
}

/* Responsive */
@media (max-width: 820px){
  .admin-user-view .grid{ grid-template-columns: 1fr; }
  .admin-user-view .topbar{ flex-direction: column; align-items: flex-start; }
}
</style>

<div class="admin-user-view">
  <div class="wrap">

    <div class="topbar">
      <div>
        <h2>Устройства пользователя: {{ $user->name }}</h2>
        <p class="sub">Просмотр последних данных датчиков и состояния реле</p>
      </div>

      <a href="{{ route('admin.dashboard') }}" class="btn">← Назад</a>
    </div>

    <div class="panel">
      @if($latestData)

        <div class="grid">

          {{-- SENSORS --}}
          <div class="card accent">
            <h4>Датчики</h4>
            @foreach($latestData->sensors_data as $key => $val)
              <div class="kv">
                <div class="k">{{ strtoupper(str_replace('_',' ', $key)) }}</div>
                <div class="v">{{ $val }}</div>
              </div>
            @endforeach
          </div>

          {{-- RELAYS --}}
          <div class="card green">
            <h4>Реле</h4>
            @foreach($latestData->rele_data as $key => $val)
              <div class="kv">
                <div class="k">{{ strtoupper(str_replace('_',' ', $key)) }}</div>
                <div class="v">
                  <span class="badge {{ $val == 1 ? 'on' : 'off' }}">
                    {{ $val == 1 ? 'ВКЛ' : 'ВЫКЛ' }}
                  </span>
                </div>
              </div>
            @endforeach

            <div class="updated">
              Обновлено: {{ \Carbon\Carbon::parse($latestData->updated_at)->format('d.m.Y H:i:s') }}
            </div>
          </div>

        </div>

      @else
        <div class="empty">
          У этого пользователя еще нет данных.
        </div>
      @endif
    </div>

  </div>
</div>

@endsection