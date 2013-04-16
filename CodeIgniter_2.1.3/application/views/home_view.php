
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <body>
   <h2>Welcome <?php echo $username; ?>!</h2><br>
   <br>
   <?php if ($connected == false) echo "<a href='driver/foursquareAuth' target='_blank'>Connect with Foursquare</a><br>"; ?>
   <br>
   <table border="1" rules="all">
    <tr><th>Venue</th><th>Location</th><th>Created At</th></tr>
    <?php if ($checkins != null):
            foreach ($checkins as $c):
                echo "<tr><td>".$c['venue']."</td><td>".$c['location']."</td><td>".$c['createdAt']."</td></tr>";
            endforeach;
            endif; ?>
   </table>
   
   <br><a href="home/logout">Logout</a>
 </body>
</html>


