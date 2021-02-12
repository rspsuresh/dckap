@include('nav')
@extends('master')
@section('content')
    <title>Product List</title>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <form action="">
                    <h2>Search Product</h2>
                    <div class="form-group">
                        <input type="text" name="q" placeholder="Search...!" class="form-control"/>
                        <input type="submit" class="btn btn-primary " value="Search"/>
                        <a href="{{url('product-list')}}" class="btn btn-danger">Reset </a>
                    </div>
                </form>
            </div>
            <div class="col-md-10">
                <table  class="table table-bordered">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Name</th>
                        <th>Short Description</th>
                        <th>Description</th>
                        <th>Category</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($data as $product)
                        <tr>
                            <td>{{$no++}}</td>
                            <td>{{$product->name}}</td>
                            <td>{{$product->short_desc}}</td>
                            <td>{{$product->description}}</td>
                            <td>{{$product->categoryrl->cat_name}}</td>
                            <td><a href="{{ url('product-view/'.$product->id)}}">view</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $data->links() }}
            </div>
        </div>
    </div>
@endsection
