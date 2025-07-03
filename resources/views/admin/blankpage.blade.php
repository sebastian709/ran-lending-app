@extends('admin')

@section('content')
    <div class="container my-5">
        <div class="paper-layout p-5 mx-auto">
            <div class="text-center mb-4">
                <h2 class="fw-bold">Certificate of Completion</h2>
                <hr class="w-25 mx-auto mb-4">
            </div>

            <p class="fs-5 mb-4 text-center">This is to certify that</p>

            <h3 class="fw-bold text-primary mb-3 ps-4 text-center">Alejandro Bermudo</h3>

            <p class="fs-5 mb-4 text-center">has successfully completed the training course</p>

            <h4 class="text-secondary ps-4 mb-5 text-center">"Web Development Bootcamp"</h4>

            <p class="fs-5 text-center">Date of Completion: <strong>July 2, 2025</strong></p>

            <div class="row mt-5 pt-5">
                <div class="col-md-6 text-center">
                    @include('admin.components.signature-box', [
                        'name' => 'Sebastian Jabson',
                        'position' => 'Chef'
                    ])
                </div>

                <div class="col-md-6 text-center">
                    @include('admin.components.signature-box', [
                        'name' => 'Lordan Lingat',
                        'position' => 'Mentor'
                    ])           
                </div>

                <div class="text-center mt-5">
                    <button class="btn btn-outline-primary" onclick="window.print()">🖨️ Print Document</button>
                </div>
            </div>
        </div>
@endsection
