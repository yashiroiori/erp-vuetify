<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

// Roles
Breadcrumbs::for('role.index', function ($trail) {
    $trail->push('Roles', route('role.index'));
});

// Roles > Upload Role
Breadcrumbs::for('role.create', function ($trail) {
    $trail->parent('role.index');
    $trail->push('Nuevo Rol', route('role.create'));
});

// Roles > [Role Name]
Breadcrumbs::for('role.show', function ($trail, $role) {
    $trail->parent('role.index');
    $trail->push($role->name, route('role.show', $role->id));
});

// Roles > [Role Name] > Edit Role
Breadcrumbs::for('role.edit', function ($trail, $role) {
    $trail->parent('role.show', $role);
    $trail->push('Editar Rol', route('role.edit', $role->id));
});