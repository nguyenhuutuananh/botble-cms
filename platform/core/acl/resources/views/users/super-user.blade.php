@extends('core.table::table')
@section('main-table')
    @parent
    <!-- Form modal -->
    <div id="add_super_user" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="til_img"></i><strong>{{ trans('core/base::system.user.add_user') }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>

                {!! Form::open(['route' => ['users-supers.create']]) !!}
                <div class="modal-body with-padding">
                    <div class="form-group @if ($errors->has('email')) has-error @endif">
                        <label>{{ trans('core/base::system.user.email') }}</label>
                        {!! Form::text('email', null, ['class' => 'form-control']) !!}
                        {!! Form::error('email', $errors) !!}
                    </div>

                    <div class="form-actions text-right">
                        <button data-dismiss="modal"
                                class="btn btn-secondary">{{ trans('core/base::system.user.cancel') }}</button>
                        <button type="submit" class="btn btn-success">{{ trans('core/base::system.user.create') }}</button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /form modal -->
@stop