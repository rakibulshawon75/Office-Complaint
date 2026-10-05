
<?php

session_start();
include "db.php";

$message = "";
$message_type = "";

if (isset($_POST['register'])) {

    $name = mysqli_real_escape_string(
        $conn,
        $_POST['name']
    );

    $email = mysqli_real_escape_string(
        $conn,
        $_POST['email']
    );

    $password = mysqli_real_escape_string(
        $conn,
        $_POST['password']
    );

    $department = mysqli_real_escape_string(
        $conn,
        $_POST['department']
    );


    /* Check existing email */

    $check_sql = "SELECT id
                  FROM users
                  WHERE email='$email'";

    $check_result = mysqli_query(
        $conn,
        $check_sql
    );


    if (mysqli_num_rows($check_result) > 0) {

        $message = "An account with this email already exists.";
        $message_type = "error";

    } else {

        /* Create account */

        $sql = "INSERT INTO users
                (name, email, password, department)
                VALUES
                ('$name', '$email', '$password', '$department')";


        if (mysqli_query($conn, $sql)) {

            /*
             * Registration successful.
             * Send user directly to Login page.
             */

            header(
                "Location: login.php?registered=success"
            );

            exit();

        } else {

            $message = "Unable to create account. Please try again.";
            $message_type = "error";

        }
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
    Create Account | Office Complaint System
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
            #ecfeff,
            #f0fdf4
        );

    display: flex;

    align-items: center;

    justify-content: center;

    padding: 25px;

}


/* ================= MAIN CONTAINER ================= */

.container {

    width: 100%;

    max-width: 460px;

}


/* ================= BRAND ================= */

.brand {

    text-align: center;

    margin-bottom: 20px;

}


.logo {

    width: 52px;

    height: 52px;

    margin: 0 auto 10px;

    border-radius: 14px;

    background:
        linear-gradient(
            135deg,
            #0f766e,
            #06b6d4
        );

    color: white;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 16px;

    font-weight: 800;

    box-shadow:
        0 8px 20px
        rgba(6, 182, 212, 0.22);

}


.brand h1 {

    font-size: 22px;

    color: #172033;

    margin-bottom: 5px;

}


.brand p {

    font-size: 12px;

    color: #667085;

}


/* ================= CARD ================= */

.card {

    background: white;

    border-radius: 17px;

    padding: 32px;

    border: 1px solid #dfe7ec;

    box-shadow:
        0 15px 40px
        rgba(15, 118, 110, 0.10);

}


/* ================= CARD TITLE ================= */

.card-title {

    margin-bottom: 24px;

}


.card-title h2 {

    font-size: 19px;

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

}


.message.error {

    color: #b42318;

    background: #fef3f2;

    border: 1px solid #fecdca;

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

    padding: 12px 13px;

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


/* ================= BUTTON ================= */

.register-btn {

    width: 100%;

    border: none;

    padding: 13px;

    border-radius: 8px;

    background:
        linear-gradient(
            135deg,
            #0f766e,
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


.register-btn:hover {

    transform: translateY(-1px);

    box-shadow:
        0 9px 22px
        rgba(6, 182, 212, 0.28);

}


/* ================= LINKS ================= */

.login-link {

    text-align: center;

    margin-top: 20px;

    font-size: 12px;

    color: #667085;

}


.login-link a {

    color: #0f766e;

    font-weight: 700;

    text-decoration: none;

}


.login-link a:hover {

    text-decoration: underline;

}


.home-link {

    text-align: center;

    margin-top: 12px;

}


.home-link a {

    font-size: 11px;

    color: #667085;

    text-decoration: none;

}


.home-link a:hover {

    color: #0f766e;

}


/* ================= FOOTER ================= */

.footer {

    text-align: center;

    margin-top: 18px;

    font-size: 10px;

    color: #98a2b3;

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
            Employee Registration Portal
        </p>

    </div>


    <!-- CARD -->

    <div class="card">


        <div class="card-title">

            <h2>
                Create Your Account
            </h2>

            <p>
                Register to submit and track your complaints.
            </p>

        </div>


        <!-- ERROR MESSAGE -->

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


        <!-- REGISTER FORM -->

        <form
            method="POST"
        >


            <!-- NAME -->

            <div class="form-group">

                <label>
                    Full Name
                </label>

                <input
                    type="text"
                    name="name"
                    placeholder="Enter your full name"
                    required
                >

            </div>


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
                    placeholder="Create a password"
                    required
                >

            </div>


            <!-- DEPARTMENT -->

            <div class="form-group">

                <label>
                    Department
                </label>

                <input
                    type="text"
                    name="department"
                    placeholder="e.g. IT, HR, Finance"
                    required
                >

            </div>


            <!-- BUTTON -->

            <button
                type="submit"
                name="register"
                class="register-btn"
            >

                Create Account

            </button>


        </form>


        <!-- LOGIN -->

        <div class="login-link">

            Already have an account?

            <a href="login.php">
                Login here
            </a>

        </div>


        <!-- HOME -->

        <div class="home-link">

            <a href="index.php">
                ← Back to Home
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
