@extends('admin.container')

@section('content')
    <div class="container my-5">
        <div class="paper-layout p-5 mx-auto">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Test Global function</h2>
                <hr class="w-25 mx-auto mb-4">
            </div>

            <div class="container-fluid cTestTriggerNotif">
                <div class="row">
                    <div class="col-lg-12 form-group">
                        <label for="">table_id</label>
                        <input type="text" class="form-control table_id">
                    </div>
                    <div class="col-lg-12 form-group">
                        <label for="">target_type</label>
                        <input type="text" class="form-control target_type">
                    </div>
                    <div class="col-lg-12 form-group">
                        <label for="">level_id</label>
                        <input type="text" class="form-control level_id">
                    </div>
                    <div class="col-lg-12 form-group">
                        <label for="">user_id</label>
                        <input type="text" class="form-control user_id">
                    </div>
                    <div class="col-lg-12 form-group">
                        <label for="">group_user_id</label>
                        <input type="text" class="form-control group_user_id">
                    </div>
                    <div class="col-lg-12 form-group">
                        <label for="">icon</label>
                        <input type="text" class="form-control icon">
                    </div>
                    <div class="col-lg-12 form-group">
                        <label for="">message</label>
                        <input type="text" class="form-control message">
                    </div>
                    <div class="col-lg-12 form-group">
                        <label for="">data_url</label>
                        <input type="text" class="form-control data_url">
                    </div>
                </div>
                <button>submit</button>
            </div>
        </div>
@endsection