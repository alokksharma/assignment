@extends('layouts.main')
    @section('content')
            <div class="content">
                <div class="container-fluid">
                    <h4 class="page-title">User List </h4>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                     <div class="card-title d-flex justify-content-between align-items-center">
                                        Short Url List
                                         @can('create short url')
                                         <div>
                                            <a href="{{ route('short_urls.create') }}" style="background-color:#007bff" class="bg-gray-800 hover:bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Create Short URL</a>
                                         </div>

                                        @endcan
                                    </div>

                                     @if (session('success'))
                                        <div class="alert alert-success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Short Code</th>
                                                <th>Short Url</th>
                                                <th>Original URL</th>
                                                <th>Created By</th>
                                                <th>Created At</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           @forelse($shortUrls as $key => $shortUrl)
                                            @php
                                                $key = 1 + $key;
                                            @endphp
                                            <tr>
                                                <th scope="row">{{ $key }}</th>
                                                {{-- <td> <a href="{{ $shortUrl->original_url }}" target="_blank" class="underline">{{ $shortUrl->short_code }}</a></td> --}}
                                                <td> <a href="{{ route('short_urls.redirect', $shortUrl->short_code) }}" target="_blank" class="underline">{{ $shortUrl->short_code }}</a></td>
                                                <td>{{ route('short_urls.redirect', $shortUrl->short_code) }}</td>
                                                <td> {{ $shortUrl->original_url }} </td>
                                                <td>{{ $shortUrl->user->name ?? 'N/A' }}</td>
                                                <td>{{ $shortUrl->created_at->format('Y-m-d H:i') }}</td>
                                            </tr>

                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center">No record found.</td>
                                                </tr>
                                            @endforelse
                                                @if ($shortUrls->hasPages())
                                                 <tr>
                                                    <td colspan="5" class="text-center">
                                                        <div class="d-flex justify-content-center">
                                                            {{ $shortUrls->appends(request()->query())->links('pagination::bootstrap-4') }}
                                                        </div>
                                                    </td>
                                                </tr>

                                                @endif
                                        </tbody>
                                    </table>
                                </div>
							</div>
                        </div>
                    </div>
                </div>
            </div>
    @endsection
    @push('scripts')
    <script>
        $('.copy_text').click(function (e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function(e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            alert('copied text: ' + copyText);
        });

    </script>

    @endpush
