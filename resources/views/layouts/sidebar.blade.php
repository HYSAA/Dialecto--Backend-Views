<nav id="sidebar" class="">
    <div class="container-fluid " style="padding: 30px;">

        <!-- <hr style="border: 0; height: 1px; background-color: #939393; margin: 20px 0 65px 0;"> -->




        <ul class="nav flex-column">

            <!-- admin  -->


            <li class="nav-item" style="display: flex; justify-content: center; align-items: center;">

                <i class="bi bi-person" style="color: white; font-size: 50px;"></i>
            </li>
            <li class="nav-item" style="display: flex; justify-content: center; align-items: center;">
                <h5 class="btn-text" style="color: white;">Welcome, {{ session('user.name') ?? Auth::user()->name }}</h5>
            </li>
            <hr style="border: 0; height: 1px; background-color: #939393; margin: 20px 0 35px 0;">

            @if(Auth::user()->usertype == 'admin')





            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('admin.courses.index') }}">
                    <i class="fas fa-book btn-icon"></i>
                    <span class="btn-text">Courses and Lessons</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('users.index') }}">
                    <i class="fas fa-chalkboard-teacher btn-icon"></i>
                    <span class="btn-text">View Users</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="btn btn-nav position-relative" href="{{ route('admin.showPendingExpert') }}">
                    <i class="bi bi-person-fill-check"></i>
                    <span class="btn-text">Pending Verification</span>
                    @if(session('unverifiedUsers') && count(session('unverifiedUsers')) > 0)
                    <span class="badge badge-light position-absolute top-0 start-100 translate-middle">{{ count(session('unverifiedUsers')) }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('admin.showWordBank') }}">
                    <i class="bi bi-bank2"></i>
                    <span class="btn-text">Word Bank</span>
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
                <a class="btn btn-nav" href="{{ route('expert.progress', ['id' => Auth::user()->firebase_id]) }}">
                    <i class="fas fa-tachometer-alt btn-icon"></i>
                    <span class="btn-text">Progress</span>
                </a>
            </li>


            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('expert.leaderboard') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span class="btn-text">Ranking</span>
                </a>

            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('expert.dictionary') }}">
                    <i class="bi bi-book-half"></i>
                    <span class="btn-text">Dictionary of Words</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="btn btn-nav position-relative" href="{{ route('expert.pendingWords') }}">
                    <i class="bi bi-book"></i>
                    <span class="btn-text">Word Bank</span>
                    @if(session('pendingWordsCount') && session('pendingWordsCount') > 0)
                    <span class="badge badge-light position-absolute top-0 start-100 translate-middle">{{ session('pendingWordsCount') }}</span>
                    @endif
                </a>
            </li>

            @endif







            @if(Auth::user()->usertype == 'user')



            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('user.profile.show') }}">
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
                <a class="btn btn-nav" href="{{ route('user.progress', ['id' => Auth::user()->firebase_id]) }}">
                    <i class="fas fa-tachometer-alt btn-icon"></i>
                    <span class="btn-text">Progress</span>
                </a>
            </li>











            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('user.leaderboard') }}">
                    <i class="bi bi-bar-chart"></i>
                    <span class="btn-text">Ranking</span>
                </a>


            <li class="nav-item">
                <a class="btn btn-nav" href="{{ route('user.dictionary') }}">
                    <i class="bi bi-book-half"></i>
                    <span class="btn-text">Dictionary of Words</span>
                </a>
            </li>


            <li>
                <a class="btn btn-nav" href="{{ route('user.wordSuggested') }}">
                    <i class="bi bi-book"></i>
                    <span class="btn-text">My Suggested Words</span>
                </a>
            </li>

            @endif




            <style>
                .position-relative {
                    position: relative;
                }

                .position-absolute {
                    position: absolute;
                }

                .top-0 {
                    top: 0;
                }

                .start-100 {
                    left: 100%;
                }

                .translate-middle {
                    transform: translate(-50%, -50%);
                }

                .badge {
                    padding: 0.6em 0.9em;
                    /* Increase padding */
                    border-radius: 50%;
                    background-color: red;
                    color: white;
                    font-size: 1.1em;
                    /* Increase font size */
                }

                .badge-light {
                    background-color: red;
                    /* Ensure the badge is red */
                }
            </style>

        </ul>
    </div>
</nav>