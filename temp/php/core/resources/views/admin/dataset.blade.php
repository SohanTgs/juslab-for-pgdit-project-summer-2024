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
                            @forelse ($jsonFiles as $file)
                                <tr>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td>
                                        {{ basename($file) }}
                                    </td>
                                    <td>
                                        <a  href="{{ route('admin.dataset.edit', basename($file)) }}"
                                            class="btn btn-sm btn-outline--primary ms-1"
                                        >
                                            <i class="la la-pen"></i> @lang('Edit')
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                </tr>
                            @endforelse
                        </table><!-- table end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection