@extends('admin.layouts.app')

@section('panel')
<div class="card">
    <div class="card-body">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <form action="{{ route('admin.dataset.update', $fileName) }}" method="POST">
                    @csrf
                    <div id="qa-container">
                        @foreach($content as $index => $item)
                        <div class="row gy-4 mb-4 border-bottom pb-3 qa-item" data-index="{{ $index }}">
                            <div class="col-lg-12">
                                <label>@lang('Question')</label>
                                <input class="form-control" type="text" 
                                       name="content[{{ $index }}][question]" 
                                       required value="{{ $item['question'] }}">
                            </div>
                            
                            <div class="col-lg-12 answers-container">
                                <label>@lang('Answers')</label>
                                @foreach($item['answer'] as $ansIndex => $answer)
                                <div class="input-group mb-2 answer-group">
                                    <input class="form-control" type="text" 
                                           name="content[{{ $index }}][answer][]" 
                                           required value="{{ $answer }}">
                                    <button class="btn btn-danger remove-answer" type="button">
                                        <i class="las la-trash"></i>
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="col-lg-12 text-end">
                                <button type="button" class="btn btn-info add-answer me-2">
                                    <i class="las la-plus"></i> @lang('Add Answer')
                                </button>
                                @if($fileName != 'default.json')
                                    <button type="button" class="btn btn-danger remove-qa">
                                        <i class="las la-trash"></i> @lang('Remove Question')
                                    </button>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12 text-center">
                            
                            @if($fileName != 'default.json')
                                <button type="button" id="add-qa" class="btn btn-primary me-3">
                                    <i class="las la-plus"></i> @lang('Add New Question')
                                </button>
                            @endif
                            
                            <button type="submit" class="btn btn-success">
                                <i class="las la-save"></i> @lang('Save Changes')
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('script')
    <script>
        (function ($) {
            "use strict";

            // Add new question-answer set
            $('#add-qa').click(function() {
                const index = Date.now();
                const newItem = `
                <div class="row gy-4 mb-4 border-bottom pb-3 qa-item" data-index="${index}">
                    <div class="col-lg-12">
                        <label>@lang('Question')</label>
                        <input class="form-control" type="text" 
                            name="content[${index}][question]" required>
                    </div>
                    
                    <div class="col-lg-12 answers-container">
                        <label>@lang('Answers')</label>
                        <div class="input-group mb-2 answer-group">
                            <input class="form-control" type="text" 
                                name="content[${index}][answer][]" required>
                            <button class="btn btn-danger remove-answer" type="button">
                                <i class="las la-trash"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-lg-12 text-end">
                        <button type="button" class="btn btn-info add-answer me-2">
                            <i class="las la-plus"></i> @lang('Add Answer')
                        </button>
                        <button type="button" class="btn btn-danger remove-qa">
                            <i class="las la-trash"></i> @lang('Remove Question')
                        </button>
                    </div>
                </div>`;
                
                $('#qa-container').append(newItem);
            });
            
            // Add new answer to a question
            $(document).on('click', '.add-answer', function() {
                const container = $(this).closest('.row').find('.answers-container');
                const index = $(this).closest('.qa-item').data('index');
                
                const newAnswer = `
                <div class="input-group mb-2 answer-group">
                    <input class="form-control" type="text" 
                        name="content[${index}][answer][]" required>
                    <button class="btn btn-danger remove-answer" type="button">
                        <i class="las la-trash"></i>
                    </button>
                </div>`;
                
                container.append(newAnswer);
            });
            
            // Remove answer
            $(document).on('click', '.remove-answer', function() {
                if ($(this).closest('.answers-container').find('.answer-group').length > 1) {
                    $(this).closest('.answer-group').remove();
                } else {
                    notify('error', 'Each question must have at least one answer');
                }
            });
            
            // Remove question-answer set
            $(document).on('click', '.remove-qa', function() {
                if ($('#qa-container .qa-item').length > 1) {
                    $(this).closest('.qa-item').remove();
                } else {
                    notify('error', 'You must have at least one question');
                }
            });
            
            function notify(type, message) {
                // Use your existing notification system
                const notify = [];
                notify.push({type: type, message: message});
                // This should match however your notification system works
            }

        })(jQuery);
    </script>
@endpush