<nav id="sidebar" class="">
    <div class="container-fluid " style="padding: 30px;">

        <!-- <hr style="border: 0; height: 1px; background-color: #939393; margin: 20px 0 65px 0;"> -->




        <ul class="nav flex-column">

            <!-- admin  -->


            <li class="nav-item" style="display: flex; justify-content: center; align-items: center;">

                <i class="bi bi-person" style="color: white; font-size: 80px;"></i>
            </li>

            <li class="nav-item" style="display: flex; justify-content: center; align-items: center;">

                <h5 class="btn-text" style="color: white;"> Welcome, {{ Auth::user()->name }}</h5>
            </li>

            <hr style="border: 0; height: 1px; background-color: #939393; margin: 20px 0 65px 0;">







            @if(Auth::user()->usertype == 'admin')

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('admin.courses.index') }}">

                    <i class="bi bi-person-circle"></i>


                    <span class="btn-text">Profile</span>
                </a>
            </li>



            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('admin.courses.index') }}">
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

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('users.index') }}">
                    <i class="fas fa-chalkboard-teacher btn-icon"></i>
                    <span class="btn-text">Pending Verification</span>
                </a>
            </li>

            @endif

            <!--EXPERT SIDE-->

            @if(Auth::user()->usertype == 'expert')

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('expert.profile.show') }}">

                    <i class="bi bi-person-circle"></i>


                    <span class="btn-text">Profile</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('expert.courses.index') }}">
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
            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('expert.pendingWords') }}">
                    <i class="fas fa-tachometer-alt btn-icon"></i>
                    <span class="btn-text">Word Approval</span>
                </a>
            </li>

            <!-- <li class="nav-item">
                        <a class="btn btn-nav" href="{{ url('/progress')}}">
                          <i class="bi bi-bar-chart"></i> 
                        <span class="btn-text">Word Approval</span>
                    </li> -->

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ url('/progress') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span class="btn-text">Ranking</span>
                </a>
            </li>



            <!-- 
                <li>
                    <a class="btn btn-nav" href="{{ route('user.wordSuggested') }}">
                        <i class="bi bi-book"></i>
                        <span class="btn-text">View Your Suggested Words</span>
                    </a>
                </li> -->

            @endif







            @if(Auth::user()->usertype == 'user')

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('user.profile.show', ['id' => Auth::user()->id]) }}">
                    <i class="bi bi-person-circle"></i>
                    <span class="btn-text">Profile</span>
                </a>
            </li>

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

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ url('/progress') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span class="btn-text">Ranking</span>
                </a>
            </li>

            <li>
                <a class="btn btn-nav" href="{{ route('user.wordSuggested') }}">
                    <i class="bi bi-book"></i>
                    <span class="btn-text">View Your Suggested Words</span>
                </a>
            </li>

            @endif






        </ul>
    </div>
</nav>