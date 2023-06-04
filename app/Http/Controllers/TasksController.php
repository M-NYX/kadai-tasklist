<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\Task;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            
            
                //タスク一覧を取得
                //$tasks = Task::all();
                
                $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
                
                // タスク一覧ビューでそれを表示
                return view('tasks.index', ['tasks' => $tasks,
                ]);

        }else{
				return view('layouts.top');
        }
    }
    



    public function create()
    {
        if (\Auth::check()){
            
            $task = new Task;
		
		    return view('tasks.create', [
		    'task' => $task,
		    ]);
		
        }else{
            return redirect('/');
        }
		
    }



    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'content' => 'required',
            'status' => 'required|max:10',
        ]);
        
        //$task = new Task;
		//$task->content = $request->content;
		//$task->status = $request->status;
		//$task->save();
		
		//return redirect('/');
		
		// 認証済みユーザ（閲覧者）のタスクとして作成（リクエストされた値をもとに作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
        ]);
        
        return redirect('/');
    }



    public function show($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() == $task->user_id) {
		
		return view('tasks.show', [
		'task' => $task,
		]);
		
        }else{
            
            return view('layouts.top');
        }
    }


    public function edit($id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() == $task->user_id) {
		
    		return view('tasks.edit', [
    		'task' => $task,
    		]);
        }else{
            return view('layouts.top');
        }
    }



    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        
        if (\Auth::id() === $task->user_id) {
            
            $request->validate([
                'content' => 'required',
                'status' => 'required|max:10',
            ]);
            
            $task = Task::findOrFail($id);
    
    		$task->content = $request->content;
    		$task->status = $request->status;
    		$task->save();
    		
    		return redirect('/');
        }
    }



    public function destroy($id)
    {
        //$task = Task::findOrFail($id);
        
		//$task->delete();
		
		//return redirect('/');
		
        // idの値で投稿を検索して取得
        $task = \App\Models\Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がその投稿の所有者である場合は投稿を削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return redirect('/');
        }
        
        return back();
        
    }
}
