@extends('webed-core::admin._master')

@section('css')

@endsection

@section('js')

@endsection

@section('js-init')

@endsection

@section('content')
    <div class="layout-1columns">
        <div class="column main">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="icon-layers font-dark"></i>
                        {{ trans('webed-backup::base.backups_list') }}
                    </h3>
                    <div class="box-tools">
                        <a class="btn btn-transparent green btn-sm"
                           data-toggle="confirmation"
                           data-placement="left"
                           href="{{ route('admin::webed-backup.create.get', ['type' => 'database']) }}">
                            <i class="fa fa-plus"></i> {{ trans('webed-backup::base.actions.create_database_bk') }}
                        </a>
                        <a class="btn btn-warning btn-sm"
                           data-toggle="confirmation"
                           data-placement="left"
                           href="{{ route('admin::webed-backup.create.get', ['type' => 'medias']) }}">
                            <i class="fa fa-plus"></i> {{ trans('webed-backup::base.actions.create_media_bk') }}
                        </a>
                        <a class="btn btn-transparent purple btn-sm"
                           data-toggle="confirmation"
                           data-placement="left"
                           href="{{ route('admin::webed-backup.create.get') }}">
                            <i class="fa fa-plus"></i> {{ trans('webed-backup::base.actions.create_database_media_bk') }}
                        </a>
                        <a class="btn btn-transparent red-sunglo btn-sm"
                           data-toggle="confirmation"
                           data-placement="left"
                           href="{{ route('admin::webed-backup.delete-all.get') }}">
                            <i class="fa fa-trash"></i> {{ trans('webed-backup::base.actions.delete_all') }}
                        </a>
                    </div>
                </div>
                <div class="box-body">
                    {!! $dataTable or '' !!}
                </div>
            </div>
        </div>
    </div>
@endsection
