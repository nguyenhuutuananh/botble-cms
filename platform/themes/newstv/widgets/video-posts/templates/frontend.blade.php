@if (is_plugin_active('blog'))
    <div class="aside-box">
        <div class="aside-box-header">
            <h4>{{ __($config['name']) }}</h4>
        </div>
        <div class="aside-box-content">
            @foreach(get_popular_posts($config['number_display'], ['where' => ['status' => 1, 'format_type' => 'video']]) as $post)
                <div class="media-news media-video">
                    <a href="{{ route('public.single', $post->slug) }}"
                       class="media-news-img" title="{{ $post->name }}">
                        <img class="img-full img-bg" src="{{ get_object_image($post->image, 'thumb') }}"
                             style="background-image: url('{{ get_object_image($post->image) }}');"
                             alt="{{ $post->name }}">
                    </a>
                    <div class="media-news-body">
                        <p class="common-title">
                            <a href="{{ route('public.single', $post->slug) }}"
                               title="{{ $post->name }}">{{ $post->name }}</a>
                        </p>
                        <p class="common-date">
                            <time datetime="">{{ date_from_database($post->created_at, 'M d, Y') }}</time>
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif