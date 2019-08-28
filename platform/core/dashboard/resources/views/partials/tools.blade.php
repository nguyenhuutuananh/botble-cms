<div class="tools">
    <a href="#" class="{{ Arr::get($settings, 'state', 'expand') }} tip" title="{{ trans('core/dashboard::dashboard.collapse_expand') }}" data-state="{{ Arr::get($settings, 'state', 'expand') == 'collapse' ? 'expand' : 'collapse' }}"></a>
    <a href="#" class="reload tip" title="{{ trans('core/dashboard::dashboard.reload') }}"></a>
    <a href="#" class="fullscreen" data-original-title="{{ trans('core/dashboard::dashboard.fullscreen') }}" title="{{ trans('core/dashboard::dashboard.fullscreen') }}"> </a>
    <a href="#" class="remove tip" title="{{ trans('core/dashboard::dashboard.hide') }}"></a>
</div>