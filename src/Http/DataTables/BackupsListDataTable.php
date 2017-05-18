<?php namespace WebEd\Plugins\Backup\Http\DataTables;

use WebEd\Base\Http\DataTables\AbstractDataTables;
use Yajra\Datatables\Engines\CollectionEngine;
use Yajra\Datatables\Engines\EloquentEngine;
use Yajra\Datatables\Engines\QueryBuilderEngine;

class BackupsListDataTable extends AbstractDataTables
{
    /**
     * @var \Illuminate\Support\Collection
     */
    protected $repository;

    public function __construct()
    {
        $this->repository = collect(\Backup::all());
    }

    public function headings()
    {
        return [
            'type' => [
                'title' => trans('webed-backup::base.type'),
                'width' => '25%',
            ],
            'backup_size' => [
                'title' => trans('webed-backup::base.backup_size'),
                'width' => '25%',
            ],
            'created_at' => [
                'title' => trans('webed-core::datatables.heading.created_at'),
                'width' => '25%',
            ],
            'actions' => [
                'title' => trans('webed-core::datatables.heading.actions'),
                'width' => '25%',
            ],
        ];
    }

    public function columns()
    {
        return [
            ['data' => 'type', 'name' => 'type'],
            ['data' => 'file_size', 'name' => 'file_size'],
            ['data' => 'created_at', 'name' => 'created_at', 'searchable' => false],
            ['data' => 'actions', 'name' => 'actions', 'searchable' => false, 'orderable' => false],
        ];
    }

    /**
     * @return string
     */
    public function run()
    {
        $this->setAjaxUrl(route('admin::webed-backup.index.post'), 'POST');

        return $this->view();
    }

    /**
     * @return CollectionEngine|EloquentEngine|QueryBuilderEngine|mixed
     */
    protected function fetchDataForAjax()
    {
        return datatable()->of($this->repository)
            ->rawColumns(['actions'])
            ->addColumn('type', function ($item) {
                $fileName = array_get($item, 'file_name');
                $type = explode('-', $fileName);
                return trans('webed-backup::base.backup_type.' . $type[0]);
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
                ]), trans('webed-backup::base.actions.download'), [
                    'class' => 'btn btn-outline green btn-sm ajax-link',
                ]);
                $deleteBtn = form()->button(trans('webed-core::datatables.delete'), [
                    'title' => trans('webed-core::datatables.delete_this_item'),
                    'data-ajax' => route('admin::webed-backup.delete.delete', [
                        'path' => array_get($item, 'file_path')
                    ]),
                    'data-method' => 'DELETE',
                    'data-toggle' => 'confirmation',
                    'class' => 'btn btn-outline red-sunglo btn-sm ajax-link',
                ]);

                return $download . $deleteBtn;
            });
    }
}
