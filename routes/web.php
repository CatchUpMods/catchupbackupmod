<?php
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

$adminRoute = config('webed.admin_route');

$moduleRoute = 'backup';

/**
 * Admin routes
 */
Route::group(['prefix' => $adminRoute . '/' . $moduleRoute], function (Router $router) use ($adminRoute, $moduleRoute) {
    /**
     * View backups
     */
    $router->get('', 'BackupController@getIndex')
        ->name('admin::webed-backup.index.get')
        ->middleware('has-permission:view-backups');
    $router->post('', 'BackupController@postListing')
        ->name('admin::webed-backup.index.post')
        ->middleware('has-permission:view-backups');

    /**
     * Create backup
     */
    $router->get('create/{type?}', 'BackupController@getCreate')
        ->name('admin::webed-backup.create.get')
        ->middleware('has-permission:create-backups');

    /**
     * Download backup
     */
    $router->get('download', 'BackupController@getDownload')
        ->name('admin::webed-backup.download.get')
        ->middleware('has-permission:download-backups');

    /**
     * Delete backup
     */
    $router->delete('delete', 'BackupController@deleteDelete')
        ->name('admin::webed-backup.delete.delete')
        ->middleware('has-permission:delete-backups');

    /**
     * Delete all backups
     */
    $router->get('delete-all', 'BackupController@getDeleteAll')
        ->name('admin::webed-backup.delete-all.get')
        ->middleware('has-permission:delete-backups');
});
