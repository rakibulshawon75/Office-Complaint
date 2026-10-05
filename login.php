
<?php

session_start();
include "db.php";

$message = "";
$message_type = "";


/* Registration success message */

if (isset($_GET['registered']) && $_GET['registered'] == "success") {

    $message = "Account created successfully. Please login to continue.";
    $message_type = "success";
}


/* Login */

if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $password = mysqli_real_escape_string(
        $conn,
        $_POST['password']
    );


    $sql = "SELECT * FROM users
            WHERE email='$email'
            AND password='$password'";


    $result = mysqli_query(
        $conn,
        $sql
    );


    if (mysqli_num_rows($result) == 1) {

        $user = mysqli_fetch_assoc($result);

        $_SESSION['user_id'] = $user['id'];

        $_SESSION['user_name'] = $user['name'];

        header("Location: dashboard.php");

        exit();

    } else {

        $message = "Invalid email or password.";

        $message_type = "error";

    }
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Employee Login | Office Complaint System
</title>


<style>

/* ================= RESET ================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* ================= BODY ================= */

body {

    font-family:
        Arial,
        Helvetica,
        sans-serif;

    min-height: 100vh;

    background:
        linear-gradient(
            135deg,
            #0f172a,
            #1e3a8a,
            #0f766e
        );

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 25px;

}


/* ================= CONTAINER ================= */

.container {

    width: 100%;

    max-width: 430px;

}


/* ================= BRAND ================= */

.brand {

    text-align: center;

    margin-bottom: 20px;

}


.logo {

    width: 54px;

    height: 54px;

    margin: 0 auto 10px;

    border-radius: 14px;

    background:
        linear-gradient(
            135deg,
            #1e3a8a,
            #06b6d4
        );

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 16px;

    font-weight: 800;

    box-shadow:
        0 8px 22px
        rgba(6, 182, 212, 0.25);

}


.brand h1 {

    font-size: 22px;

    color: white;

    margin-bottom: 5px;

}


.brand p {

    font-size: 12px;

    color: #cbd5e1;

}


/* ================= CARD ================= */

.card {

    background: white;

    border-radius: 17px;

    padding: 32px;

    border-top:
        4px solid #06b6d4;

    box-shadow:
        0 20px 50px
        rgba(0, 0, 0, 0.22);

}


/* ================= TITLE ================= */

.card-title {

    margin-bottom: 22px;

}


.card-title h2 {

    font-size: 20px;

    color: #172033;

    margin-bottom: 5px;

}


.card-title p {

    font-size: 11px;

    color: #7b8494;

}


/* ================= MESSAGE ================= */

.message {

    padding: 12px 14px;

    border-radius: 8px;

    margin-bottom: 18px;

    font-size: 12px;

    font-weight: 600;

    line-height: 1.5;

}


.message.success {

    color: #047857;

    background: #ecfdf5;

    border:
        1px solid #a7f3d0;

}


.message.error {

    color: #b42318;

    background: #fef3f2;

    border:
        1px solid #fecdca;

}


/* ================= FORM ================= */

.form-group {

    margin-bottom: 17px;

}


label {

    display: block;

    margin-bottom: 7px;

    font-size: 12px;

    font-weight: 700;

    color: #344054;

}


input {

    width: 100%;

    padding: 13px 14px;

    border:
        1px solid #d5dce3;

    border-radius: 8px;

    outline: none;

    font-size: 13px;

    color: #172033;

    background: white;

    transition: 0.2s;

}


input::placeholder {

    color: #98a2b3;

}


input:focus {

    border-color: #06b6d4;

    box-shadow:
        0 0 0 3px
        rgba(6, 182, 212, 0.10);

}


/* ================= LOGIN BUTTON ================= */

.login-btn {

    width: 100%;

    border: none;

    padding: 13px;

    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            #1e3a8a,
            #06b6d4
        );

    color: white;

    font-size: 13px;

    font-weight: 700;

    cursor: pointer;

    margin-top: 5px;

    box-shadow:
        0 7px 18px
        rgba(6, 182, 212, 0.20);

    transition: 0.2s;

}


.login-btn:hover {

    transform: translateY(-1px);

    box-shadow:
        0 9px 22px
        rgba(6, 182, 212, 0.30);

}


/* ================= LINKS ================= */

.register-link {

    text-align: center;

    margin-top: 20px;

    font-size: 12px;

    color: #667085;

}


.register-link a {

    color: #1e3a8a;

    font-weight: 700;

    text-decoration: none;

}


.register-link a:hover {

    text-decoration: underline;

}


.admin-link {

    text-align: center;

    margin-top: 14px;

}


.admin-link a {

    font-size: 11px;

    color: #667085;

    text-decoration: none;

}


.admin-link a:hover {

    color: #1e3a8a;

}


/* ================= FOOTER ================= */

.footer {

    text-align: center;

    margin-top: 18px;

    font-size: 10px;

    color: #cbd5e1;

}


/* ================= RESPONSIVE ================= */

@media (max-width: 500px) {

    body {

        padding: 15px;

    }

    .card {

        padding: 24px 20px;

    }

    .brand h1 {

        font-size: 20px;

    }

}

</style>

</head>


<body>


<div class="container">


    <!-- BRAND -->

    <div class="brand">

        <div class="logo">
            OCS
        </div>

        <h1>
            Office Complaint System
        </h1>

        <p>
            Employee Login Portal
        </p>

    </div>


    <!-- LOGIN CARD -->

    <div class="card">


        <div class="card-title">

            <h2>
                Welcome Back
            </h2>

            <p>
                Login to manage and track your complaints.
            </p>

        </div>


        <!-- MESSAGE -->

        <?php

        if ($message != "") {

        ?>

            <div
                class="message <?php echo $message_type; ?>"
            >

                <?php

                echo htmlspecialchars(
                    $message
                );

                ?>

            </div>

        <?php

        }


        ?>


        <!-- LOGIN FORM -->

        <form
            method="POST"
        >


            <!-- EMAIL -->

            <div class="form-group">

                <label>
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    placeholder="Enter your email address"
                    required
                >

            </div>


            <!-- PASSWORD -->

            <div class="form-group">

                <label>
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="login"
                class="login-btn"
            >

                Login to Employee Portal

            </button>


        </form>


        <!-- REGISTER -->

        <div class="register-link">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </div>


        <!-- ADMIN -->

        <div class="admin-link">

            <a href="admin_login.php">
                Admin Login
            </a>

        </div>


    </div>


    <!-- FOOTER -->

    <div class="footer">

        © 2026 Office Complaint System

    </div>


</div>


</body>

</html>

