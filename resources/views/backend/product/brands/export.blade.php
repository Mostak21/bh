@extends('backend.layouts.app')

@section('content')

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <span><a class="h6">All Brands</a></span>
            <span right style="float:right">
            <a class="btn btn-soft-primary   btn-sm" href="{{route('product_bulk_export_google.index')}}" title="{{ translate('Export') }}">
		      <i class="las la-download"></i>
                Export All
		      </a>
        </span>
        </div>
    </div>

    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header row gutters-5">
                    <div class="col text-center text-md-left">
                        <h5 class="mb-md-0 h6">{{ translate('Export By Brands') }}</h5>
                    </div>
                    <div class="col-md-4">
                        <form class="" id="sort_brands" action="" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="search" name="search"@isset($sort_search) value="{{ $sort_search }}" @endisset placeholder="{{ translate('Type name & Enter') }}">
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table aiz-table mb-0">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>{{translate('Name')}}</th>
                            <th>{{translate('Logo')}}</th>
                            <th class="text-right">{{translate('Download')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($brands as $key => $brand)
                            <tr>
                                <td>{{ ($key+1) + ($brands->currentPage() - 1)*$brands->perPage() }}</td>
                                <td>{{ $brand->getTranslation('name') }}</td>
                                <td>
                                    <img src="{{ uploaded_asset($brand->logo) }}" alt="{{translate('Brand')}}" class="h-50px">
                                </td>
                                <td class="text-right">
                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('product_bulk_export.single_brand', ['id'=>$brand->id] )}}" title="{{ translate('Export') }}">
                                        <i class="las la-download"></i>
                                    </a>
                                    {{--                                    <a class="btn btn-soft-primary btn-icon btn-circle btn-sm" href="{{route('product_bulk_export_google.index', ['id'=>$brand->id] )}}" title="{{ translate('Export') }}">--}}
                                    {{--                                        <i class="las la-google"></i>--}}
                                    {{--                                    </a>--}}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="aiz-pagination">
                        {{ $brands->appends(request()->input())->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

