<div class="modal fade" id="avatar-modal" tabindex="-1" role="dialog" aria-labelledby="avatar-modal-label"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form class="avatar-form" method="post" action="{{ route('public.member.avatar') }}" enctype="multipart/form-data">
                <div class="modal-header">
                    <h4 class="modal-title" id="avatar-modal-label"><i class="til_img"></i><strong>{{ __('plugins/member::dashboard.change_profile_image') }}</strong></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="avatar-body">

                        <!-- Upload image and data -->
                        <div class="avatar-upload">
                            <input class="avatar-src" name="avatar_src" type="hidden">
                            <input class="avatar-data" name="avatar_data" type="hidden">
                            {!! csrf_field() !!}
                            <label for="avatarInput">{{ __('plugins/member::dashboard.new_image') }}</label>
                            <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                        </div>

                        <div class="loading" tabindex="-1" role="img" aria-label="{{ __('plugins/member::dashboard.loading') }}"></div>

                        <!-- Crop and preview -->
                        <div class="row">
                            <div class="col-md-9">
                                <div class="avatar-wrapper"></div>
                            </div>
                            <div class="col-md-3 avatar-preview-wrapper">
                                <div class="avatar-preview preview-lg"></div>
                                <div class="avatar-preview preview-md"></div>
                                <div class="avatar-preview preview-sm"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" type="button" data-dismiss="modal">{{ __('plugins/member::dashboard.close') }}</button>
                    <button class="btn btn-primary avatar-save" type="submit">{{ __('plugins/member::dashboard.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div><!-- /.modal -->