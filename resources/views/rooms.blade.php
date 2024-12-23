@extends('layouts.app')
@section('content')

<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<style>
    .custom-bg {
        background-color: var(--teal);
        border: 1px solid var(--teal);
    }
</style>
<title>ResideMe - Rooms</title>
<div class="container">
    <div class="row">
        <div class="col-lg-1 col-md-12 px-4"></div>
        <div class="col-lg-10 col-md-12 px-4">
            <div class="card mb-5 border-0 shadow">
                <div class="row g-0 p-1 align-items-center">
                    @if(auth()->check()) <!-- Check if the user is logged in -->
                    <span class="d-flex align-items-center">
                        @if(isset($booking)) <!-- Check if there is a booking available -->
                        @if(strtolower(trim($booking->status)) === 'deleted') <!-- Check if the booking is marked as deleted -->
                        <div class="col-md-7 px-lg-2 px-md-2 px-0">
                            <h6>Your previous booking request has been deleted.</h6>
                            <!-- Check if the user has a new booking that is pending or approved -->
                            @php
                            $newBooking = \App\Models\Booking::where('user_id', auth()->user()->user_id)
                            ->whereIn('status', ['pending', 'approved'])
                            ->where('id', '!=', $booking->id) // Exclude the current deleted booking
                            ->first();
                            @endphp
                            @if($newBooking) <!-- If a new booking exists -->
                            <h6>You have made a new booking request:</h6>
                        </div>
                        <div class="col-md-3 mt-lg-0 mt-md-0 mt-4 text-center">

                            <a href="{{ route('room.showbooking', $newBooking->id) }}" class="btn btn-warning" style="background-color: #D5AF07;">View New Booking Request</a>
                        </div>
                        @else <!-- If no new booking exists -->
                        <div class="col-md-7 px-lg-2 px-md-2 px-0">

                            <h6>No new bookings have been made yet.</h6>
                        </div>
                        @endif

                        @elseif(strtolower(trim($booking->status)) === 'rejected') <!-- Check if the booking is marked as rejected -->
                        <div class="col-md-7 px-lg-2 px-md-2 px-0">
                            <h6>Your previous booking request was rejected.</h6>
                            <!-- Check if the user has a new booking that is pending or approved -->
                            @php
                            $newBooking = \App\Models\Booking::where('user_id', auth()->user()->user_id)
                            ->whereIn('status', ['pending', 'approved'])
                            ->where('id', '!=', $booking->id) // Exclude the current rejected booking
                            ->first();
                            @endphp
                            @if($newBooking) <!-- If a new booking exists -->
                            <h6>You have made a new booking request:</h6>
                        </div>
                        <div class="col-md-3 mt-lg-0 mt-md-0 mt-4 text-center">

                            <a href="{{ route('room.showbooking', $newBooking->id) }}" class="btn btn-warning" style="background-color: #D5AF07;">View New Booking Request</a>
                        </div>
                        @else <!-- If no new booking exists -->
                        <div class="col-md-10 px-lg-2 px-md-2 px-0">

                            <h6>No new bookings have been made yet.</h6>
                        </div>
                        @endif

                        @else <!-- If the booking is neither deleted nor rejected, show the current booking -->
                        <div class="col-md-7 px-lg-2 px-md-2 px-0">
                            <h6>Check your current booking request:</h6>
                        </div>
                        <div class="col-md-3 mt-lg-0 mt-md-0 mt-4 text-center">


                            <a href="{{ route('room.showbooking', $booking->id) }}" class="btn custom-bg" style="background-color: #D5AF07;">Booking Request View</a>
                        </div>
                        @endif
                        @else
                        <div class="col-md-10 px-lg-2 px-md-2 px-0">

                            <h6>You have not made a booking yet.</h6>
                        </div>
                        @endif
                    </span>
                    @else
                    <div class="col-md-10 px-lg-2 px-md-2 px-0">

                        <h6>Please log in to view and manage your booking request.</h6> <!-- Message if user is not logged in -->
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
</div>




