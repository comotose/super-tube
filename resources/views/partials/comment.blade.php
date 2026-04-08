<div class="border rounded p-2 mb-2">
    <div class="d-flex justify-content-between align-items-start">
        <div class="me-2">
            <div class="small">
                <span class="fw-semibold">{{ $comment->user?->name ?? '—' }}</span>
                <span class="text-muted">· {{ $comment->created_at?->format('d.m.Y H:i') }}</span>
                @if($comment->edited_at)
                    <span class="text-muted">· Обновлено {{ $comment->edited_at->format('d.m.Y H:i') }}</span>
                @endif
            </div>
            <div>{{ $comment->body }}</div>
        </div>
    </div>

    @auth
        <div class="mt-2 d-flex flex-wrap gap-2">
            <button class="btn btn-outline-secondary btn-sm" type="button"
                    onclick="document.getElementById('reply-form-{{ $comment->id }}').classList.toggle('d-none')">
                Ответить
            </button>

            @if(auth()->id() === $comment->user_id)
                <button class="btn btn-outline-primary btn-sm" type="button"
                        onclick="document.getElementById('edit-form-{{ $comment->id }}').classList.toggle('d-none')">
                    Редактировать
                </button>
            @endif
        </div>

        <div class="mt-2 d-none" id="reply-form-{{ $comment->id }}">
            <form method="POST" action="{{ route('comments.store', $video) }}">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                <div class="mb-2">
                    <textarea class="form-control" name="body" rows="2" placeholder="Ваш ответ..." required></textarea>
                </div>
                <button class="btn btn-dark btn-sm" type="submit">Отправить</button>
            </form>
        </div>

        @if(auth()->id() === $comment->user_id)
            <div class="mt-2 d-none" id="edit-form-{{ $comment->id }}">
                <form method="POST" action="{{ route('comments.update', $comment) }}">
                    @csrf
                    <div class="mb-2">
                        <textarea class="form-control" name="body" rows="2" required>{{ $comment->body }}</textarea>
                    </div>
                    <button class="btn btn-primary btn-sm" type="submit">Сохранить</button>
                </form>
            </div>
        @endif
    @endauth

    @if($comment->replies && $comment->replies->count())
        <div class="mt-2 ms-3">
            @foreach($comment->replies->sortBy('id') as $reply)
                @include('partials.comment', ['comment' => $reply, 'video' => $video])
            @endforeach
        </div>
    @endif
</div>

