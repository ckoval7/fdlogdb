<?php
if(!empty($_SESSION['priv'])) {
	$priv = $_SESSION['priv'];
} else {
	$priv = '';
}

echo '
      <div id="drawer" class="sidenav">
         <nav class="nav">
         <img class="logo" src="/img/100.jpg" alt="logo goes here"/>
            <ul class="nav_list">
               <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a><br>';

if (empty($priv)) {
      echo '
               <li onclick="self.location.href=\'/sign-in.php\';" class="nav_item"><a href="/sign-in.php">Sign In</a></li>
               <li onclick="self.location.href=\'/register.php\';" class="nav_item"><a href="/register.php">Register</a></li>
               <br>
';
}
if (!empty($priv)) {
      echo '
               <li onclick="self.location.href=\'/sign-out.php\';" class="nav_item"><a href="/sign-out.php">Sign Out</a></li>
               <br>
';
}

echo '
               <li onclick="self.location.href=\'/index.php\';" class="nav_item"><a href="/index.php">Home</a></li>
               <li onclick="self.location.href=\'/view-log.php\';" class="nav_item"><a href="/view-log.php">FD Log</a></li>
               <li onclick="self.location.href=\'/guestbook.php\';" class="nav_item"><a href="/guestbook.php">Guestbook</a></li>
               <br>

';

   if ($priv === "user") {
      echo '
               <li onclick="self.location.href=\'/php/enterlog.php\';" class="nav_item_users"><a href="/php/enterlog.php">My Log</a></li>
               <li onclick="self.location.href=\'/images.php\';" class="nav_item_users"><a href="/images.php">Images</a></li>
               <li onclick="self.location.href=\'/account.php\';" class="nav_item_users"><a href="/account.php">My Account</a></li>
               <li onclick="self.location.href=\'/inventory.php\';" class="nav_item"><a href="/inventory.php">My Inventory</a></li>
';
}
   if ($priv === "admin"){
      echo '
               <li onclick="self.location.href=\'/admin/setup.php\';" class="nav_item_users"><a href="/admin/setup.php">DB Config</a></li>
               <li onclick="self.location.href=\'/admin/post_fd.php\';" class="nav_item_users"><a href="/admin/post_fd.php">FD Config</a></li>
               <li onclick="self.location.href=\'/admin/edit_users.php\';" class="nav_item_users"><a href="/admin/edit_users.php">FD User Accounts</a></li>
';
}
echo '
               <br>
               <li onclick="window.open(\'/files/2016 Rules.pdf\',\'_blank\');" class="nav_item"><a target="_blank" href="/files/2016 Rules.pdf">FD 2016 Rules</a></li>
            </ul>
         </nav>
      </div>';
?>
