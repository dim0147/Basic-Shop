
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">




    <div class="container">

    
      <a class="navbar-brand" href="/product"><i class="fas fa-users"></i> Group Project</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="/product">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/cart/show">Your Cart 
            @if(isset($_SESSION['cart']) && isset($_SESSION['cart']['totalQty']) && !empty($_SESSION['cart']['totalQty']))
            <span class="badge">{{$_SESSION['cart']['totalQty']}}</span>
            @endif
            </a>
          </li>
          <li class="nav-item"> 
            <a class="nav-link" href="/product">Services</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Contact</a>
          </li>
          @if (!empty($_SESSION['username']))
          <li class="nav-item">
            <a class="nav-link" href="/user/profile">Profile</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/user/logout">Logout</a>
          </li>
          @else
          <li class="nav-item">
            <a class="nav-link" href="/user/login">Login</a>
          </li>
          @endif
        </ul>
      </div>
    </div>
</nav>

  <!--
  <nav class="navbar navbar-expand-sm bg-light">
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" href="#">Link 1</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link 2</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="#">Link 3</a>
    </li>
  </ul>
</nav>-->