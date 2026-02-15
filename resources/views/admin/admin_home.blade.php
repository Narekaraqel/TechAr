@extends('../.app')

@section('title')
<title>Admin home</title>
@endsection

@include('admin.inc.header_admin')

@section('body')

<style>
/* ===== Base ===== */
:root{
  --bg1:#070A12;
  --bg2:#0B1220;
  --card: rgba(255,255,255,.06);
  --card2: rgba(255,255,255,.08);
  --border: rgba(255,255,255,.10);
  --text:#EAF0FF;
  --muted: rgba(234,240,255,.65);
  --cyan:#00D2FF;
  --green:#00FF88;
  --red:#FF4D4D;
  --shadow: 0 18px 55px rgba(0,0,0,.45);
}

body{
  margin:0;
  min-height:100vh;
  background:
    radial-gradient(1200px 600px at 10% 10%, rgba(0,210,255,.20), transparent 55%),
    radial-gradient(900px 500px at 90% 15%, rgba(0,255,136,.14), transparent 60%),
    radial-gradient(900px 500px at 50% 110%, rgba(141,84,255,.12), transparent 55%),
    linear-gradient(180deg, var(--bg1), var(--bg2));
  color:var(--text);
  font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Arial, "Noto Sans", "Helvetica Neue", sans-serif;
  padding: 28px 18px;
}

/* ===== Container ===== */
.admin-wrap{
  max-width: 1180px;
  margin: 0 auto;
}

/* ===== Topbar ===== */
.topbar{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:14px;
  margin-bottom: 18px;
}

.title{
  display:flex;
  flex-direction:column;
  gap:4px;
}
.title h1{
  margin:0;
  font-size: 26px;
  letter-spacing:.2px;
}
.title p{
  margin:0;
  color:var(--muted);
  font-size: 13px;
}

/* ===== Pills / user info ===== */
.pill{
  display:flex;
  align-items:center;
  gap:10px;
  background: rgba(255,255,255,.05);
  border: 1px solid var(--border);
  padding: 10px 12px;
  border-radius: 999px;
  backdrop-filter: blur(14px);
}

.pill b{ font-weight:700; }
.pill small{ color:var(--muted); }

/* ===== Cards ===== */
.card{
  background: var(--card);
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 18px;
  box-shadow: var(--shadow);
  backdrop-filter: blur(18px);
}

.grid{
  display:grid;
  grid-template-columns: 1fr;
  gap: 14px;
}

/* ===== Stat card ===== */
.stat{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
}
.stat .big{
  font-size: 22px;
  font-weight: 800;
}
.stat .tag{
  font-size:12px;
  color: var(--muted);
}

/* ===== Buttons ===== */
.btn{
  border: none;
  cursor:pointer;
  padding: 10px 14px;
  border-radius: 12px;
  font-weight: 700;
  transition: .22s ease;
  display:inline-flex;
  align-items:center;
  gap:10px;
  text-decoration:none;
  user-select:none;
}

.btn-primary{
  background: linear-gradient(135deg, rgba(0,210,255,1), rgba(64,140,255,1));
  color:#061019;
  box-shadow: 0 10px 25px rgba(0,210,255,.25);
}
.btn-primary:hover{ transform: translateY(-2px); }

.btn-green{
  background: linear-gradient(135deg, rgba(0,255,136,1), rgba(0,210,255,1));
  color:#061019;
  box-shadow: 0 10px 25px rgba(0,255,136,.20);
}
.btn-green:hover{ transform: translateY(-2px); }

.btn-ghost{
  background: rgba(255,255,255,.05);
  color: var(--text);
  border: 1px solid var(--border);
}
.btn-ghost:hover{
  background: rgba(255,255,255,.08);
  transform: translateY(-2px);
}

.btn-danger{
  background: rgba(255,77,77,.10);
  color: #ffd7d7;
  border: 1px solid rgba(255,77,77,.25);
}
.btn-danger:hover{
  background: rgba(255,77,77,.16);
  transform: translateY(-2px);
}

/* ===== Form ===== */
.form-title{
  display:flex;
  align-items:center;
  justify-content:space-between;
  gap:10px;
  margin-bottom: 12px;
}
.form-title h3{
  margin:0;
  font-size: 16px;
}
.form-title span{
  color:var(--muted);
  font-size: 12px;
}

.form-grid{
  display:grid;
  grid-template-columns: repeat(3, minmax(0, 1fr));
  gap: 12px;
}

.input{
  width:100%;
  padding: 11px 12px;
  border-radius: 12px;
  border: 1px solid rgba(255,255,255,.14);
  background: rgba(0,0,0,.18);
  color: var(--text);
  outline:none;
  transition: .2s ease;
}
.input::placeholder{ color: rgba(234,240,255,.45); }
.input:focus{
  border-color: rgba(0,210,255,.55);
  box-shadow: 0 0 0 4px rgba(0,210,255,.12);
}

