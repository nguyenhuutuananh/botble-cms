@if ($posts->count() > 0)
    <div class="scroller">
        <table class="table table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>{{ trans('core/base::tables.name') }}</th>
                <th>{{ trans('core/base::tables.created_at') }}</th>
            </tr>
            </thead>
            <tbody>
            @foreach($posts as $post)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>@if ($post->slug) <a href="{{ route('public.single', $post->slug) }}" target="_blank">{{ Str::limit($post->name, 100) }}</a> @else <strong>{{ str_limit($post->name, 100) }}</strong> @endif</td>
                    <td>{{ date_from_database($post->created_at, 'd-m-Y') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    @if ($posts->total() > $limit)
        <div class="widget_footer">
            @include('core/dashboard::partials.paginate', ['data' => $posts, 'limit' => $limit])
        </div>
    @endif
@else
    @include('core/dashboard::partials.no-data', ['message' => trans('plugins/blog::posts.no_new_post_now')])
@endif