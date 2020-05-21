<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Users
Breadcrumbs::for('user.index', function ($trail) {
    $trail->push('Usuarios', route('user.index'));
});

// Users > Upload Role
Breadcrumbs::for('user.create', function ($trail) {
    $trail->parent('user.index');
    $trail->push('Nuevo Usuario', route('user.create'));
});

// Users > [Role Name]
Breadcrumbs::for('user.show', function ($trail, $user) {
    $trail->parent('user.index');
    $trail->push($user->name, route('user.show', $user->id));
});

// Users > [Role Name] > Edit Role
Breadcrumbs::for('user.edit', function ($trail, $user) {
    $trail->parent('user.show', $user);
    $trail->push('Editar Usuario', route('user.edit', $user->id));
});