@extends('admin.layout')
@section('content')
    <style>
        .resetbtn {
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
    <div class="container-fluid content-inner pb-0">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-title mb-0">Add AD</h2>
                <div class="card p-2">
                    <div class="mb-3 d-flex justify-content-end">
                        <a href="{{ route('ad_history') }}" class="btn btn-info">View History</a>
                    </div>

                    <form action="{{ route('upload_ad') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row justify-content-center mt-3 mb-3">
                            <!-- Image Upload -->
                            <div class="form-group col-md-8 mb-3">
                                <label class="form-label" for="image">Upload Image</label>
                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            </div>

                            <!-- Video Upload -->
                            <div class="form-group col-md-8 mb-3">
                                <label class="form-label" for="video">Upload Video</label>
                                <input type="file" class="form-control" id="video" name="video" accept="video/*">
                            </div>


                            <!-- District Dropdown -->
                            <div class="form-group col-md-8 mb-3">
                                <label class="form-label" for="district">Select District</label>
                                <select class="form-control" id="district" name="district" required>
                                    <option value="">Select a District</option>
                                    @foreach ($districts as $district)
                                        <option value="{{ $district->id }}">{{ $district->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date -->
                            <div class="form-group col-md-8 mb-3">
                                <label class="form-label" for="date">Date</label>
                                <input type="date" class="form-control" id="date" name="date" required>
                            </div>

                            <!-- Time -->
                            <div class="form-group col-md-8 mb-3">
                                <label class="form-label" for="time">Time</label>
                                <input class="form-control" id="time" name="time" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="resetbtn mb-5">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>



                </div>
            </div>
        </div>
    </div>
@endsection


