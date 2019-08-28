<a href="{{ route('categories.edit', $item->id) }}" class="btn btn-icon btn-primary tip" data-original-title="{{ trans('core/base::tables.edit') }}"><i class="fa fa-edit"></i></a>
@if (!$item->is_default)
    <a href="#" class="btn btn-icon btn-danger deleteDialog tip" data-toggle="modal" data-section="{{ route('categories.delete', $item->id) }}" role="button" data-original-title="{{ trans('core/base::tables.delete_entry') }}" >
        <i class="fa fa-trash"></i>
    </a>
@endif

