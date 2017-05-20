<?php namespace WebEd\Plugins\Backup\Providers;

use Illuminate\Support\ServiceProvider;
use Schema;
use Illuminate\Database\Schema\Blueprint;

class InstallModuleServiceProvider extends ServiceProvider
{
    protected $module = 'WebEd\Plugins\Backup';

    protected $moduleAlias = 'webed-backup';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        app()->booted(function () {
            $this->booted();
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {

    }

    protected function booted()
    {
        acl_permission()
            ->registerPermission('View backups', 'view-backups', $this->moduleAlias)
            ->registerPermission('Download backups', 'download-backups', $this->moduleAlias)
            ->registerPermission('Create backups', 'create-backups', $this->moduleAlias)
            ->registerPermission('Delete backups', 'delete-backups', $this->moduleAlias);
    }
}
