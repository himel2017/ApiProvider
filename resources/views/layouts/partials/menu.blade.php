<nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
	<div class="text-left navbar-brand-wrapper d-flex align-items-center justify-content-between">
		<a class="navbar-brand brand-logo" href="{{ route('home') }}"><img src="https://www.akij.net/wp-content/uploads/2019/02/logo-60px.svg" alt="logo"/></a>
		<!-- <a class="navbar-brand brand-logo-mini" href="index.html"><img src="http://www.urbanui.com/hiliteui/template/images/logo-mini.svg" alt="logo"/></a>  -->
		<button class="navbar-toggler align-self-center" type="button" data-toggle="minimize">
		<span class="mdi mdi-menu"></span>
		</button>
	</div>
	<div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
          <ul class="navbar-nav">
          </ul>
          <ul class="navbar-nav navbar-nav-right">
            <!-- <li class="nav-item nav-user-icon">
              <a class="nav-link" href="#">
              <img src="https://www.akij.net/wp-content/uploads/2019/02/logo-60px.svg" alt="profile"/>
              </a>
            </li> -->

            <!-- Authentication Links -->
            @guest
              <li class="nav-item">
                <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
              </li>
              @if (Route::has('register'))
                <li class="nav-item">
                  <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </li>
              @endif
            @else
              <li class="nav-item nav-user-icon dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                <img src="https://www.akij.net/wp-content/uploads/2019/02/logo-60px.svg" alt="profile"/>
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                  <a class="dropdown-item" href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                    {{ __('Logout') }}
                  </a>

                  <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                  </form>
                </div>
              </li>
            @endguest
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
          </button>
        </div>
	</nav>