<div class="bg-light">
    <div class="my-5 px-4">
        <h2 class="fw-bold h-font text-center">OUR ROOMS</h2>
        <div class="h-line bg-dark"></div>
    </div>
    <div class="container">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-12 mb-lg-0 mb-4 px-lg-0">
                        <nav class="navbar navbar-expand-lg navbar-light bg-white rounded shadow">
                            <div class="container-fluid flex-lg-column align-items-stretch">
                                <h4 class="fw-bold h-font mt-2">FILTER</h4>
                                <hr><br>

                                <button class="navbar-toggler shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#filterdropdown" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>

                                <div class="collapse navbar-collapse flex-column mt-2 align-items-stretch" id="filterdropdown">
                                    <form action="{{ route('rooms.filter') }}" method="GET" class="filter-form">
                                        <!-- Room Category -->
                                        <div>
                                            <label for="category">Room Category:</label>
                                            <select name="category" id="category" class="form-control">
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Room Number -->
                                        <!-- Room Number -->
                                        <div>
                                            <label for="room_no">Room Number:</label>
                                            <select name="room_no" id="room_no" class="form-control">
                                                <option value="">Select Room Number</option>
                                                @if(request()->category)
                                                @foreach($roomNumbers as $room)
                                                <option value="{{ $room->room_no }}" {{ request('room_no') == $room->room_no ? 'selected' : '' }}>
                                                    {{ $room->room_no }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <!-- Building Block (Auto-filled based on selected room) -->
                                        <div class="form-group">
                                            <label for="block_name">Building Block:</label>
                                            <input type="text" name="block_name" id="block_name" class="form-control" placeholder="Block Name"
                                                value="{{ $selectedRoom ? $selectedRoom->block->block_name : '' }}" readonly>
                                        </div>

                                        <!-- Number of Beds (Dynamic based on selected room) -->
                                        <div class="form-group">
                                            <label for="beds">Number of Beds:</label>
                                            <select name="beds" id="beds" class="form-control">
                                                <option value="">Select a Bed No</option>
                                                @if($selectedRoom)
                                                @foreach($roomBeds as $bed)
                                                <option value="{{ $bed->id }}" {{ request('beds') == $bed->id ? 'selected' : '' }}>
                                                    {{ $bed->bed_no }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-warning">Search Rooms</button>
                                        </div>
                                    </form>
                                    @if(isset($roomAvailability) && $roomAvailability->isNotEmpty())
                                    @foreach($roomAvailability as $room)
                                    <p>{{ $room->isAvailable ? 'Room Available' : 'No booking available yet' }}</p>
                                    @endforeach
                                    @else
                                    <p>No rooms available based on the selected filters.</p>
                                    @endif
                                </div>

                            </div>


                        </nav>
                    </div>



                    <div class="col-lg-9 col-md-12 px-4">
                        @foreach ($rooms as $room)
                        <div class="room-card mb-5 border-0 shadow">
                            <div class="row g-0 p-3 align-items-center">
                                <div class="col-md-5 mb-lg-0 mb-md-0 mb-3">
                                    <img src="{{ asset('storage/' . $room->picture) }}" class="img-fluid rounded-start" alt="Room Image">
                                    <h5 class="m-0 ms-3">{{ $room->name }}</h5>
                                </div>
                                <div class="col-md-5 px-lg-3 px-md-3 px-0">
                                    <h5 class="mb-3">{{ $room->room_no }} - {{ $room->category->name }}</h5>
                                    <!-- facilities Section -->
                                    <div class="facilities mb-3">
                                        <h6 class="mb-1">facilities</h6>
                                        @if($room->facilities && $room->facilities->isNotEmpty())
                                        @foreach ($room->facilities as $facility)
                                        <span class="badge rounded-pill bg-light text-dark text-wrap">{{ $facility->name }}</span>
                                        @endforeach
                                        @else
                                        <p>No facilities available</p>
                                        @endif
                                    </div>

                                    <div class="Guests">
                                        <div class="row mb-2">
                                            <div class="col-lg-5">
                                                <h6 class="mt-2">Building Block</h6>
                                                <span class="badge rounded-pill bg-light text-dark text-wrap">
                                                    {{ $room->block->block_name }} Block
                                                </span>
                                            </div>
                                            <div class="col-lg-4">
                                                <h6 class="mt-2">Guests</h6>
                                                <span class="badge rounded-pill bg-light text-dark text-wrap">
                                                    {{ $room->number_of_members }} Members
                                                </span>
                                            </div>
                                            <div class="col-lg-3">
                                                <h6 class="mt-2">Beds</h6>
                                                <span class="badge rounded-pill bg-light text-dark text-wrap">
                                                    {{ $room->beds->count() }} Beds
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="description">
                                        <h6 class="mt-1">Description</h6>
                                        <span class="badge rounded-pill bg-light text-dark text-wrap">
                                            {{ $room->description }}
                                        </span>
                                    </div>


                                    <!-- Show availability-->
                                    @if ($room->beds->isEmpty())
                                    <p>No booking available yet</p>
                                    @else
                                    <p>Room Available</p>
                                    @endif
                                </div>
                                <div class="col-md-2 mt-lg-0 mt-md-0 mt-4 text-center">
                                    <h5 class="mb-4">RS.{{ $room->room_charge }} Per Month</h5>
                                    <a href="{{ route('room.booking', ['room_no' => $room->room_no]) }}" class="btn btn-sm text-white bg-warning shadow-none w-100 mb-2">Request For Booking</a>
                                    <a href="{{ route('room.details', $room->id) }}" class="btn btn-sm btn-outline-dark shadow-none w-100">More Details</a>
                                </div>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>
        <!-- Add pagination links -->
<div class="pagination">
    {{ $rooms->links('vendor.pagination.default') }}
</div>
    </div>

</div>


<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.getElementById('category').addEventListener('change', function() {
        const categoryId = this.value;
        const roomNoSelect = document.getElementById('room_no');
        const blockNameInput = document.getElementById('block_name');
        const bedsSelect = document.getElementById('beds');

        // Clear existing room options, block name, and beds
        roomNoSelect.innerHTML = '<option value="">Select Room Number</option>';
        blockNameInput.value = ''; // Clear block name
        bedsSelect.innerHTML = '<option value="">Select Bed</option>'; // Clear bed options

        if (categoryId) {
            // Fetch room numbers related to the selected category via AJAX
            fetch(`/api/rooms/${categoryId}`)
                .then(response => response.json())
                .then(data => {
                    data.rooms.forEach(room => {
                        const option = document.createElement('option');
                        option.value = room.room_no;
                        option.textContent = room.room_no;
                        roomNoSelect.appendChild(option);
                    });

                    // Fetch additional information for selected room when a room is selected
                    roomNoSelect.addEventListener('change', function() {
                        const selectedRoomNo = this.value;

                        if (selectedRoomNo) {
                            // Fetch room details including block name and beds based on room number
                            fetch(`/api/room/details/${selectedRoomNo}`)
                                .then(response => response.json())
                                .then(roomDetails => {
                                    // Set block name
                                    blockNameInput.value = roomDetails.block_name;

                                    // Dynamically populate beds
                                    bedsSelect.innerHTML = '<option value="">Select Bed</option>'; // Reset beds options
                                    roomDetails.beds.forEach(bed => {
                                        const option = document.createElement('option');
                                        option.value = bed.id;
                                        option.textContent = bed.bed_no;
                                        bedsSelect.appendChild(option);
                                    });
                                });
                        }
                    });
                });
        }
    });
</script>



@endsection