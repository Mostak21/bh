@extends('backend.layouts.app')

@section('content')

<div class="row">
    <div class="col-lg-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="fs-18 mb-0">{{translate('Send Bulk SMS')}}</h3>
            </div>
            <form class="form-horizontal" action="{{ route('sms.send') }}" method="POST" enctype="multipart/form-data">
            	@csrf
                <div class="card-body">

                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="source">{{translate('Custom number')}}</label>
                        <div class="col-sm-10">
                            <input type="number" id="source" class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="name">{{translate('Mobile Users')}}</label>
                        <div class="col-sm-10">
                            <select class="form-control aiz-selectpicker" data-live-search="true" name="user_phones[]" multiple>
                                <option id="result" value="" selected hidden></option>
                                @foreach($users as $user)
                                    @if ($user->phone != null)
                                        <option value="{{$user->phone}}">{{$user->name}} - {{$user->phone}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-sm-2 control-label" for="name">{{translate('SMS content')}}</label>
                        <div class="col-sm-10">
                            <textarea class="form-control" name="content" required>{{session('BulkSMSBody')}}</textarea>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-form-label">{{translate('Template ID')}}</label>
                        <div class="col-md-10">
                            <input type="text" name="template_id"  class="form-control" placeholder="{{translate('Template Id')}}">
                            <small class="form-text text-danger">{{ ('**N.B : Template ID is Required Only for Fast2SMS DLT Manual **') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" type="submit">{{translate('Send')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    const source = document.getElementById('source');
    const result = document.getElementById('result');

    const inputHandler = function(e) {
        result.value = e.target.value;
        result.innerHTML = e.target.value;
        document.getElementsByClassName('filter-option-inner-inner')[0].innerHTML = e.target.value ;
    }

    source.addEventListener('input', inputHandler);
    source.addEventListener('propertychange', inputHandler); // for IE8
    // Firefox/Edge18-/IE9+ donâ€™t fire on <select><option>
    // source.addEventListener('change', inputHandler);
</script>

@endsection
