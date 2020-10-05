<?php

use DaveJamesMiller\Breadcrumbs\Facades\Breadcrumbs;

/**
 * Breadcrumbs by Dave J. Miller
 * https://github.com/davejamesmiller/laravel-breadcrumbs
 */

// Dashboard
Breadcrumbs::register('dashboard', function ($breadcrumbs) {
    $breadcrumbs->push('Dashboard', route('dashboard'), ['icon' => '<i class="fa fa-dashboard"></i>']);
});

/**
 * Errors
 */

// Dashboard > 403
Breadcrumbs::register('403', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('403 Error', null);
});

/**
 * Assets
 */

// Dashboard > Categorieën
Breadcrumbs::register('assets', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Assets', route('assets.index'));
});

// Dashboard > Assets > [Asset]
Breadcrumbs::register('asset', function ($breadcrumbs, $asset) {
    $breadcrumbs->parent('categories');
    $breadcrumbs->push($asset->name, route('assets.show', $asset->id));
});

// Dashboard > Categorieën > Assets importeren
Breadcrumbs::register('assetImport', function ($breadcrumbs) {
    $breadcrumbs->parent('assets');
    $breadcrumbs->push('Assets importeren', route('assets.import'));
});

// Dashboard > Categorieën > Categorie aanmaken
Breadcrumbs::register('assetCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('assets');
    $breadcrumbs->push('Asset aanmaken', route('assets.create'));
});

/**
 * Categorieën
 */

// Dashboard > Categorieën
Breadcrumbs::register('categories', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Assets', route('categories.index'));
});

// Dashboard > Categorieën > [Category]
Breadcrumbs::register('category', function ($breadcrumbs, $category) {

    if ($category->parent) {
        $breadcrumbs->parent('category', $category->parent);
    } else {
        $breadcrumbs->parent('categories');
    }

    $breadcrumbs->push($category->name, route('categories.show', $category->id));
});

// Dashboard > Categorieën > Categorie aanmaken
Breadcrumbs::register('categoryCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('categories');
    $breadcrumbs->push('Categorie aanmaken', route('categories.create'));
});

/**
 * Breslocaties
 */

// Dashboard > Breslocaties
Breadcrumbs::register('breaches', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Breslocaties', route('breaches.index'));
});

// Dashboard > Breslocaties > [Breslocatie]
Breadcrumbs::register('breach', function ($breadcrumbs, $breach) {
    $breadcrumbs->parent('breaches');
    $breadcrumbs->push($breach->name, route('breaches.show', $breach->id));
});

// Dashboard > Breslocaties > Breslocatie aanmaken
Breadcrumbs::register('breachCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('breaches');
    $breadcrumbs->push('Breslocatie aanmaken', route('breaches.create'));
});

/**
 * Belastingniveaus
 */

// Dashboard > Belastingniveaus
Breadcrumbs::register('loadlevels', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Belastingniveaus', route('loadlevels.index'));
});

// Dashboard > Belastingniveaus > [Belastingniveau]
Breadcrumbs::register('loadlevel', function ($breadcrumbs, $loadLevel) {
    $breadcrumbs->parent('loadlevels');
    $breadcrumbs->push($loadLevel->name, route('loadlevels.show', $loadLevel->id));
});

// Dashboard > Belastingniveaus > Belastingniveau aanmaken
Breadcrumbs::register('loadlevelCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('loadlevels');
    $breadcrumbs->push('Belastingniveau aanmaken', route('loadlevels.create'));
});

/**
 * Scenario's
 */

// Dashboard > Scenario's
Breadcrumbs::register('scenarios', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Scenario\'s', route('scenarios.index'));
});

// Dashboard > Scenario's > [scenario]
Breadcrumbs::register('scenario', function ($breadcrumbs, $scenario) {
    $breadcrumbs->parent('scenarios');
    $breadcrumbs->push($scenario->name, route('scenarios.show', $scenario->id));
});

// Dashboard > Scenario's > Nieuw scenario
Breadcrumbs::register('scenarioCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('scenarios');
    $breadcrumbs->push('Nieuw scenario', route('scenarios.create'));
});

