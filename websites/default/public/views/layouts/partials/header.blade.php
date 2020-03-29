<style>
/* Styles for wrapping the search box */

/* Bootstrap 4 text input with search icon */

.search-ip .form-control {
    padding-left: 2.375rem;
}

.search-ip .feedback {
    position: absolute;
    z-index: 2;
    display: block;
    width: 2.375rem;
    height: 2.375rem;
    line-height: 2.375rem;
    text-align: center;
    pointer-events: none;
    color: #aaa;
}
</style>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">




    <div class="container">

   
      <a class="navbar-brand" href="/product"><img src="@asset('views/public/image/favicon.png')" style="width:50px;height:40px" alt="">  Shopping Website</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">

        <li class="nav-item">
        <div class="dropdown show">
  <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Admin
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    <a class="dropdown-item" href="/admin/dashboard">Dashboard</a>
  </div>
</div>
        </li>
        
          
          <li class="nav-item">
            <a class="nav-link" href="/product">Home
              <span class="sr-only">(current)</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/cart/show">Cart 
            @if(isset($_SESSION['cart']) && isset($_SESSION['cart']['totalQty']) && !empty($_SESSION['cart']['totalQty']))
            <span class="badge">{{$_SESSION['cart']['totalQty']}}</span>
            @endif
            </a>
          </li>

        <li class="nav-item">
        <div class="dropdown show">
  <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    Category
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
   @if(!empty($categoryHeader))
   @foreach($categoryHeader as $category)
    <a class="dropdown-item" href="/product/search/category?s={{$category['name']}}">{{$category['name']}}</a>
  @endforeach
  @else
    <p>Don't have any category</p>
  @endif
  </div>
</div>
        </li>


        <li class="nav-item" style="margin-right: 20px">
        @if (!empty($_SESSION['user']))

        <div class="dropdown show">
  <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
  Welcome back, {{$_SESSION['username']}}
  </a>

  <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
  <a class="dropdown-item" href="/user/profile">Profile</a>
              <a class="dropdown-item" href="/user/logout">Logout</a>
  </div>
</div>
        </li>
          @else
          <li class="nav-item">
            <a class="nav-link" href="/user/login">Login</a>
          </li>
          @endif

          
          
          <li class="nav-item">
              <div class="form-group search-ip">
          <span class="fa fa-search feedback"></span>
          <input type="text" class="form-control" placeholder="Search" id="searchBar">
        </div>
          </li>
          
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