@if($row->is_folder)
    <a href="{{ route('admin.repository.index', ['folder' => $row->id]) }}" class="bold">
        {!! extensionIcon(strtolower($row->extension)) . ' ' . $row->name  !!}
    </a>
    <br/>
    <small>{{ number($row->count_folders, 0) }} Pastas &bullet; {{ number($row->count_files, 0) }} Ficheiros</small>
@else
    @if ($nameLink === 'open')
        <a href="{{ route('admin.files.open', $row->id) }}" target="_blank">
            {!! extensionIcon(strtolower($row->extension)) . ' ' . $row->name  !!}
        </a>
    @elseif ($nameLink === 'download')
        <a href="{{ route('admin.attachments.download', $row->id) }}">
            {!! extensionIcon(strtolower($row->extension)) . ' ' . $row->name  !!}
        </a>
    @else
        {!! extensionIcon(strtolower($row->extension)) . ' ' . $row->name  !!}
    @endif

@endif
