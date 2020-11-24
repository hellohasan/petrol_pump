@extends('layouts.dashboard')
@section('style')
    <link href="{{asset('assets/admin/css/select2.min.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/bootstrap-datetimepicker.css') }}">
@endsection
@section('content')
    <section id="horizontal-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="horz-layout-basic">{{$page_title}}</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                <li><a data-action="close"><i class="ft-x"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">

                            {!! Form::open(['class'=>'form-horizontal','role'=>'form']) !!}

                            <div class="form-body">

                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Payment Date</strong></label>
                                    <div class="col-md-12">
                                        <div class='input-group'>
                                            <input type='text' name="payment_date" class="form-control font-weight-bold" id='datetimepicker1' value="{{ \Carbon\Carbon::now()->format('Y-m-d H:i:s') }}" />
                                            <span class="input-group-addon"><span class="fa fa-calendar"></span></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Product Company</strong></label>
                                    <div class="col-md-12">
                                        <select name="company_id" id="company_id" class="form-control select2 font-weight-bold">
                                            <option value="" class="font-weight-bold">Select One</option>
                                            @foreach($company as $c)
                                                <option value="{{ $c->id }}" class="font-weight-bold">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Invoice Number</strong></label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input name="reference" class="form-control font-weight-bold" value="{{ old('reference') }}" placeholder="Reference Number"/>
                                            <span class="input-group-addon"><strong><i class="fa fa-tasks"></i></strong></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Sended Amount</strong></label>
                                    <div class="col-md-12">
                                        <div class="input-group">
                                            <input class="form-control font-weight-bold input-lg" id="amount" name="amount" value="{{ old('amount') }}" placeholder="Sended Amount" type="text" >
                                            <span class="input-group-addon"><strong>{{ $basic->currency }}</strong></span>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <label class="col-md-12"><strong style="text-transform: uppercase;">Short Details</strong></label>
                                    <div class="col-md-12">
                                        <textarea name='details' id="" cols="30" rows="5" placeholder="Short Details" class="form-control font-weight-bold input-lg"></textarea>
                                    </div>
                                </div>


                                <div class="col-md-12">
                                    <button type="submit" id="paymentButton" class="btn btn-primary btn-block font-weight-bold btn-lg text-uppercase"><i class="fa fa-send"></i> Submit payment</button>
                                </div>
                            </div>
                            {!! Form::close() !!}



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section><!---ROW-->
@endsection
@section('scripts')
    <script src="{{asset('assets/admin/js/select2.full.min.js')}}" type="text/javascript"></script>

    <script src="{{ asset('assets/admin/js/moment-with-locales.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/admin/js/picker-date-time.js') }}" type="text/javascript"></script>

    <script>
        $(function () {
            $('.select2').select2();
        });
    </script>

@endsection