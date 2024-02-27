<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Star Renting - Contact Us</title>
    <link rel="stylesheet" href="css/main.css">
    <script src="scripts/contactus.js" defer></script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <?php include("assets/nav.php")?>
    <main class="container">
        <header class="pt-3 pb-3">
            <h1 class="display-3 text-center fw-bold">Star Renting</h1>
            <h2 class="display-5 text-center fw-bold">Contact Us</h2>
        </header>
        <section>
            <section class="mx-auto" style="width: 50%; min-width: 220px;">
                <form action="mailto:keeron.spiteri.g53977@mcast.edu.mt" name="contact-form" onsubmit="return validateForm()" method="post" enctype="text/plain">
                    <label class="form-label mb-1">Name:</label>
                    <input type="name" name="name" class="form-control mb-2" placeholder="Your Name">
                    <label class="form-label mb-1">Email:</label>
                    <input type="email" name="email" class="form-control mb-2" placeholder="Email Address">
                    <label class="form-label mb-1">Title:</label>
                    <input type="title" name="title" class="form-control mb-2" placeholder="Title" >
                    <label class="form-label mb-1">Message:</label>
                    <textarea type="text" name="message" class="form-control mb-2" cols="30" rows="10" placeholder="Type your message here..."></textarea>
                    <div class="text-center">
                        <input class="mt-1 btn btn-dark" style="padding-left: 15px; padding-right: 15px;" id="submit-btn" type="submit"></input>
                    </div>
                </form>
            </section>
        </section>
    </main>
    <?php include("assets/footer.php")?>
</body>
</html>