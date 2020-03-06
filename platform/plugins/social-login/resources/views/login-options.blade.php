@if (setting('social_login_facebook_enable', false) || setting('social_login_google_enable', false) || setting('social_login_github_enable', false))
    <div class="login-options">
        <p>{{ trans('core/acl::auth.login_via_social') }}</p>
        <ul class="social-icons">
            @if (setting('social_login_facebook_enable', false))
                <li>
                    <a class="social-icon-color facebook" data-original-title="facebook"
                       href="{{ route('auth.social', 'facebook') }}"></a>
                </li>
            @endif
            @if (setting('social_login_google_enable', false))
                <li>
                    <a class="social-icon-color googleplus" data-original-title="Google Plus"
                       href="{{ route('auth.social', 'google') }}"></a>
                </li>
            @endif
            @if (setting('social_login_github_enable', false))
                <li>
                    <a class="social-icon-color github" data-original-title="Github"
                       href="{{ route('auth.social', 'github') }}"></a>
                </li>
            @endif
        </ul>
    </div>
@endif