```php
<?php
session_start();
include "db.php";

$message = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string(
        $conn,
        $_POST['username']
    );

    $password = mysqli_real_escape_string(
        $conn,
        $_POST['password']
    );

    $sql = "SELECT * FROM admins
            WHERE username='$username'
            AND password='$password'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) == 1) {

        $_SESSION['admin'] = $username;

        header("Location: admin.php");
        exit();

    } else {

        $message = "Invalid Admin Username or Password!";

    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Login | Office Complaint System</title>

    <link rel="stylesheet" href="style.css">

    <style>

        * {
            box-sizing: border-box;
        }

        body {

            min-height: 100vh;

            margin: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            font-family: Arial, sans-serif;

            background:
                linear-gradient(
                    135deg,
                    #0F172A,
                    #1E3A8A,
                    #2563EB
                );

            padding: 20px;
        }


        .admin-box {

            width: 410px;

            background: #ffffff;

            padding: 40px;

            border-radius: 20px;

            box-shadow:
                0 20px 50px rgba(15, 23, 42, 0.30);

            position: relative;

            overflow: hidden;
        }


        .admin-box::before {

            content: "";

            position: absolute;

            top: 0;
            left: 0;

            width: 100%;
            height: 5px;

            background:
                linear-gradient(
                    90deg,
                    #1D4ED8,
                    #3B82F6,
                    #06B6D4
                );
        }


        .admin-title {

            text-align: center;

            margin-bottom: 28px;
        }


        .admin-icon {

            width: 68px;
            height: 68px;

            margin: 0 auto 16px;

            border-radius: 16px;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                linear-gradient(
                    135deg,
                    #1E3A8A,
                    #2563EB
                );

            color: white;

            font-size: 27px;

            box-shadow:
                0 8px 20px rgba(37, 99, 235, 0.25);
        }


        .admin-title h1 {

            margin: 0 0 8px;

            color: #0F172A;

            font-size: 26px;

            font-weight: 700;
        }


        .admin-title p {

            margin: 0;

            color: #64748B;

            font-size: 14px;
        }


        .message {

            background: #FEF2F2;

            color: #B91C1C;

            border: 1px solid #FECACA;

            padding: 12px 14px;

            border-radius: 9px;

            margin-bottom: 20px;

            text-align: center;

            font-size: 14px;
        }


        .admin-box form {

            box-shadow: none;

            padding: 0;

            max-width: none;

            background: transparent;
        }


        .admin-box label {

            display: block;

            margin-bottom: 7px;

            color: #334155;

            font-size: 14px;

            font-weight: 600;
        }


        .admin-box input {

            width: 100%;

            padding: 13px 14px;

            margin-bottom: 18px;

            border: 1px solid #CBD5E1;

            border-radius: 9px;

            outline: none;

            font-size: 14px;

            background: #F8FAFC;

            transition: 0.2s;
        }


        .admin-box input:focus {

            border-color: #2563EB;

            background: #ffffff;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, 0.10);
        }


        .admin-box button {

            width: 100%;

            border: none;

            border-radius: 9px;

            padding: 13px;

            margin-top: 4px;

            background:
                linear-gradient(
                    135deg,
                    #1E3A8A,
                    #2563EB
                );

            color: white;

            font-size: 15px;

            font-weight: 600;

            cursor: pointer;

            transition: 0.2s;

            box-shadow:
                0 7px 18px rgba(37, 99, 235, 0.22);
        }


        .admin-box button:hover {

            transform: translateY(-1px);

            box-shadow:
                0 10px 22px rgba(37, 99, 235, 0.28);
        }


        .bottom-link {

            text-align: center;

            margin-top: 24px;

            padding-top: 18px;

            border-top: 1px solid #E2E8F0;
        }


        .bottom-link a {

            color: #2563EB;

            text-decoration: none;

            font-size: 14px;

            font-weight: 600;
        }


        .bottom-link a:hover {

            text-decoration: underline;
        }


        .security-note {

            text-align: center;

            margin-top: 18px;

            color: #94A3B8;

            font-size: 12px;
        }


        @media (max-width: 480px) {

            .admin-box {

                width: 100%;

                padding: 30px 24px;
            }
        }

    </style>

</head>


<body>


<div class="admin-box">


    <div class="admin-title">

        <div class="admin-icon">
            ⚙
        </div>

        <h1>Admin Portal</h1>

        <p>
            Secure access to complaint management
        </p>

    </div>


    <?php if ($message != "") { ?>

        <div class="message">

            <?php echo htmlspecialchars($message); ?>

        </div>

    <?php } ?>


    <form method="POST">


        <label>
            Admin Username
        </label>

        <input
            type="text"
            name="username"
            placeholder="Enter admin username"
            autocomplete="username"
            required
        >


        <label>
            Password
        </label>

        <input
            type="password"
            name="password"
            placeholder="Enter admin password"
            autocomplete="current-password"
            required
        >


        <button type="submit" name="login">
            Sign in to Admin Panel
        </button>


    </form>


    <div class="bottom-link">

        <a href="login.php">
            ← Back to Employee Login
        </a>

    </div>


    <div class="security-note">

        Authorized administrators only

    </div>


</div>


</body>

</html>
```
