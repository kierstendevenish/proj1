<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <body>
   <?php echo form_open('findUser'); ?>
        <label for="findUsername">Find a User:</label>
        <input type="text" size="20" id="findUsername" name="findUsername"/>
        <br/>
   </form><br/><br/>
    <h2>Login to your account:</h2><br>
   <?php echo validation_errors(); ?>
   <?php echo form_open('verifylogin'); ?>
     <label for="username">Username:</label>
     <input type="text" size="20" id="username" name="username"/>
     <br/>
     <label for="password">Password:</label>
     <input type="password" size="20" id="passowrd" name="password"/>
     <br/>
     <input type="submit" value="Login"/>
    </form><br/><br/>
            <a href="register">Register</a>
 </body>
</html>