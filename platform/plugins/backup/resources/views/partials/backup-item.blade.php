<tr class="@if (!empty($odd) && $odd == true) odd @else even @endif">
    <td>{{ $data['name'] }}</td>
    <td>{{ $data['description'] }}</td>
    <td>{{ human_file_size(get_backup_size($key)) }}</td>
    <td style="width: 250px;">{{ $data['date'] }}</td>
    <td style="width: 150px;">
        <a href="{{ route('backups.download.database', $key) }}" class="text-success tip" title="{{ trans('plugins/backup::backup.download_database') }}"><i class="icon icon-database"></i></a>
        <a href="{{ route('backups.download.uploads.folder', $key) }}" class="text-primary tip" title="{{ trans('plugins/backup::backup.download_uploads_folder') }}"><i class="icon icon-download"></i></a>
        <a href="#" data-section="{{ route('backups.delete', $key) }}" class="text-danger deleteDialog tip" title="{{ trans('core/base::tables.delete_entry') }}"><i class="icon icon-trash"></i></a>
        <a href="#" data-section="{{ route('backups.restore', $key) }}" class="text-info restoreBackup tip" title="{{ trans('plugins/backup::backup.restore_tooltip') }}"><i class="icon icon-publish"></i></a>
    </td>
</tr>