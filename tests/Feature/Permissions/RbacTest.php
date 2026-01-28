<?php

declare(strict_types=1);

use App\Modules\Permissions\Enums\Roles;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;

uses(RefreshDatabase::class);

beforeEach(function () {
    Artisan::call('sync:permissions');
});

it('can check if a user is a super admin or a normal user', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole(Roles::SuperAdmin->value);

    $normalUser = User::factory()->create();
    $normalUser->assignRole(Roles::User->value);

    expect($superAdmin->hasRole(Roles::SuperAdmin))->toBeTrue()
        ->and($superAdmin->hasRole(Roles::User))->toBeFalse()
        ->and($normalUser->hasRole(Roles::SuperAdmin))->toBeFalse()
        ->and($normalUser->hasRole(Roles::User))->toBeTrue();
});

it('grants full access to the super admin via gate before.', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole(Roles::SuperAdmin->value);

    $otherUser = User::factory()->create();
    $project = Project::factory()->create(['owner_id' => $otherUser->id]);

    expect($superAdmin->can('view', $project))->toBeTrue();
});

it('restricts access to the normal user based on permissions and ownership.', function () {
    $user = User::factory()->create();
    $user->assignRole(Roles::User->value);

    $otherUser = User::factory()->create();
    $projectOfOther = Project::factory()->create(['owner_id' => $otherUser->id]);

    $myProject = Project::factory()->create(['owner_id' => $user->id]);

    expect($user->can('view', $projectOfOther))->toBeFalse()
        ->and($user->can('view', $myProject))->toBeTrue();
});
