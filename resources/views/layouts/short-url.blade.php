 <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        @can('create short url')
        <a href="{{ route('short_urls.create') }}" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Short URL</a>
        @endcan
        <div class="overflow-x-auto w-full">
            <table class="min-w-full w-full divide-y divide-gray-200 mt-4 border border-gray-200 rounded-lg">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Short Code</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Original URL</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Created By</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Created At</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($shortUrls as $key => $shortUrl)
                        @php
                            $key = 1 + $key;
                        @endphp
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-900">{{ $key }}</td>
                            <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-blue-700 font-mono">
                                <a href="{{ $shortUrl->original_url }}" target="_blank" class="underline">{{ $shortUrl->short_code }}</a>
                            </td>
                            <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-900 break-all">{{ $shortUrl->original_url }}</td>
                            <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-900">{{ $shortUrl->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-left whitespace-nowrap text-sm text-gray-900">{{ $shortUrl->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-left text-gray-500">No short URLs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $shortUrls->links() }}
        </div>

        {{-- add dasboard route condition --}}
        @if (request()->routeIs('dashboard'))
        <div>
             <a href="{{ route('short_urls.index') }}" class="mt-4 inline-block text-sm text-gray-600 hover:text-gray-900">
                <p>View All</p>
            </a>
        </div>

        @endif
        {{-- end dashboard route condition --}}

    </div>
</div>
