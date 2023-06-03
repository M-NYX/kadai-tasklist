<!--　ログインユーザ自身の情報が表示されるUserのページからTaskが登録できるように-->
@if (Auth::id() == $user->id)
    <div class="mt-4">
        <form method="POST" action="{{ route('tasks.store') }}">
            @csrf
        
            <div class="form-control mt-4">
                <h2>タスク新規作成</h2>
                <p>タスクContent</p>
                <textarea rows="2" name="content" class="input input-bordered w-full"></textarea>
            </div>
            
            <div class="form-control mt-4" style="margin: 5px, 0, 5px, 0;">
                <p>タスクStatus</p>
                <textarea rows="2" name="status" class="input input-bordered w-full"></textarea>
            </div>
        
            <button type="submit" class="btn btn-primary btn-block normal-case">Post</button>
        </form>
    </div>
@endif