.form-actions{
  display:flex;
  align-items:center;
  justify-content:flex-end;
  gap:10px;
  margin-top: 12px;
  flex-wrap:wrap;
}

.alert{
  margin-top: 10px;
  padding: 10px 12px;
  border-radius: 12px;
  font-size: 13px;
  border: 1px solid var(--border);
  background: rgba(255,255,255,.04);
}
.alert.err{ border-color: rgba(255,77,77,.35); color:#ffd0d0; }
.alert.ok{ border-color: rgba(0,255,136,.35); color:#caffea; }

/* ===== Table ===== */
.table-card{ padding: 0; overflow: hidden; }

.table-head{
  padding: 14px 18px;
  display:flex;
  align-items:center;
  justify-content:space-between;
  border-bottom: 1px solid var(--border);
  background: rgba(255,255,255,.04);
}
.table-head h3{
  margin:0;
  font-size: 15px;
}
.table-head small{
  color: var(--muted);
}

.table{
  width:100%;
  border-collapse: collapse;
}
.table th, .table td{
  padding: 14px 18px;
  text-align:left;
  border-bottom: 1px solid rgba(255,255,255,.08);
  font-size: 14px;
}
.table th{
  font-size: 12px;
  letter-spacing:.6px;
  text-transform: uppercase;
  color: rgba(234,240,255,.65);
}
.table tr:hover td{
  background: rgba(0,210,255,.06);
}
.id-badge{
  display:inline-flex;
  align-items:center;
  gap:8px;
  padding: 6px 10px;
  border-radius: 999px;
  border: 1px solid rgba(0,210,255,.25);
  background: rgba(0,210,255,.08);
  font-weight: 800;
}
.actions{
  display:flex;
  justify-content:flex-end;
}

/* ===== Responsive ===== */
@media (max-width: 900px){
  .form-grid{ grid-template-columns: 1fr; }
  .actions{ justify-content:flex-start; }
  .table th:nth-child(3), .table td:nth-child(3){ display:none; } /* скрыть email на маленьких */
}
</style>

<div class="admin-wrap">

  {{-- Topbar --}}
  <div class="topbar">
    <div class="title">
      <h1>Панель администратора</h1>
      <p>Управление пользователями и устройствами (IoT)</p>
    </div>

    <div style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
      <div class="pill">
        <small>Админ:</small>
        <b>{{ Auth::user()->name }}</b>
      </div>

      <form action="{{ route('logout') }}" method="POST" style="margin:0;">
        @csrf
        <button type="submit" class="btn btn-danger">⎋ Выйти</button>
      </form>
    </div>
  </div>

  <div class="grid">

    {{-- Stats --}}
    <div class="card stat">
      <div>
        <div class="tag">Всего пользователей</div>
        <div class="big">{{ $total }}</div>
      </div>
      <a class="btn btn-ghost" href="{{ route('admin.dashboard') }}">⟳ Обновить</a>
    </div>

    {{-- Create user --}}
    <div class="card">
      <div class="form-title">
        <h3>➕ Добавить нового пользователя</h3>
        <span>Создай аккаунт и привяжи устройства</span>
      </div>

      <form action="{{ route('admin.user.create') }}" method="POST">
        @csrf

        <div class="form-grid">
          <input class="input" type="text" name="name" placeholder="Имя" required>
          <input class="input" type="email" name="email" placeholder="Email" required>
          <input class="input" type="password" name="password" placeholder="Пароль" required>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-green">✅ Создать пользователя</button>
        </div>

        @if($errors->any())
          <div class="alert err">⚠ {{ $errors->first() }}</div>
        @endif

        @if(session('success'))
          <div class="alert ok">✅ {{ session('success') }}</div>
        @endif
      </form>
    </div>

    {{-- Users table --}}
    <div class="card table-card">
      <div class="table-head">
        <h3>👥 Пользователи</h3>
        <small>Нажми “Просмотреть устройства” чтобы открыть данные</small>
      </div>

      <table class="table">
        <thead>
          <tr>
            <th style="width:120px;">ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th style="text-align:right;">Действие</th>
          </tr>
        </thead>
        <tbody>
          @foreach($users as $u)
            <tr>
              <td><span class="id-badge">#{{ $u->id }}</span></td>
              <td><b>{{ $u->name }}</b></td>
              <td style="color: rgba(234,240,255,.75);">{{ $u->email }}</td>
              <td>
                <div class="actions">
                  <a href="{{ route('admin.user.view', $u->id) }}" class="btn btn-primary">
                    ⚙ Просмотреть устройства
                  </a>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>

    </div>

  </div>
</div>

@endsection