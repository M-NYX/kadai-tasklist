<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
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
		$data = [];
        if (\Auth::check()) { // 認証済みの場合
            // 認証済みユーザを取得
            $user = \Auth::user();
            // ユーザのタスクの一覧を作成日時の降順で取得
            // （後のChapterで他ユーザのタスクも取得するように変更しますが、現時点ではこのユーザのタスクのみ取得します）
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
        }
        
        // dashboardビューでそれらを表示
        return view('dashboard', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    //public function create()
    //{
    //   $task = new Task;
	//	
	//	return view('tasks.create', [
	//	'task' => $task,
	//	]);
    //}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
        
        // 前のURLへリダイレクトさせる
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function show($id)
    //{
    //    $task = Task::findOrFail($id);
		
	//	return view('tasks.show', [
	//	'task' => $task,
	//	]);
    //}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function edit($id)
    //{
    //    $task = Task::findOrFail($id);
		
	//	return view('tasks.edit', [
	//	'task' => $task,
	//	]);
    //}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    //public function update(Request $request, $id)
    //{
    //    $request->validate([
    //        'content' => 'required',
    //        'status' => 'required|max:10',
    //    ]);
    //    
    //    $task = Task::findOrFail($id);

	//	$task->content = $request->content;
	//	$task->status = $request->status;
	//	$task->save();
		
	//	return redirect('/');
    //}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //$task = Task::findOrFail($id);
        
		//$task->delete();
		
		//return redirect('/');
		
		// idの値でタスクを検索して取得
        $task = \App\Models\Task::findOrFail($id);
        
        // 認証済みユーザ（閲覧者）がそのタスクの所有者である場合はタスクを削除
        if (\Auth::id() === $task->user_id) {
            $task->delete();
            return back()
                ->with('success','Delete Successful');
        }

        // 前のURLへリダイレクトさせる
        return back()
            ->with('Delete Failed');
    }
}
