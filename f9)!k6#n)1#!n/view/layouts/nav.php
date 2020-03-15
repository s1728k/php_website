<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">PHP Website</a>
    </div>
    <ul class="nav navbar-nav navbar-right">
      <?php if(!$_SESSION[$app_key]["id"]): ?>
      <li><a href="/login_form">Login</a></li>
      <li><a href="/register_form">Register</a></li>
      <?php else: ?>
      <li class="active"><a href="#">Home</a></li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="#"><i class="fa fa-user"></i> <?php echo $_SESSION[$app_key]["name"]; ?>
        <span class="caret"></span></a>
        <ul class="dropdown-menu">
          <li><a href="controller/logout.php" onclick="event.preventDefault();
                           document.getElementById('logout-form').submit();"><span class="fa fa-sign-out"></span> Logout</a></li>
          <form id="logout-form" action="controller/logout.php" method="POST" style="display: none;">
              <input type="hidden" name="_token" value="<?php echo $rand; ?>">
          </form>
        </ul>
      </li>
      <?php endif; ?>
    </ul>
  </div>
</nav>