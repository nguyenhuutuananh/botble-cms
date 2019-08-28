
{!! Theme::partial('header') !!}

<main class="main" id="main">
    <div class="container">
        @if (Route::currentRouteName() == 'public.index' && is_plugin_active('blog'))
            @php
                $featured = get_featured_posts(5);
            @endphp
            @if (count($featured) > 0)
                <div class="main-feature">
                    @foreach ($featured as $feature_item)
                        <div class="feature-item">
                            <div class="feature-item-dv">
                                <a href="{{ route('public.single', $feature_item->slug) }}"
                                   title="{{ $feature_item->name }}"
                                   style="display: block">
                                    <img class="img-full img-bg" src="{{ get_object_image($feature_item->image, $loop->first ? 'featured' : 'medium') }}" alt="{{ $feature_item->name }}"
                                         style="background-image: url('{{ get_object_image($feature_item->image) }}');">
                                    <span class="feature-item-link"
                                          title="{{ $feature_item->name }}">
                                        <span>{{ $feature_item->name }}</span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
        <div class="main-content">
            <div class="main-left">
                {!! Theme::content() !!}
            </div>
            {!! Theme::partial('sidebar') !!}
        </div>
    </div>
</main>

{!! Theme::partial('footer') !!}

