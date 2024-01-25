<?php

namespace Tests\Feature;

use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class TaskTest extends TestCase
{
    public function testIndexReturnsDataInValidFormat()
    {
        $this->json('get', 'api/tasks')
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(
                [
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'is_completed',
                            'is_deleted',
                            'created_at'
                        ]
                    ]
                ]
            );
    }

    public function testTaskIsCreatedSuccessfully()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
            'is_completed'      => rand(Task::STATUS_IS_OPEN, Task::STATUS_IS_COMPLETED),
            'is_deleted'      => rand(Task::STATUS_IS_NOT_DELETED, Task::STATUS_IS_DELETED)
        ];
        $this->json('post', 'api/tasks', $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id',
                        'title',
                        'description',
                        'is_completed',
                        'is_deleted',
                        'created_at'
                    ]
                ]
            );
        $this->assertDatabaseHas('tasks', $payload);
    }

    public function testTaskIsShownCorrectly()
    {
        $faker = \Faker\Factory::create();

        $task = Task::create(
            [
                'title' => $faker->titleMale(),
                'description'  => $faker->realText(),
                'is_completed'      => rand(Task::STATUS_IS_OPEN, Task::STATUS_IS_COMPLETED),
                'is_deleted'      => rand(Task::STATUS_IS_NOT_DELETED, Task::STATUS_IS_DELETED),
            ]
        );

        $this->json('get', "api/tasks/$task->id")
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' => [
                        'id' => $task->id,
                        'title' => $task->title,
                        'description' => $task->description,
                        'is_completed' => $task->is_completed,
                        'is_deleted' => $task->is_deleted,
                        'created_at' => date(Task::FORMAT_CREATED_AT, strtotime($task->created_at))
                    ]
                ]
            );
    }

    public function testTaskUpdateSuccessfully()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
            'is_completed'      => rand(Task::STATUS_IS_OPEN, Task::STATUS_IS_COMPLETED),
            'is_deleted'      => rand(Task::STATUS_IS_NOT_DELETED, Task::STATUS_IS_DELETED),
        ];

        $this->json('put', 'api/tasks/1', $payload)
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(
                [
                    'data' => [
                        'title' => $payload['title'],
                        'description' => $payload['description'],
                        'is_completed' => (bool)$payload['is_completed'],
                        'is_deleted' => (bool)$payload['is_deleted']
                    ]
                ]
            );
    }

    public function testTaskIsDestroyedSuccessfully()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
        ];
        $task = Task::create(
            $payload
        );

        $this->json('delete', "api/tasks/$task->id")
            ->assertNoContent();
        $this->assertDatabaseMissing('tasks', $payload);
    }

    public function testTaskChangeCompletedStatus()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
        ];
        $task = Task::create(
            $payload
        );

        $this->json('get', "api/tasks/$task->id/set-as-completed")
            ->assertStatus(Response::HTTP_OK);


        $this->json('get', "api/tasks/$task->id/set-as-open")
            ->assertStatus(Response::HTTP_OK);
    }

    public function testTaskChangeDeletedStatus()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
        ];
        $task = Task::create(
            $payload
        );

        $this->json('get', "api/tasks/$task->id/set-as-deleted")
            ->assertStatus(Response::HTTP_OK);


        $this->json('get', "api/tasks/$task->id/set-as-not-deleted")
            ->assertStatus(Response::HTTP_OK);
    }




    public function testShowForMissingTask()
    {
        $this->json('get', "api/tasks/0")
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testUpdateForMissingTask()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
        ];

        $this->json('put', 'api/tasks/0', $payload)
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
    public function testUpdateTaskWithWrongTitleLenght()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->realText(300),
            'description'  => $faker->realText(),
        ];

        $this->json('put', 'api/tasks/1', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function testUpdateTaskWithWrongDescriptionLenght()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(900),
        ];

        $this->json('put', 'api/tasks/1', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function testUpdateTaskWithWrongDeletedFlag()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
            'is_deleted' => 4
        ];

        $this->json('put', 'api/tasks/1', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function testUpdateTaskWithWrongCompletedFlag()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
            'is_completed' => 4
        ];

        $this->json('put', 'api/tasks/1', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }


    public function testCreateTaskWithWrongTitleLength()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->realText(300),
            'description'  => $faker->realText()
        ];

        $this->json('post', 'api/tasks', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function testCreateTaskWithWrongDescriptionLength()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(900)
        ];

        $this->json('post', 'api/tasks', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function testCreateTaskWithWrongDeletedFlag()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
            'is_deleted' => 4
        ];

        $this->json('post', 'api/tasks', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    public function testCreateTaskWithWrongCompletedFlag()
    {
        $faker = \Faker\Factory::create();

        $payload = [
            'title' => $faker->titleMale(),
            'description'  => $faker->realText(),
            'is_completed' => 4
        ];

        $this->json('post', 'api/tasks', $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testDeteleTaskWithNotFoundId()
    {
        $this->json('delete', 'api/tasks/0')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testSetTaskAsCompletedWithNotFoundId()
    {
        $this->json('get', 'api/tasks/0/set-as-completed')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
    public function testSetTaskAsOpenWithNotFoundId()
    {
        $this->json('get', 'api/tasks/0/set-as-open')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
    public function testSetTaskAsDeletedWithNotFoundId()
    {
        $this->json('get', 'api/tasks/0/set-as-deleted')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
    public function testSetTaskAsNotDeletedWithNotFoundId()
    {
        $this->json('get', 'api/tasks/0/set-as-not-deleted')
            ->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
