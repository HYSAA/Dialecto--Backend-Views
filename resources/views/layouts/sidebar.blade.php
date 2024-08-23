<nav id="sidebar" class="">
    <div class="container-fluid " style="padding: 30px;">

        <hr style="border: 0; height: 1px; background-color: #939393; margin: 20px 0 65px 0;">
        <ul class="nav flex-column">

            <!-- admin  -->

            @if(Auth::user()->usertype == 'admin')
            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('courses.index') }}">
                    <i class="fas fa-book btn-icon"></i>
                    <span class="btn-text">Courses</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('lessons.index') }}">
                    <i class="fas fa-chalkboard-teacher btn-icon"></i>
                    <span class="btn-text">Lessons</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('users.index') }}">
                    <i class="fas fa-chalkboard-teacher btn-icon"></i>
                    <span class="btn-text">View Users</span>
                </a>
            </li>

            @endif



            @if(Auth::user()->usertype == 'user')

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('user.dashboard') }}">
                    <i class="fas fa-book btn-icon"></i>
                    <span class="btn-text">Courses</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="btn btn-nav" href="{{ url('/progress') }}">
                    <i class="fas fa-tachometer-alt btn-icon"></i>
                    <span class="btn-text">Progress</span>
                </a>
            </li>

            @endif





        </ul>
    </div>
</nav>