<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">WebSiteName</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
      <li class="active"><a href="#">Home</a></li>
      <li><a href="#">Page 1</a></li>
      <li><a href="#">Page 2</a></li>
      <?php if(!$_SESSION["name"]): ?>
      <li><a href="/login.php">Login</a></li>
      <li><a href="/register.php">Register</a></li>
      <?php else: ?>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user"></i> <?php echo $_SESSION["name"]; ?>
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="controller/logout.php" onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();"><span class="fa fa-sign-out"></span> Logout</a></li>
          <form id="logout-form" action="controller/logout.php" method="POST" style="display: none;">
              <input type="hidden" name="_token" value="{{csrf_token()}}">
          </form>
        </ul>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>