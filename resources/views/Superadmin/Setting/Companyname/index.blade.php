@extends('layouts.app')

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Setting</h3>
            </div>

            <div class="card-body">
                <form
                    action="{{ isset($companyname) ? route('settings.update', $companyname->id) : route('settings.store') }}"
                    method="POST" enctype="multipart/form-data">

                    @csrf
                    @if (isset($companyname))
                        @method('PUT')
                    @endif


                    <div class="form-group mb-4">
                        <label for="name_company" class="form-label">Company Name</label>
                        <input type="text" class="form-control" name="name_company" id="name_company"
                            value="{{ isset($companyname) ? $companyname->name_company : '' }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="image" class="form-label">Company Image</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="image" id="image" accept="image/*">
                            <label class="custom-file-label" for="image">Choose file</label>
                        </div>
                        <div class="mt-2">
                            @if (isset($companyname) && $companyname->image)
                                <p>Current Image:</p>
                                <img src="{{ asset('storage/' . $companyname->image) }}" alt="Current Image"
                                    class="img-fluid img-thumbnail" width="150">
                            @endif
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        {{ isset($companyname) ? 'Update' : 'Create' }}
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
