<?php namespace WebEd\Plugins\Backup\Providers;

use Illuminate\Support\ServiceProvider;

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

    private function booted()
    {
        acl_permission()
            ->registerPermission('View backups', 'view-backups', $this->module)
            ->registerPermission('Download backups', 'download-backups', $this->module)
            ->registerPermission('Create backups', 'create-backups', $this->module)
            ->registerPermission('Delete backups', 'delete-backups', $this->module);
    }
}
