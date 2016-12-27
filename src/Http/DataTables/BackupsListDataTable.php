<?php namespace WebEd\Plugins\Backup\Http\DataTables;

use WebEd\Base\Core\Http\DataTables\AbstractDataTables;

class BackupsListDataTable extends AbstractDataTables
{
    protected $repository;

    public function __construct()
    {
        $this->repository = collect(\Backup::all());

        parent::__construct();
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->setAjaxUrl(route('admin::webed-backup.index.post'), 'POST');

        $this
            ->addHeading('type', 'Type', '25%')
            ->addHeading('backup_size', 'Backup size', '25%')
            ->addHeading('created_at', 'Created at', '25%')
            ->addHeading('actions', 'Actions', '25%')
        ;

        $this->setColumns([
            ['data' => 'type', 'name' => 'type'],
            ['data' => 'file_size', 'name' => 'file_size'],
            ['data' => 'created_at', 'name' => 'created_at', 'searchable' => false],
            ['data' => 'actions', 'name' => 'actions', 'searchable' => false, 'orderable' => false],
        ]);

        return $this->view();
    }

    /**
     * @return $this
     */
    protected function fetch()
    {
        $this->fetch = datatable()->of($this->repository)
            ->addColumn('type', function ($item) {
                $fileName = array_get($item, 'file_name');
                $type = explode('-', $fileName);
                return $type[0];
            })
            ->addColumn('file_size', function ($item) {
                return format_file_size(array_get($item, 'file_size', 0), 2);
            })
            ->addColumn('created_at', function ($item) {
                return convert_unix_time_format(array_get($item, 'last_modified'));
            })
            ->addColumn('actions', function ($item) {
                $download = html()->link(route('admin::webed-backup.download.get', [
                    'path' => array_get($item, 'file_path')
                ]), 'Download', [
                    'class' => 'btn btn-outline green btn-sm ajax-link',
                ]);
                $deleteBtn = form()->button('Delete', [
                    'title' => 'Delete this item',
                    'data-ajax' => route('admin::webed-backup.delete.delete', [
                        'path' => array_get($item, 'file_path')
                    ]),
                    'data-method' => 'DELETE',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline red-sunglo btn-sm ajax-link',
                ]);

                return $download . $deleteBtn;
            });

        return $this;
    }
}
