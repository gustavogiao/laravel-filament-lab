<?php

declare(strict_types=1);

use App\Modules\Permissions\Enums\Roles;
use App\Modules\Project\Models\Project;
use App\Modules\User\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Gate;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Sincroniza as permissões e papéis antes de cada teste
    Artisan::call('sync:permissions');
});

it('pode verificar se um usuário é super admin ou user normal', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole(Roles::SuperAdmin->value);

    $normalUser = User::factory()->create();
    $normalUser->assignRole(Roles::User->value);

    // Verificação via método hasRole (Spatie)
    expect($superAdmin->hasRole(Roles::SuperAdmin))->toBeTrue()
        ->and($superAdmin->hasRole(Roles::User))->toBeFalse()
        ->and($normalUser->hasRole(Roles::SuperAdmin))->toBeFalse()
        ->and($normalUser->hasRole(Roles::User))->toBeTrue();
});

it('concede acesso total ao super admin via gate before', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole(Roles::SuperAdmin->value);

    // Criamos um projeto que não pertence ao super admin
    $otherUser = User::factory()->create();
    $project = Project::factory()->create(['owner_id' => $otherUser->id]);

    // O SuperAdmin deve conseguir ver o projeto de qualquer pessoa devido ao Gate::before
    expect($superAdmin->can('view', $project))->toBeTrue();
});

it('restringe acesso ao usuário normal baseado em permissões e ownership', function () {
    $user = User::factory()->create();
    $user->assignRole(Roles::User->value);

    // Projeto de outro usuário
    $otherUser = User::factory()->create();
    $projectOfOther = Project::factory()->create(['owner_id' => $otherUser->id]);

    // Projeto do próprio usuário
    $myProject = Project::factory()->create(['owner_id' => $user->id]);

    // Usuário normal não pode ver projeto de outros
    expect($user->can('view', $projectOfOther))->toBeFalse();

    // Usuário normal pode ver seu próprio projeto (se tiver a permissão view_projects)
    // Nota: O sync:permissions já atribui view_projects ao Role::User no config/rbac.php
    expect($user->can('view', $myProject))->toBeTrue();
});
