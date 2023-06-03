<?php
	
	namespace App\Http\Controllers;
	
	use Illuminate\Http\Request;
	
	use Illuminate\Support\Facades\Auth;
	use App\Models\User;
	
	class UsersController extends Controller
	{
		public function index()     
		{
			// ユーザ一覧をidの降順で取得
			$users = User::orderBy('id', 'desc')->paginate(10);
			
			if (Auth::id() === $users){
				// ユーザ一覧ビューでそれを表示
				return view('users.index', [
					'users' => $users,
				]);
			}else{
				return view('layouts.top');
			}
		}
		
		
		public function show($id)
		{
			// idの値でユーザを検索して取得
	        $user = User::findOrFail($id);
	        
	        // 関係するモデルの件数をロード
	        $user->loadRelationshipCounts();
	        
	        // ユーザーのタスク一覧を作成日時の降順で取得
	        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
	        if (Auth::id() === $user->id){
		         // ユーザ詳細ビューでそれを表示
		        return view('users.show', [
		            'user' => $user,
		            'tasks' => $tasks,
		        ]);
	        }else{
				return view('layouts.top');
			}
		}
	}