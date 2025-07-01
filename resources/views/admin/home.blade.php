@extends('admin')

@section('content')

    <div class="container-fluid">
        <div class="row my-2">
            <div class="col-lg-6 py-1">
                <div class="card">
                    <div class="card-body">
                        <canvas id="chLine"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 py-1">
                <div class="card">
                    <div class="card-body">
                        <canvas id="chBar"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="row py-2">
            <div class="col-lg-4 col-sm-4 py-1">
                <div class="card">
                    <div class="card-body">
                        <canvas id="chDonut1"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 py-1">
                <div class="card">
                    <div class="card-body">
                        <canvas id="chDonut2"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-4 py-1">
                <div class="card">
                    <div class="card-body">
                        <canvas id="chDonut3"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/home.js') }}"></script>
@endsection