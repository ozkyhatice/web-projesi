
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SignIn&SignUp</title>
    <link rel="stylesheet" type="text/css" href="../styles/loginstyles.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poetsen+One&display=swap" rel="stylesheet">
    
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
  
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post" class="sign-in-form">
            <h2 class="title">Sign In</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="username" placeholder="Username" />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="password" placeholder="Password" />
            </div>
            <input type="submit" name="login" value="Login" class="btn solid" id="LoginButton"/>

            <p class="social-text">Or Sign in with social platforms</p>
            <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
            </div>
          </form>

          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="sign-up-form">
            <h2 class="title">Sign Up</h2>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="new_username" placeholder="Username" />
            </div>
            <div class="input-field">
              <i class="fas fa-envelope"></i>
              <input type="email" name="email" placeholder="Email" />
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="new_password" placeholder="Password" />
            </div>
            <input type="submit" name="register" value="Sign Up" class="btn solid" />

            <p class="social-text">Or Sign up with social platforms</p>
            <div class="social-media">
              <a href="#" class="social-icon">
                <i class="fab fa-facebook-f"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-twitter"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-instagram"></i>
              </a>
              <a href="#" class="social-icon">
                <i class="fab fa-google"></i>
              </a>
            </div>
          </form>
        </div>
      </div>
      <div class="panels-container">

        <div class="panel left-panel">
            <div class="content">
                <h3>Create an account</h3><br>
                
                <button class="btn transparent" id="sign-up-btn">Sign Up</button>
            </div>
            <img src="../images/cooking (1).png" class="image" alt="">
        </div>

        <div class="panel right-panel">
            <div class="content">
                <h3>Have an account?</h3><br>
                <button class="btn transparent" id="sign-in-btn">Sign In</button>
            </div>
            <img src="../images/frying-pan.png" class="image" alt="">
        </div>
      </div>
    </div>
    <!-- Add this section after the forms -->
    <?php
session_start();
include ("../baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login"])) { // Giriş formu gönderildiyse
        $username = $_POST["username"];
        $password = $_POST["password"];

        if (empty($username) || empty($password)) {
            echo "<script>
                    alert('Username or password empty!'); window.location.href='./login.php';
                  </script>";
            exit;
        } else {
            $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0) {
                $_SESSION['oturum'] = true;
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                print 'Hoş geldiniz ' . $username;
                if ($username == 'admin' && $password == '12345') {
                    header('Location: ./add_recipe.php');
                    exit;
                } else {
                    header('Location: ../index2.php');
                    exit;
                }
            } else {
                echo "<script>
                        alert('HATALI KULLANICI BİLGİSİ!');
                      </script>";
            }
            mysqli_close($conn);
        }
    } 
    elseif (isset($_POST["register"])) { // Kayıt formu gönderildiyse
      $new_username = $_POST["new_username"];
      $email = $_POST["email"];
      $new_password = $_POST["new_password"];

      // Boş input kontrolü
      if (empty($new_username) || empty($email) || empty($new_password)) {
          echo "<script>
                  alert('Please fill all fields!'); window.location.href='./login.php';
                </script>";
          exit;
      }
      if (strlen($new_username) < 5) {
        $_SESSION['register_error'] = "Kullanıcı adı en az 5 karakter olmalıdır.";
        header("Location: ./login.php");
        exit;
    }

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['register_error'] = "Geçersiz e-posta adresi formatı.";
        header("Location: ./login.php");
        exit;
    }

    // E-posta adresinin alan adını alma
    $domain = explode('@', $email)[1];

    // Alan adının DNS kayıtlarını kontrol etme
    if (!checkdnsrr($domain, "MX")) {
        $_SESSION['register_error'] = "E-posta adresi alanı doğrulanamadı.";
        header("Location: ./login.php");
        exit;
    }

      // Kullanıcı adının benzersiz olup olmadığını kontrol etme
      $check_username_query = "SELECT * FROM users WHERE username='$new_username'";
      $check_username_result = mysqli_query($conn, $check_username_query);
      if (mysqli_num_rows($check_username_result) > 0) {
          echo "<script>
                  alert('This username is already taken! Please choose a different one.'); window.location.href='./login.php';
                </script>";
          exit;
      }
      $check_email_query = "SELECT * FROM users WHERE email='$email'";
        $check_email_result = mysqli_query($conn, $check_email_query);
        if (mysqli_num_rows($check_email_result) > 0) {
            echo "<script>
                    alert('This email is already registered! Please use a different one.'); window.location.href='./login.php';
                  </script>";
            exit;
        }

      
      // Kullanıcıyı veritabanına ekleme
      $sql = "INSERT INTO users (username, email, password) VALUES ('$new_username', '$email', '$new_password')";
      if (mysqli_query($conn, $sql)) {
          echo "<script>
                  alert('User registered successfully!'); window.location.href='./login.php';
                </script>";
          exit;
      } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }

      mysqli_close($conn);
  }
}

?>
    <script src="../js/login.js"></script>
  </body>
</html>
