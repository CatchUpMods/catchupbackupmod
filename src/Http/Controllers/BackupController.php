<?php namespace WebEd\Plugins\Backup\Http\Controllers;

use WebEd\Base\Http\Controllers\BaseAdminController;
use Storage;
use WebEd\Plugins\Backup\Http\DataTables\BackupsListDataTable;

class BackupController extends BaseAdminController
{
    protected $module = 'webed-backup';

    public function __construct()
    {
        parent::__construct();

        $this->middleware(function ($request, $next) {
            $this->breadcrumbs->addLink(trans('webed-backup::base.backups'), route('admin::webed-backup.index.get'));

            return $next($request);
        });

        $this->getDashboardMenu($this->module);
    }

    public function getIndex(BackupsListDataTable $backupsListDataTable)
    {
        $this->getDashboardMenu($this->module);

        $this->setPageTitle(trans('webed-backup::base.backups'));

        $this->dis['dataTable'] = $backupsListDataTable->run();

        return do_filter('webed-backup.index.get', $this)->viewAdmin('index');
    }

    public function postListing(BackupsListDataTable $backupsListDataTable)
    {
        return do_filter('datatables.custom-fields.index.post', $backupsListDataTable, $this);
    }

    /**
     * @param null $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getCreate($type = null)
    {
        try {
            ini_set('max_execution_time', 30000);

            \Backup::createBackupFolder('webed-backup');

            if($type === null || $type === 'database') {
                \Backup::backupDb();
            }
            if($type === null || $type === 'medias') {
                \Backup::backupFolder(public_path('uploads'));
            }

            flash_messages()->addMessages('Create backup successfully', 'success');
        } catch (\Exception $exception) {
            flash_messages()->addMessages($exception->getMessage(), 'danger');
        }
        flash_messages()->showMessagesOnSession();
        return redirect()->to(route('admin::webed-backup.index.get'));
    }

    public function getDownload()
    {
        $path = $this->request->get('path');
        $result = \Backup::download($path);
        if ($result !== null) {
            return $result;
        }
        flash_messages()->addMessages('Cannot download...', 'danger')
            ->showMessagesOnSession();
        return redirect()->to(route('admin::webed-backup.index.get'));
    }

    public function deleteDelete()
    {
        $path = $this->request->get('path');
        if (!$path) {
            return response_with_messages('Wrong path name', true, \Constants::ERROR_CODE);
        }

        $result = \Backup::delete($path);
        if ($result) {
            return response_with_messages('Deleted', false, \Constants::SUCCESS_NO_CONTENT_CODE);
        }
        return response_with_messages('Cannot delete...', true, \Constants::ERROR_CODE);
    }

    public function getDeleteAll()
    {
        $result = \Backup::delete();
        if ($result) {
            flash_messages()->addMessages('Deleted', 'success');
        } else {
            flash_messages()->addMessages('Error occurred', 'danger');
        }
        flash_messages()->showMessagesOnSession();
        return redirect()->to(route('admin::webed-backup.index.get'));
    }
}
