<?php

use App\Modules\Project\Filament\Resources\ProjectResource;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    \App\Modules\Permissions\Models\Role::create(['name' => \App\Modules\Permissions\Enums\Roles::SuperAdmin->value, 'guard_name' => 'web']);
    $this->user = User::factory()->create();
    $this->user->assignRole(\App\Modules\Permissions\Enums\Roles::SuperAdmin->value);
});

it('can list projects', function () {
    Project::factory()->count(5)->create(['owner_id' => $this->user->id]);

    actingAs($this->user)
        ->get(ProjectResource::getUrl('index'))
        ->assertSuccessful();
});

it('has a create button on the list page', function () {
    actingAs($this->user)
        ->get(ProjectResource::getUrl('index'))
        ->assertSuccessful()
        ->assertSee('New project')
        ->assertSee('projects/create');
});

it('has edit and delete actions in the table', function () {
    $project = Project::factory()->create(['owner_id' => $this->user->id]);

    Livewire::test(ProjectResource\Pages\ListProjects::class)
        ->assertTableActionExists('edit', record: $project)
        ->assertTableActionExists('delete', record: $project);
});

it('has name and description fields in the form', function () {
    actingAs($this->user);

    Livewire::test(ProjectResource\Pages\CreateProject::class)
        ->assertSee('name')
        ->assertSee('description')
        ->assertSee('Active');
});
