@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('SL')</th>
                                    <th>@lang('Knowledge Base')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($jsonFiles as $file)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration }}
                                        </td>
                                        <td>
                                            {{ basename($file) }}
                                        </td>
                                        <td>
                                            <button 
                                                class="btn btn-sm btn-outline--danger ms-1 confirmationBtn"
                                                data-question="@lang('Are you sure to delete this Knowledge Base?')"
                                                data-action="{{ route('admin.knowledge.base.delete', basename($file)) }}"
                                            >
                                                <i class="la la-trash"></i> @lang('Remove')
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editModalLabel">@lang('Edit Language')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close"><i class="las la-times"></i></button>
                </div>
                <form method="post" action="{{ route('admin.knowledge.base.add')}}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-12 form-group mb-3">
                                <label>@lang('Name')</label>
                                <div class="col-sm-12">
                                    <input type="text" class="form-control" value="{{ old('name') }}" name="name" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a class="btn btn-outline--primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="las la-plus"></i>@lang('Add New')</a>
@endpush
