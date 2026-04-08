@extends('layouts.app')

@section('title', 'Админ - Пользователи')

@section('content')
    <h1 class="h4 mb-3">Админ: пользователи</h1>

    <div class="alert alert-secondary">
        <div class="fw-semibold mb-1">Блокировки по ТЗ</div>
        <div class="small">
            - Теневой бан: пользователь видит свои видео, другие не видят.<br>
            - Постоянный бан: авторизация недоступна, видео не выводятся другим.
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-sm table-bordered align-middle bg-white">
            <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th class="text-center">Админ</th>
                <th class="text-center">Теневой бан</th>
                <th class="text-center">Постоянный бан</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $u)
                <tr>
                    <td>{{ $u->id }}</td>
                    <td>{{ $u->name }}</td>
                    <td>{{ $u->email }}</td>
                    <td class="text-center">{{ $u->is_admin ? 'да' : 'нет' }}</td>
                    <td class="text-center">{{ $u->is_shadow_banned ? 'да' : 'нет' }}</td>
                    <td class="text-center">{{ $u->is_perma_banned ? 'да' : 'нет' }}</td>
                    <td style="min-width: 260px;">
                        <form method="POST" action="{{ route('admin.users.update', $u) }}" class="d-flex gap-2 align-items-center">
                            @csrf
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_admin" value="1" id="a{{ $u->id }}" {{ $u->is_admin ? 'checked' : '' }}>
                                <label class="form-check-label" for="a{{ $u->id }}">админ</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_shadow_banned" value="1" id="s{{ $u->id }}" {{ $u->is_shadow_banned ? 'checked' : '' }}>
                                <label class="form-check-label" for="s{{ $u->id }}">теневой</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_perma_banned" value="1" id="p{{ $u->id }}" {{ $u->is_perma_banned ? 'checked' : '' }}>
                                <label class="form-check-label" for="p{{ $u->id }}">постоянный</label>
                            </div>
                            <button class="btn btn-dark btn-sm" type="submit">Сохранить</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{ $users->links() }}
@endsection

