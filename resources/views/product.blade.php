@include('nav')
@extends('master')
@section('content')
    <title>Add Product</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 m-auto">


            <div class="card-body">
                @if(Session::has('success'))
                    <div class="alert alert-success">
                        {{ Session::get('success') }}
                        @php
                            Session::forget('success');
                        @endphp
                    </div>
                @endif
                <form method="post" action="{{url('product-store')}}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="category"> Category </label>
                        <select class="form-control" id="category" name="category">
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->cat_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pname">Product Name</label>
                        <input type="text" name="pname" class="form-control" placeholder="Product Name" id="pname">
                        {!! $errors->first('pname', '<small class="text-danger">:message</small>') !!}
                    </div>
                    <div class="form-group">
                        <label for="short_desc">Short Description</label>
                        <input type="text" name="short_desc" class="form-control" placeholder="Short Description"
                               id="short_desc">
                        {!! $errors->first('short_desc', '<small class="text-danger">:message</small>') !!}
                    </div>
                    <div class="form-group">
                        <label for="description"> Description</label>
                        <textarea name="description" class="form-control"  id="description"></textarea>
                        {!! $errors->first('description', '<small class="text-danger">:message</small>') !!}
                    </div>
                    <div class="form-group">
                        <label for="pname">Images</label>
                        <input type="file" multiple name="images[]" accept="image/*" id="images">
                        {!! $errors->first('images', '<small class="text-danger">:message</small>') !!}
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
    </div>
@endsection
