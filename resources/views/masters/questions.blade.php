@extends('layouts.main')
@section('content')
<div class="container">
    <div class="container-fluid">
         <h4 class="page-title">Survey List </h4>
         <div class="row">
            <div class="col-md-12">
                <div class="card mt-4">
                    <div class="card-header">
                         <div class="card-title d-flex justify-content-between align-items-center">Survey Form</div>
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