/**
 * Gebruikers
 */

// Dashboard > Gebruikers
Breadcrumbs::register('users', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Gebruikers', route('users.index'));
});

// Dashboard > Gebruikers > Huidige gebruiker
Breadcrumbs::register('currentUser', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Gebruikers', route('users.index'));
});

// Dashboard > Gebruikers > [Gebruiker]
Breadcrumbs::register('user', function ($breadcrumbs, $user) {
    $breadcrumbs->parent('users');
    $breadcrumbs->push($user->name, route('users.show', $user->id));
});

// Dashboard > Gebruiker > Gebruiker aanmaken
Breadcrumbs::register('userCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('users');
    $breadcrumbs->push('Gebruiker aanmaken', route('users.create'));
});

/**
 * Cascades
 */

// Dashboard > Cascades
Breadcrumbs::register('cascades', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Cascades', route('cascades.index'));
});

// Dashboard > Cascades > [Cascade]
Breadcrumbs::register('cascade', function ($breadcrumbs, $cascade) {
    $breadcrumbs->parent('cascades');
    $breadcrumbs->push($cascade->id, route('cascades.show', $cascade->id));
});

// Dashboard > Cascades > Cascade aanmaken
Breadcrumbs::register('cascadeCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('cascades');
    $breadcrumbs->push('Cascade aanmaken', route('cascades.create'));
});

/**
 * Consequences
 */

// Dashboard > Consequences
Breadcrumbs::register('consequences', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Consequenties', route('consequences.index'));
});

// Dashboard > Consequences > [Consequence]
Breadcrumbs::register('consequence', function ($breadcrumbs, $consequence) {
    $breadcrumbs->parent('consequences');
    $breadcrumbs->push($consequence->id, route('consequences.show', $consequence->id));
});

// Dashboard > Consequences > Consequence aanmaken
Breadcrumbs::register('consequenceCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('consequences');
    $breadcrumbs->push('Consequentie aanmaken', route('consequences.create'));
});

/**
 * Nieuwsberichten
 */

// Dashboard > Nieuwsberichten
Breadcrumbs::register('newsposts', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Nieuwsberichten', route('news.index'));
});

// Dashboard > Nieuwsberichten > [Nieuwsbericht]
Breadcrumbs::register('news', function ($breadcrumbs, $newspost) {
    $breadcrumbs->parent('newsposts');
    $breadcrumbs->push($newspost->title, route('news.show', $newspost->id));
});

// Dashboard > Nieuwsberichten > Nieuwsbericht aanmaken
Breadcrumbs::register('newsCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('newsposts');
    $breadcrumbs->push('Nieuwsbericht aanmaken', route('news.create'));
});

/**
 * Roles
 */

// Dashboard > Roles
Breadcrumbs::register('roles', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Roles', route('roles.index'));
});

// Dashboard > Roles > [Role]
Breadcrumbs::register('role', function ($breadcrumbs, $user) {
    $breadcrumbs->parent('roles');
    $breadcrumbs->push($user->name, route('roles.show', $user->id));
});

// Dashboard > Roles > Roles aanmaken
Breadcrumbs::register('roleCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('roles');
    $breadcrumbs->push('Roles aanmaken', route('roles.create'));
});

// Dashboard > Gebruikers > Avatar
Breadcrumbs::register('avatar', function ($breadcrumbs) {
    $breadcrumbs->parent('users');
    $breadcrumbs->push("Avatar configuratie", route('users.avatar'));
});

/**
 * Invite
 */

// Dashboard > Invites
Breadcrumbs::register('invites', function ($breadcrumbs) {
    $breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Uitnodigingen', route('invites.index'));
});

// Dashboard > Invites > Gebruiker uitnodigingen
Breadcrumbs::register('inviteCreate', function ($breadcrumbs) {
    $breadcrumbs->parent('invites');
    $breadcrumbs->push('Uitnodiging aanmaken', route('invites.create'));
});
