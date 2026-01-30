<?php

use App\Modules\Permissions\Enums\Roles;
use App\Modules\Permissions\Models\Role;
use App\Modules\Project\Filament\Resources\ProjectResource;
use App\Modules\Project\Filament\Resources\ProjectResource\Pages\CreateProject;
use App\Modules\Project\Filament\Resources\ProjectResource\Pages\ListProjects;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Livewire\Livewire;

use function Pest\Laravel\actingAs;

beforeEach(function (): void {
    Role::create(['name' => Roles::SuperAdmin->value, 'guard_name' => 'web']);
    $this->user = User::factory()->create();
    $this->user->assignRole(Roles::SuperAdmin->value);
});

it('can list projects', function (): void {
    Project::factory()->count(5)->create(['owner_id' => $this->user->id]);

    actingAs($this->user)
        ->get(ProjectResource::getUrl('index'))
        ->assertSuccessful();
});

it('has a create button on the list page', function (): void {
    actingAs($this->user)
        ->get(ProjectResource::getUrl('index'))
        ->assertSuccessful()
        ->assertSee('New project')
        ->assertSee('projects/create');
});

it('has edit and delete actions in the table', function (): void {
    $project = Project::factory()->create(['owner_id' => $this->user->id]);

    Livewire::test(ListProjects::class)
        ->assertTableActionExists('edit', record: $project)
        ->assertTableActionExists('delete', record: $project);
});

it('has name and description fields in the form', function (): void {
    actingAs($this->user);

    Livewire::test(CreateProject::class)
        ->assertSee('name')
        ->assertSee('description')
        ->assertSee('status');
});
