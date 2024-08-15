<nav id="sidebar" class="">
    <div class="container-fluid " style="padding: 30px;">

        <hr style="border: 0; height: 1px; background-color: #939393; margin: 20px 0 65px 0;">
        <ul class="nav flex-column">
            <li class="nav-item">


                <!-- <a href="{{ route('courses.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Courses</a>

                <a class="btn btn-nav" href="{{ route('courses.index') }}">
                    <i class="fas fa-book btn-icon"></i>
                    <span class="btn-text">Courses</span>
                </a> -->

                <a class="btn btn-nav" href="{{ route('courses.index') }}">
                    <i class="fas fa-book btn-icon"></i>
                    <span class="btn-text">Courses</span>

                </a>
                <!-- <a class="btn btn-nav" href="{{ route('lessons.index') }}">
                <i class="fas fa-book btn-icon"></i>
                <span class="btn-text">Lessons</span>
                  </a>  -->
            </li>


            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('lessons.index') }}">
                    <i class="fas fa-chalkboard-teacher btn-icon"></i>
                    <span class="btn-text">Lessons</span>
                </a>
            </li>




            @if(Auth::user()->usertype == 'admin')

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('users.index') }}">
                    <i class="fas fa-chalkboard-teacher btn-icon"></i>
                    <span class="btn-text">View Users</span>
                </a>
            </li>

            @endif



            <li class="nav-item">
                <a class="btn btn-nav" href="{{ url('/progress') }}">
                    <i class="fas fa-tachometer-alt btn-icon"></i>
                    <span class="btn-text">Progress</span>
                </a>
            </li>

        </ul>
    </div>
</nav>