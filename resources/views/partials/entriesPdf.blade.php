<style>
    #recentEntries {
        font-family: Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #recentEntries td,
    #recentEntries th {
        border: 1px solid #ddd;
        padding: 8px;
    }

    #recentEntries tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    #recentEntries tr:hover {
        background-color: #ddd;
    }

    #recentEntries th {
        padding-top: 12px;
        padding-bottom: 12px;
        text-align: left;
        background-color: #13214A;
        color: white;
    }
</style>
<table id="recentEntries" class="table table-striped" style="width:100%" cellspacing="0" cellpadding="0" border="0"
    bgcolor="#fff">
    <thead>
        <tr>
            <th>#</th>
            <th>Horse</th>
            <th>Rider</th>
            <th>Trainer</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($entries as $entry)
            <tr>
                <td>{{ $loop->index + 1 }}</td>
                <td>
                    {{ $entry->horsename }}
                    {{ $entry->horsenfid }}
                    {{ $entry->horsefeiid }}
                </td>
                <td>
                    {{ $entry->ridername }}
                    {{ $entry->ridernfid }}
                    {{ $entry->riderfeiid }}
                </td>
                <td>
                    {{ $entry->trainername }}
                    {{ $entry->trainernfid }}
                    {{ $entry->trainerfeiid }}
                </td>
                <td>{{ $entry->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
