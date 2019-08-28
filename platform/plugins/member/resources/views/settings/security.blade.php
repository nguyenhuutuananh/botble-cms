@extends('plugins.member::layouts.skeleton')
@section('content')
  <div class="settings">
    <div class="container">
      <div class="row">
        @include('plugins.member::settings.sidebar')
        <div class="col-12 col-md-9">
          <div class="mb-5">
            <!-- Title -->
            <div class="row">
              <div class="col-12">
                <h4 class="with-actions">{{ trans('plugins/member::dashboard.security_title') }}</h4>
              </div>
            </div>

            <!-- Content -->
            <div class="row">
              <div class="col-lg-8">
                @if (session('status'))
                  <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                @endif
                <form method="POST" action="{{ route('public.member.post.security') }}" class="settings-reset">
                  @method('PUT')
                  @csrf
                  <div class="form-group">
                    <label for="current_password">{{ trans('plugins/member::dashboard.current_password') }}</label>
                    <input type="password" class="form-control" name="current_password" id="current_password">
                  </div>
                  <div class="form-group">
                    <label for="password">{{ trans('plugins/member::dashboard.password_new') }}</label>
                    <input type="password" class="form-control" name="password" id="password">
                  </div>
                  <div class="form-group">
                    <label for="password_confirmation">{{ trans('plugins/member::dashboard.password_new_confirmation') }}</label>
                    <input type="password" class="form-control" name="password_confirmation" id="password_confirmation">
                  </div>
                  <button type="submit" class="btn default-btn fw6">{{ trans('plugins/member::dashboard.password_update_btn') }}</button>
                </form>
              </div>
            </div>
          </div>

          <div class="mb-3 br2">
            <!-- Title -->
            <div class="row">
              <div class="col-12">
                <h4>{{ trans('plugins/member::dashboard.danger_zone_title') }}</h4>
              </div>
            </div>

            <div class="row">
              <div class="col-lg-8">
                <div class="ba br2 b--darkest-red">
                  <ul class="list pa0">
                    <li class="pa3" style="background: none;">
                      @if(empty(auth()->guard('member')->user()->destroy_date))
                        <div class="mb-2">{{ trans('plugins/member::dashboard.delete_account_desc') }}</div>
                        <form method="POST" action="/users/destroy">
                          @csrf
                          <button type="button" class="btn default-btn fw6 darkest-red" onclick="if (confirm('{{ trans('plugins/member::dashboard.delete_account_confirmation') }}')) { $(this).closest('form').submit(); } return false;">
                            {{ trans('plugins/member::dashboard.delete_account_btn') }}
                          </button>
                        </form>
                      @else
                        <div class="mb-2">{!! trans('plugins/member::dashboard.deleted_account_desc', ['destroy_date' => auth()->guard('member')->user()->destroy_date]) !!}</div>
                        <form method="POST" action="/users/reverse">
                          @csrf
                          <button type="submit" class="btn default-btn fw6 darkest-red">
                            {{ trans('plugins/member::dashboard.cancel_delete_account_btn') }}
                          </button>
                        </form>
                      @endif
                    </li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@push('scripts')
  <!-- Laravel Javascript Validation -->
  <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
  {!! JsValidator::formRequest(\Botble\Member\Http\Requests\UpdatePasswordRequest::class); !!}
@endpush