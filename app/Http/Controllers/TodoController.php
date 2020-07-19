<?php

namespace App\Http\Controllers;

use App\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $todos = auth()->user()->todos();

        if ($request->unfulfilled == 'true') {
            $todos = $todos->where('completed', false);
        }

        if ($request->completed == 'true') {
            $todos = $todos->where('completed', true);
        }

        return response()->json([
            'status' => 'success',
            'todos'  => $todos->latest()->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        auth()->user()->todos()->create($validated);

        return messageResponse('success', 'Todo item created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        return response()->json([
            'status' => 'success',
            'todo'   => $todo,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Todo $todo)
    {
        $validated = $request->validate([
            'name' => 'required',
        ]);

        $todo->update($validated);

        return messageResponse('success', 'Todo item updated successfully.');
    }

    /**
     * Mark todo as completed
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function complete(Todo $todo)
    {
        $todo->update([
            'completed' => true,
        ]);

        return messageResponse('success', 'Todo item completed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Todo $todo)
    {
        if (!$todo->completed) {
            return messageResponse('error', 'Todo item not completed yet.', 400);
        }

        $todo->delete();

        return messageResponse('success', 'Todo item deleted successfully.');
    }
}
