<style>

.btn{
    margin-left: 10px;
}

</style>


<nav class="navbar navbar-light bg-light justify-content-center">
<a class="navbar-brand" href="/admin/dashboard">Dashboard</a>
    <ul class="nav">
    <li class="nav-item">
            <a href="/product"><button class="btn btn-outline-secondary" type="button"><i class="fa fa-home" aria-hidden="true"></i> Go to home</button></a>
        </li>
        <li class="nav-item">
            <a href="/admin/add-product"><button class="btn btn-outline-success" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add product</button></a>
        </li>
        <li class="nav-item">
            <a href="/admin/show-product"><button class="btn btn-outline-success" type="button"><i class="fas fa-edit"></i> Edit product</button></a>
        </li>
        <li class="nav-item">
            <a href="/admin/add-category"><button class="btn btn-outline-primary" type="button"><i class="fa fa-plus" aria-hidden="true"></i> Add Category</button></a>
        </li>
        <li class="nav-item">
            <a href="/admin/show-category"><button class="btn btn-outline-primary" type="button"><i class="fas fa-edit"></i> Edit Category</button></a>
        </li>

        @if(!empty($_SESSION['user']))
        <li class="nav-item">
            <a href="/user/logout"><button class="btn btn-outline-danger" type="button"><i class="fas fa-sign-out-alt"></i> Logout</button></a>
        </li>
        @endif
    </ul>
    </nav>

