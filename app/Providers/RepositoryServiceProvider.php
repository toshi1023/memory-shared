<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Repositories\User\UserRepositoryInterface;
use App\Repositories\User\UserRepository;
use App\Repositories\Group\GroupRepositoryInterface;
use App\Repositories\Group\GroupRepository;
use App\Repositories\GroupHistory\GroupHistoryRepositoryInterface;
use App\Repositories\GroupHistory\GroupHistoryRepository;
use App\Repositories\Family\FamilyRepositoryInterface;
use App\Repositories\Family\FamilyRepository;
use App\Repositories\Album\AlbumRepositoryInterface;
use App\Repositories\Album\AlbumRepository;
use App\Repositories\UserImage\UserImageRepositoryInterface;
use App\Repositories\UserImage\UserImageRepository;
use App\Repositories\UserVideo\UserVideoRepositoryInterface;
use App\Repositories\UserVideo\UserVideoRepository;
use App\Repositories\MessageHistory\MessageHistoryRepositoryInterface;
use App\Repositories\MessageHistory\MessageHistoryRepository;
use App\Repositories\MessageRelation\MessageRelationRepositoryInterface;
use App\Repositories\MessageRelation\MessageRelationRepository;
use App\Repositories\News\NewsRepositoryInterface;
use App\Repositories\News\NewsRepository;
use App\Repositories\MreadManagement\MreadManagementRepositoryInterface;
use App\Repositories\MreadManagement\MreadManagementRepository;
use App\Repositories\NreadManagement\NreadManagementRepositoryInterface;
use App\Repositories\NreadManagement\NreadManagementRepository;

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
        $this->app->singleton(FamilyRepositoryInterface::class, FamilyRepository::class);
        $this->app->singleton(AlbumRepositoryInterface::class, AlbumRepository::class);
        $this->app->singleton(UserImageRepositoryInterface::class, UserImageRepository::class);
        $this->app->singleton(UserVideoRepositoryInterface::class, UserVideoRepository::class);
        $this->app->singleton(NewsRepositoryInterface::class, NewsRepository::class);
        $this->app->singleton(MessageHistoryRepositoryInterface::class, MessageHistoryRepository::class);
        $this->app->singleton(MessageRelationRepositoryInterface::class, MessageRelationRepository::class);
        $this->app->singleton(NreadManagementRepositoryInterface::class, NreadManagementRepository::class);
        $this->app->singleton(MreadManagementRepositoryInterface::class, MreadManagementRepository::class);
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
