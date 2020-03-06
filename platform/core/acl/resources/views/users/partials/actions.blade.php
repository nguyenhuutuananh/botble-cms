@if (Auth::user()->hasPermission('users.edit'))
<a href="{{ route('user.profile.view', $item->id) }}" class="btn btn-icon btn-primary" data-toggle="tooltip" data-original-title="View user's profile"><i class="fa fa-eye"></i></a>
@endif

@if (Auth::user()->hasPermission('users.destroy'))
<a href="#" class="btn btn-icon btn-danger deleteDialog" data-toggle="tooltip" data-section="{{ route('users.destroy', $item->id) }}" role="button" data-original-title="{{ trans('core/base::tables.delete_entry') }}" >
    <i class="fa fa-trash"></i>
</a>
@endif