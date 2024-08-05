<x-app-layout>
    <x-slot name="dashboard">




        <div class="main-container">
            <!-- Header section -->
            <div class="container-fluid mb-1">
                <h1>Courses</h1>
            </div>

            <!-- Card container -->
            <div class="container-fluid card-container row">
                <!-- Card item -->
                <div class="card">
                    <div class="top">
                        <img src="{{ asset('images/cebuano.png') }}" alt="Card Image" class="card-img">
                        <div class="row align-items-center mt-3 mb-3">
                            <div class="col-6 d-flex align-items-center">
                                <h3 class="card-title mb-0">Cebuano</h3>
                            </div>
                            <div class="col-6 d-flex justify-content-center align-items-center">
                                <a href="{{ url('/course/courseName') }}" class="btn btn-view-courses btn-block">View Course</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-content">
                        <h5>Regions:</h5>
                        <p class="card-description">
                            Central Visayas, eastern Negros Island, Cebu, Bohol, Siquijor, parts of Leyte and Southern Leyte, Mindanao, and a few parts of Masbate.
                        </p>
                        <h5>Cities:</h5>
                        <p class="card-description">
                            Cebu City, Davao City, Cagayan de Oro, and Dumaguete.
                        </p>
                        <h5>Fun Facts:</h5>
                        <p class="card-description">
                            Cebuano is sometimes simply called "Bisaya," though Bisaya can also refer to other Visayan languages.
                        </p>
                    </div>
                </div>


            </div>
        </div>






    </x-slot>







</x-app-layout>