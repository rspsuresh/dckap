@extends('master')

@section('content')
    <meta name="_base_url" content="{{ url('/') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registration</title>
<div class="container mt-3">
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 m-auto">
            <form method="post" action=" {{ url('user-store') }} ">
                <div class="card shadow mb-4">
                    <div class="car-header bg-success pt-2">
                        <div class="card-title font-weight-bold text-white text-center"> Laravel 6 User Registration </div>
                    </div>
                    <div class="card-body">
                            @if(Session::has('success'))
                                <div class="alert alert-success">
                                    {{ Session::get('success') }}
                                    @php
                                        Session::forget('success');
                                    @endphp
                                </div>
                            @endif
                        <div class="form-group">
                            <label for="first_name"> First Name </label>
                            <input type="text" name="first_name" id="first_name" class="form-control" placeholder="Enter First Name" value="{{ old('first_name') }}"/>
                            {!! $errors->first('first_name', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label for="last_name"> Last Name </label>
                            <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Enter Last Name" value="{{ old('last_name') }}"/>
                            {!! $errors->first('last_name', '<small class="text-danger">:message </small>') !!}
                        </div>

                        <div class="form-group">
                            <label for="email"> E-mail </label>
                            <input type="text" name="email" id="email" class="form-control" placeholder="Enter E-mail" value="{{ old('email') }}"/>
                            {!! $errors->first('email', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label for="password"> Password </label>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Enter Password" value="{{ old('password') }}"/>
                            {!! $errors->first('password', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label for="confirm_password"> Confirm Password </label>
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" value="{{ old('confirm_password') }}">
                            {!! $errors->first('confirm_password', '<small class="text-danger">:message</small>') !!}
                        </div>

                        <div class="form-group">
                            <label for="phone"> Address </label>
                            <textarea name="address" class="form-control" id="address"></textarea>
                            {!! $errors->first('address', '<small class="text-danger">:message</small>') !!}
                        </div>
                                <div class="form-group">
                                    <label for="phone"> Country </label>
                                    <select class="form-control" id="country" name="country">
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    {!! $errors->first('country', '<small class="text-danger">:message</small>') !!}
                                </div>
                                <div class="form-group">
                                    <label for="phone"> State </label>
                                    <select class="form-control" id="state" name="state">

                                    </select>
                                    {!! $errors->first('state', '<small class="text-danger">:message</small>') !!}
                                </div>
                                <div class="form-group">
                                    <label for="phone"> City </label>
                                    <select class="form-control" id="city" name="city">

                                    </select>
                                    {!! $errors->first('city', '<small class="text-danger">:message</small>') !!}
                                </div>
                    </div>
                    <div class="card-footer d-inline-block">
                        <button type="submit" class="btn btn-success"> Register </button>
                    <p class="float-right mt-2"> Already have an account?  <a href="{{ url('user-login')}}" class="text-success"> Login </a> </p>
                    </div>
                    @csrf
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
<script>
    $('#country').change(function(){
        var countryID = $(this).val();
        if(countryID){
            $.ajax({
                type:"get",
                url:"{{url('states')}}/"+countryID,
                success:function(res){
                    if(res){
                        $("#state").empty();
                        $("#state").append('<option>Select</option>');
                        $.each(res,function(key,value){
                            $("#state").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#state").empty();
                    }
                }
            });
        }else{
            $("#state").empty();
            $("#city").empty();
        }
    });
    $('#state').on('change',function(){
        var stateID = $(this).val();
        if(stateID){
            $.ajax({
                type:"GET",
                url:"{{url('cities')}}/"+stateID,
                success:function(res){
                    if(res){
                        $("#city").empty();
                        $.each(res,function(key,value){
                            $("#city").append('<option value="'+key+'">'+value+'</option>');
                        });

                    }else{
                        $("#city").empty();
                    }
                }
            });
        }else{
            $("#city").empty();
        }

    });
</script>
@endsection
