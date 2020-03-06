@if ($showLabel && $showField)
    @if ($options['wrapper'] !== false)
        <div {!! $options['wrapperAttrs'] !!}>
            @endif
            @endif

            @if ($showLabel && $options['label'] !== false && $options['label_show'])
                {!! Form::customLabel($name, $options['label'], $options['label_attr']) !!}
            @endif

            @if ($showField)
                {!! Form::textarea($name, $options['value'], $options['attr']) !!}
                @include('core/base::forms.partials.help_block')
            @endif

            @include('core/base::forms.partials.errors')

            @if ($showLabel && $showField)
                @if ($options['wrapper'] !== false)
        </div>
    @endif
@endif

@push('scripts')
    <script>
        function setImageValue(file) {
            $('.mce-btn.mce-open').parent().find('.mce-textbox').val(file);
        }
    </script>
    <iframe id="form_target" name="form_target" style="display:none"></iframe>
    <form id="upload_form" action="{{ route('public.member.upload') }}" target="form_target" method="post" enctype="multipart/form-data" style="width:0;height:0;overflow:hidden;display: none;">
        {{ csrf_field() }}
        <input name="upload" id="upload_file" type="file" onchange="$('#upload_form').submit();this.value='';">
    </form>
@endpush
