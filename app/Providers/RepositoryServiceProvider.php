<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\PemasukanRepositoryInterface;
use App\Repositories\Contracts\PengeluaranRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\SettingRepositoryInterface;
use App\Repositories\Contracts\RoleRepositoryInterface;

use App\Repositories\Eloquent\MenuRepository;
use App\Repositories\Eloquent\PemasukanRepository;
use App\Repositories\Eloquent\PengeluaranRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Repositories\Eloquent\SettingRepository;
use App\Repositories\Eloquent\RoleRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bind repository contracts to their Eloquent implementations.
     *
     * @var array<class-string, class-string>
     */
    public array $bindings = [
        MenuRepositoryInterface::class        => MenuRepository::class,
        PemasukanRepositoryInterface::class   => PemasukanRepository::class,
        PengeluaranRepositoryInterface::class => PengeluaranRepository::class,
        UserRepositoryInterface::class        => UserRepository::class,
        SettingRepositoryInterface::class     => SettingRepository::class,
        RoleRepositoryInterface::class        => RoleRepository::class,
    ];

    public function register(): void
    {
        //
    }
}
