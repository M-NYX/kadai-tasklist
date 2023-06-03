<div class="mt-4">
    @if (isset($tasks))
        <ul class="list-none">
            @foreach ($tasks as $task)
                <li class="flex items-start gap-x-2 mb-4">
                    {{-- タスクの所有者のメールアドレスをもとにGravatarを取得して表示 --}}
                    <div class="avatar">
                        <div class="w-12 rounded">
                            <img src="{{ Gravatar::get($task->user->email) }}" alt="" />
                        </div>
                    </div>
                    <div>
                        <div>
                            {{-- タスクの所有者のユーザ詳細ページへのリンク --}}
                            <a class="link link-hover text-info" href="{{ route('users.show', $task->user->id) }}">{{ $task->user->name }}</a>
                            <span class="text-muted text-gray-500">posted at {{ $task->created_at }}</span>
                        </div>
                        <div>
                            {{-- タスクコンテンツ内容 --}}
                            <p class="mb-0">コンテンツ：{!! nl2br(e($task->content)) !!}</p>
                            {{-- タスクステータス内容 --}}
                            <p class="mb-0">ステータス：{!! nl2br(e($task->status)) !!}</p>
                        </div>
                        <div>
                            @if (Auth::id() == $task->user_id)
                                {{-- タスク削除ボタンのフォーム --}}
                                <form method="POST" action="{{ route('tasks.destroy', $task->id) }}" class="my-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-error btn-outline" 
                                    onclick="return confirm('id = {{ $task->id }} のタスクを削除します。よろしいですか？')">削除</button>
                                </form>
                            @endif
                        </div>
                        <div>
                            @if (Auth::id() == $task->user_id)
                               {{-- タスク編集ページへのリンク --}}
                                <a class="btn btn-outline" href="{{ route('tasks.edit', $task->id) }}">このタスクを編集</a>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
        {{-- ページネーションのリンク --}}
        {{ $tasks->links() }}
    @endif
</div>