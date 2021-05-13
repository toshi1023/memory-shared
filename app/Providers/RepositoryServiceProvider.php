<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\Group\GroupRepository;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use App\Repositories\GroupHistory\GroupHistoryRepository;
use App\Repositories\Album\AlbumRepositoryInterface;
use App\Repositories\Album\AlbumRepository;
use App\Repositories\UserImage\UserImageRepositoryInterface;
use App\Repositories\UserImage\UserImageRepository;
use App\Repositories\UserVideo\UserVideoRepositoryInterface;
use App\Repositories\UserVideo\UserVideoRepository;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\News\NewsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(UserRepositoryInterface::class, UserRepository::class);
        $this->app->singleton(GroupRepositoryInterface::class, GroupRepository::class);
        $this->app->singleton(GroupHistoryRepositoryInterface::class, GroupHistoryRepository::class);
        $this->app->singleton(AlbumRepositoryInterface::class, AlbumRepository::class);
        $this->app->singleton(UserImageRepositoryInterface::class, UserImageRepository::class);
        $this->app->singleton(UserVideoRepositoryInterface::class, UserVideoRepository::class);
        $this->app->singleton(NewsRepositoryInterface::class, NewsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
