<div id="edit-slug-box" @if (empty($value) && !$errors->has($name)) class="hidden" @endif>
    <label class="control-label required" for="current-slug">{{ trans('core/base::forms.permalink') }}:</label>
    <span id="sample-permalink">
        <a class="permalink" target="_blank" href="{{ str_replace('--slug--', $value, url($prefix . '/' . config('packages.slug.general.pattern'))) }}{{ $ending_url }}">
            <span class="default-slug">{{ url($prefix) }}/<span id="editable-post-name">{{ $value }}</span>{{ $ending_url }}</span>
        </a>
    </span>
    ‎<span id="edit-slug-buttons">
        <button type="button" class="btn btn-secondary" id="change_slug">{{ trans('core/base::forms.edit') }}</button>
        <button type="button" class="save btn btn-secondary" id="btn-ok">{{ trans('core/base::forms.ok') }}</button>
        <button type="button" class="cancel button-link">{{ trans('core/base::forms.cancel') }}</button>
    </span>
</div>
<input type="hidden" id="current-slug" name="{{ $name }}" value="{{ $value }}">
<div data-url="{{ route('slug.create') }}" data-view="{{ rtrim(str_replace('--slug--', '', url($prefix . '/' . config('packages.slug.general.pattern'))), '/') . '/' }}" id="slug_id" data-id="{{ $id }}"></div>
<input type="hidden" name="slug_id" value="{{ $id }}">