<?php

declare(strict_types=1);

use App\Modules\Permissions\Enums\Roles;
use App\Modules\Project\Models\Project;
use App\Modules\Task\Models\Task;
use App\Modules\User\Models\User;

beforeEach(function (): void {
    $this->artisan('sync:permissions');
});

test('Super Admin can see any task', function (): void {
    $admin = User::factory()->create();
    $admin->assignRole(Roles::SuperAdmin->value);

    $user = User::factory()->create();
    $project = Project::factory()->create(['owner_id' => $user->id]);
    $task = Task::factory()->create([
        'project_id' => $project->id,
        'submitted_by_id' => $user->id,
    ]);

    expect($admin->can('view', $task))->toBeTrue();
});

test('User can see their own task', function (): void {
    $user = User::factory()->create();
    $user->assignRole(Roles::User->value);

    $project = Project::factory()->create(['owner_id' => $user->id]);
    $task = Task::factory()->create([
        'project_id' => $project->id,
        'submitted_by_id' => $user->id,
    ]);

    expect($user->can('view', $task))->toBeTrue();
});

test('User can see tasks in their own project even if submitted by another user', function (): void {
    $owner = User::factory()->create();
    $owner->assignRole(Roles::User->value);

    $otherUser = User::factory()->create();

    $project = Project::factory()->create(['owner_id' => $owner->id]);
    $task = Task::factory()->create([
        'project_id' => $project->id,
        'submitted_by_id' => $otherUser->id,
    ]);

    expect($owner->can('view', $task))->toBeTrue();
});

test('User cannot see tasks from other projects', function (): void {
    $user = User::factory()->create();
    $user->assignRole(Roles::User->value);

    $otherUser = User::factory()->create();
    $otherProject = Project::factory()->create(['owner_id' => $otherUser->id]);
    $task = Task::factory()->create([
        'project_id' => $otherProject->id,
        'submitted_by_id' => $otherUser->id,
    ]);

    expect($user->can('view', $task))->toBeFalse();
});

test('User can update their own task', function (): void {
    $user = User::factory()->create();
    $user->assignRole(Roles::User->value);

    $project = Project::factory()->create(['owner_id' => $user->id]);
    $task = Task::factory()->create([
        'project_id' => $project->id,
        'submitted_by_id' => $user->id,
    ]);

    expect($user->can('update', $task))->toBeTrue();
});

test('User can update tasks in their project', function (): void {
    $owner = User::factory()->create();
    $owner->assignRole(Roles::User->value);

    $otherUser = User::factory()->create();
    $project = Project::factory()->create(['owner_id' => $owner->id]);
    $task = Task::factory()->create([
        'project_id' => $project->id,
        'submitted_by_id' => $otherUser->id,
    ]);

    expect($owner->can('update', $task))->toBeTrue();
});
