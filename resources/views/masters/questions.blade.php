@extends('layouts.main')
@section('content')
<div class="container">
    <div class="container-fluid">
         <h4 class="page-title">Questions List </h4>
         <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <h5 class="card-title mb-0">Survey Form</h5>

                            <form action="{{ route('questions') }}" method="GET" class="d-flex flex-wrap align-items-center justify-content-end gap-2 ms-auto" style="min-width: 0;">
                                <div class="d-flex align-items-center gap-2">
                                    <label for="supplier_id" class="mb-0 text-nowrap">Supplier</label>
                                    <select id="supplier_id" name="supplier_id" class="form-control form-control-sm" style="min-width: 220px;">
                                        <option value="">All Suppliers</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                {{ $supplier->name }} - {{ $supplier->code }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="btn btn-primary btn-sm">Filter</button>

                                @if(request('supplier_id'))
                                    <a href="{{ route('questions') }}" class="btn btn-secondary btn-sm">Reset</a>
                                @endif
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <form>
                            <!-- Age -->
                            @foreach($questions as $question)
                            <div class="question form-group">
                                <label>{{ $question->question }}</label>

                                @switch($question->control_type_id)
                                    @case(0)
                                         <input type="text" name="answers[{{ $question->id }}]" class="form-control">
                                        @break
                                    {{-- Text --}}
                                    @case(2)
                                        <input type="text" name="answers[{{ $question->id }}]" class="form-control">
                                        @break

                                    {{-- Number --}}
                                    @case(6)
                                        <input type="number"
                                            name="answers[{{ $question->id }}]"
                                            min="{{ $question->from_range }}"
                                            max="{{ $question->to_range }}"
                                            class="form-control">
                                        @break

                                    {{-- Dropdown --}}
                                    @case(1)

                                        <select name="answers[{{ $question->id }}]" class="form-control">
                                            <option value="">Select</option>
                                            @foreach($question->mapped_options as $option_key => $option)
                                                <option value="{{ $option_key }}">
                                                    {{ $option }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @break

                                    {{-- Checkbox --}}
                                    @case(4)
                                        <div class="row">
                                        @foreach($question->mapped_options as $option_key => $option)
                                        <div class="col-md-6">
                                            <div class="form-check">
                                                <label class="form-check-label">
                                                <input type="checkbox"
                                                    class="form-check-input"
                                                    name="answers[{{ $question->id }}][]"
                                                    value="{{ $option_key }}">
                                                    <span class="form-check-sign"></span>
                                                    {{ $option }}
                                                </label>
                                            </div>
                                        </div>
                                        @endforeach
                                        </div>
                                        @break

                                @endswitch
                            </div>
                            @endforeach
                            @if ($questions->hasPages())
                                <div class="d-flex justify-content-center">
                                    {{ $questions->appends(request()->query())->links('pagination::bootstrap-4') }}
                                </div>
                            @endif

                            {{-- <div class="text-end mt-4">
                                <button class="btn btn-success btn-lg">
                                    Submit Survey
                                </button>
                            </div> --}}

                        </form>

                    </div>

                </div>
            </div>
         </div>
    </div>
</div>
@endsection
