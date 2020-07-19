<?php

namespace Tests\Feature;

use App\Todo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Test if users can fetch todos
     *
     * @return void
     */
    public function testUsersCanFetchTodos()
    {
        $user = factory(User::class)->create();

        factory(Todo::class, 10)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->get(route('todos.index'))->assertJson([
            'status' => 'success',
            'todos'  => $user->todos()->latest()->get()->toArray(),
        ])->assertStatus(200);
    }

    /**
     * Test if users can fetch unfulfilled todos
     *
     * @return void
     */
    public function testUsersCanFetchUnfulfilledTodos()
    {
        $user = factory(User::class)->create();

        factory(Todo::class, 3)->create([
            'user_id' => $user->id,
        ]);

        factory(Todo::class, 3)->create([
            'user_id'   => $user->id,
            'completed' => true,
        ]);

        $this->actingAs($user)->get(route('todos.index') . '?unfulfilled=true')->assertJson([
            'status' => 'success',
            'todos'  => $user->todos()->where('completed', false)->latest()->get()->toArray(),
        ])->assertStatus(200);
    }

    /**
     * Test if users can fetch completed todos
     *
     * @return void
     */
    public function testUsersCanFetchCompletedTodos()
    {
        $user = factory(User::class)->create();

        factory(Todo::class, 3)->create([
            'user_id' => $user->id,
        ]);

        factory(Todo::class, 3)->create([
            'user_id'   => $user->id,
            'completed' => true,
        ]);

        $this->actingAs($user)->get(route('todos.index') . '?completed=true')->assertJson([
            'status' => 'success',
            'todos'  => $user->todos()->where('completed', true)->latest()->get()->toArray(),
        ])->assertStatus(200);
    }

    /**
     * Test users can create todos
     *
     * @return void
     */
    public function testUsersCanCreateTodos()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)->post(route('todos.store'), [
            'name' => $this->faker->sentence,
        ])->assertJson([
            'status'  => 'success',
            'message' => 'Todo item created successfully.',
        ])->assertStatus(200);

        $todo = $user->refresh()->todos()->first();

        $this->assertDatabaseHas('todos', ['id' => $todo->id]);
    }

    /**
     * Test users can view todo
     *
     * @return void
     */
    public function testUsersCanViewTodos()
    {
        $user = factory(User::class)->create();

        $todo = factory(Todo::class)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->get(route('todos.show', $todo))->assertJson([
            'status' => 'success',
            'todo'   => $todo->toArray(),
        ])->assertStatus(200);
    }

    /**
     * Test users can edit todos
     *
     * @return void
     */
    public function testUsersCanEditTodos()
    {
        $user = factory(User::class)->create();

        $todo = factory(Todo::class)->create([
            'user_id' => $user->id,
        ]);

        $name = $this->faker->sentence;

        $this->actingAs($user)->put(route('todos.update', $todo), [
            'name' => $name,
        ])->assertJson([
            'status'  => 'success',
            'message' => 'Todo item updated successfully.',
        ])->assertStatus(200);

        $todo = $todo->refresh();

        $this->assertEquals($name, $todo->name);
    }

    /**
     * Test users can mark todo as completed
     *
     * @return void
     */
    public function testUsersCanCompleteTodos()
    {
        $user = factory(User::class)->create();

        $todo = factory(Todo::class)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->post(route('todos.complete', $todo))->assertJson([
            'status'  => 'success',
            'message' => 'Todo item completed successfully.',
        ])->assertStatus(200);

        $todo = $todo->refresh();

        $this->assertTrue($todo->completed);
    }

    /**
     * Test users can delete a todo
     *
     * @return void
     */
    public function testUsersCanDeleteTodos()
    {
        $user = factory(User::class)->create();

        $todo = factory(Todo::class)->create([
            'user_id' => $user->id,
        ]);

        $this->actingAs($user)->delete(route('todos.destroy', $todo))->assertJson([
            'status'  => 'error',
            'message' => 'Todo item not completed yet.',
        ])->assertStatus(400);

        $todo = factory(Todo::class)->create([
            'user_id'   => $user->id,
            'completed' => true,
        ]);

        $this->actingAs($user)->delete(route('todos.destroy', $todo))->assertJson([
            'status'  => 'success',
            'message' => 'Todo item deleted successfully.',
        ])->assertStatus(200);

        $this->assertDatabaseMissing('todos', ['id' => $todo->id]);
    }
}
