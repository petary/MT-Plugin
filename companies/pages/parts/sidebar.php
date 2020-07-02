<div class="col-md-3 mt-5 mb-5" >
    <ul class="nav flex-column nav-pills sidebar-2"  >
  <li class="nav-item">
    <a class="nav-link text-bold <?php echo !isset($_GET['tickets']) ? 'active' : ''  ?>" href="<?php echo get_permalink( $optd->ppage ); ?>">Raise a ticket</a>
  </li>
  <li class="nav-item">
    <a class="nav-link text-bold <?php echo isset($_GET['tickets']) ? 'active' : ''  ?>" href="<?php echo get_permalink( $optd->ppage ); ?>?tickets">Tickets status</a>
  </li>
</ul>
</div>