<table class="bg-gray-900 rounded-lg overflow-hidden w-full">
    <thead>
        <tr>
            <th class="text-left px-4 py-2 border-b border-gray-700 text-white">{{ $tableHeaders[0] }}</th>
            <th class="text-right px-4 py-2 border-b border-gray-700 text-white">{{ $tableHeaders[1] }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($tableData as $data)
        <tr class="{{ $loop->iteration % 2 ? 'bg-gray-800' : 'bg-gray-700' }}">
            <td class="text-left px-4 py-2 border-b border-gray-700 text-white">{{ $data[0] }}</td>
            <td class="text-right px-4 py-2 border-b border-gray-700 text-white">{{ $data[1] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
