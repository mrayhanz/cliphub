<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('brand:seed-mock', function () {
    $this->info('Seeding high-fidelity Brand mock data...');
    $this->call('db:seed', ['--class' => 'Database\\Seeders\\BrandMockSeeder']);
    $this->info('Brand mock data seeded successfully!');
})->purpose('Seed rich mock campaigns, submissions, and deposits for ClipHub brand testing');
