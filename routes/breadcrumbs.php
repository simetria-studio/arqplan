<?php

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Home', route('home'));
});



// Profile
Breadcrumbs::for('profile', function ($trail) {
    $trail->parent('home');
    $trail->push('Meus dados', route('profile'));
});

//produtos
Breadcrumbs::for('products', function ($trail) {
    $trail->parent('home');
    $trail->push('Produtos', route('products'));
});



// Company
Breadcrumbs::for('company', function ($trail) {
    $trail->parent('home');
    $trail->push('Empresa', route('company'));
});
Breadcrumbs::for('company.users', function ($trail) {
    $trail->parent('home');
    $trail->push('Usuários', route('company.users'));
});



// Calendar
Breadcrumbs::for('calendar', function ($trail) {
    $trail->parent('home');
    $trail->push('Agenda', route('calendar'));
});



// Task
Breadcrumbs::for('task', function ($trail) {
    $trail->parent('home');
    $trail->push('Atividades', route('task'));
});



// Projetos
Breadcrumbs::for('project', function ($trail) {
    $trail->parent('home');
    $trail->push('Projetos', route('project'));
});
Breadcrumbs::for('project.new', function ($trail) {
    $trail->parent('project');
    $trail->push('Novo', route('project.new'));
});
Breadcrumbs::for('project.category', function ($trail) {
    $trail->parent('project');
    $trail->push('Categorias', route('project.category.new'));
});
Breadcrumbs::for('project.status', function ($trail) {
    $trail->parent('project');
    $trail->push('Status', route('project.status.new'));
});
Breadcrumbs::for('project.step', function ($trail) {
    $trail->parent('project');
    $trail->push('Etapas', route('project.step.new'));
});



// Clients
Breadcrumbs::for('client', function ($trail) {
    $trail->parent('home');
    $trail->push('Clientes', route('client'));
});
Breadcrumbs::for('client.new', function ($trail) {
    $trail->parent('client');
    $trail->push('Novo', route('client.new'));
});



// Providers
Breadcrumbs::for('provider', function ($trail) {
    $trail->parent('home');
    $trail->push('Fornecedores', route('provider'));
});
Breadcrumbs::for('provider.new', function ($trail) {
    $trail->parent('provider');
    $trail->push('Novo', route('provider.new'));
});



// Finance
Breadcrumbs::for('finance', function ($trail) {
    $trail->parent('home');
    $trail->push('Financeiro', route('finance'));
});
Breadcrumbs::for('finance.new', function ($trail) {
    $trail->parent('finance');
    $trail->push('Nova Transação', route('finance.new'));
});
Breadcrumbs::for('finance.category', function ($trail) {
    $trail->parent('finance');
    $trail->push('Categorias', route('finance.category'));
});



// Report
Breadcrumbs::for('report', function ($trail) {
    $trail->parent('home');
    $trail->push('Relatórios', route('report'));
});



// Users
Breadcrumbs::for('user', function ($trail) {
    $trail->parent('home');
    $trail->push('Usuários', route('user'));
});
Breadcrumbs::for('user.new', function ($trail) {
    $trail->parent('user');
    $trail->push('Novo', route('user.new'));
});











// Admin

// ADMIN
Breadcrumbs::for('admin', function ($trail) {
    $trail->push('Admin');
});

Breadcrumbs::for('admin.company', function ($trail) {
    $trail->parent('admin');
    $trail->push('Empresas', route('admin.company'));
});

Breadcrumbs::for('admin.user', function ($trail) {
    $trail->parent('admin');
    $trail->push('Usuários', route('admin.user'));
});

Breadcrumbs::for('admin.subscribers', function ($trail) {
    $trail->parent('admin');
    $trail->push('Inscritos', route('admin.subscribers'));
});

Breadcrumbs::for('admin.demonstration_requests', function ($trail) {
    $trail->parent('admin');
    $trail->push('Solicitações de Demonstração', route('admin.demonstration_requests'));
});

// Admin > template
Breadcrumbs::for('template', function ($trail) {
    $trail->parent('home');
    $trail->push('Template', route('template'));
});












// Home > Blog
Breadcrumbs::for('blog', function ($trail) {
    $trail->parent('home');
    $trail->push('Blog', route('blog'));
});

// Home > Blog > [Category]
Breadcrumbs::for('category', function ($trail, $category) {
    $trail->parent('blog');
    $trail->push($category->title, route('category', $category->id));
});

// Home > Blog > [Category] > [Post]
Breadcrumbs::for('post', function ($trail, $post) {
    $trail->parent('category', $post->category);
    $trail->push($post->title, route('post', $post->id));
});
