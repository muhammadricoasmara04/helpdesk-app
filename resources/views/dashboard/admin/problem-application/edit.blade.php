@extends('layouts.main-dashboard')

@section('container')
    <div class="container">
        <h2>Edit Problem</h2>

        <form action="{{ route('application-problem.update', $problem->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="application_id">Aplikasi</label>
                <select name="application_id" class="form-control">
                    @foreach ($applications as $app)
                        <option value="{{ $app->id }}" {{ $app->id == $problem->application_id ? 'selected' : '' }}>
                            {{ $app->application_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="problem_name">Nama Problem</label>
                <input type="text" name="problem_name" value="{{ old('problem_name', $problem->problem_name) }}"
                    class="form-control">
            </div>

            <div class="mb-3">
                <label for="description">Deskripsi</label>
                <textarea name="description" class="form-control">{{ old('description', $problem->description) }}</textarea>
            </div>

        
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="" class="btn btn-secondary">Batal</a>
        </form>
    </div>
@endsection
