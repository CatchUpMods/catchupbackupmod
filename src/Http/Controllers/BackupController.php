<?php namespace WebEd\Plugins\Backup\Http\Controllers;

use WebEd\Base\Core\Http\Controllers\BaseAdminController;
use Storage;
use WebEd\Plugins\Backup\Http\DataTables\BackupsListDataTable;

class BackupController extends BaseAdminController
{
    protected $module = 'webed-backup';

    public function __construct()
    {
        parent::__construct();

        $this->getDashboardMenu($this->module);
    }

    public function getIndex(BackupsListDataTable $backupsListDataTable)
    {
        $this->setPageTitle('Backups');

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
            ini_set('max_execution_time', 3000);

            \Backup::createBackupFolder('webed-backup');

            if($type === null || $type === 'database') {
                \Backup::backupDb();
            }
            if($type === null || $type === 'medias') {
                \Backup::backupFolder(public_path('uploads'));
            }

            $this->flashMessagesHelper->addMessages('Completed', 'success');
        } catch (\Exception $exception) {
            $this->flashMessagesHelper->addMessages($exception->getMessage(), 'danger');
        }
        $this->flashMessagesHelper->showMessagesOnSession();
        return redirect()->to(route('admin::webed-backup.index.get'));
    }

    public function getDownload()
    {
        $path = $this->request->get('path');
        $result = \Backup::download($path);
        if ($result !== null) {
            return $result;
        }
        $this->flashMessagesHelper->addMessages('Cannot download...', 'danger')
            ->showMessagesOnSession();
        return redirect()->to(route('admin::webed-backup.index.get'));
    }

    public function deleteDelete()
    {
        $path = $this->request->get('path');
        if (!$path) {
            return response_with_messages('Wrong path name', true, ERROR_CODE);
        }

        $result = \Backup::delete($path);
        if ($result) {
            return response_with_messages('Deleted', false, SUCCESS_NO_CONTENT_CODE);
        }
        return response_with_messages('Cannot delete...', true, ERROR_CODE);
    }

    public function getDeleteAll()
    {
        $result = \Backup::delete();
        if ($result) {
            $this->flashMessagesHelper->addMessages('Deleted', 'success');
        } else {
            $this->flashMessagesHelper->addMessages('Error occurred', 'danger');
        }
        $this->flashMessagesHelper->showMessagesOnSession();
        return redirect()->to(route('admin::webed-backup.index.get'));
    }
}
