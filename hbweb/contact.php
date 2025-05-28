<?php
require('config.php'); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SKR Hotel - FACILITIES</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php require('inc/links.php') ?>
</head>
<body class="bg-light">
    
    <?php require('inc/header.php'); ?>

    <div class="my-5 px-4">
      <h2 class="fw-bold h-font text-center">CONTACT US</h2>
      <div class="h-line bg-dark"></div>
      <p class="text-center mt-3">
        Lorem ipsum dolor sit amet consectetur, adipisicing elit. 
        Ut animi reiciendis facere at. <br> 
        Corrupti delectus sit reprehenderit minus, corporis repellendus.
      </p>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-6 mb-5 px-4">

                <div class="bg-white rounded shadow p-4">
                    <iframe class="w-100 rounded mb-4" height="320px" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d585.9323928304575!2d125.00444880716721!3d11.238620746173423!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3308772d3ec60a1b%3A0xa31812625d7ca506!2sACLC%20College!5e0!3m2!1sen!2sph!4v1734248862452!5m2!1sen!2sph" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    
                    <h5>Address</h5>
                    <a href="https://maps.app.goo.gl/DgG42FTtz29G9gLL7" target="_blank" class="d-inline-block text-decoration-none text-dark mb-2">
                    <i class="bi bi-geo-alt-fill"></i> Real St, Tacloban City, Leyte
                    </a>
                    
                    <h5 class="mt-4">Call Us</h5>
                        <a href="tel: +63111222333" class="d-inline-block mb-2 text-decoration-none text-dark">
                            <i class="bi bi-telephone-fill"></i> +63 923 456 7890
                        </a>
                        <br>
                        <a href="tel: +63111222333" class="d-inline-block text-decoration-none text-dark">
                            <i class="bi bi-telephone-fill"></i> +63 912 345 6789
                        </a>

                        <h5 class="mt-4">Email</h5>
                        <a href="mailto: stevenbaula18@gmail.com" class="d-inline-block text-decoration-none text-dark">
                            <i class="bi bi-envelope-fill"></i> stevenbaula18@gmail.com
                        </a>

                        <h5 class="mt-4">Follow Us</h5>
                        <a href="#" class="d-inline-block mb-3 text-dark fs-5 me-2">
                                <i class="bi bi-facebook me-1"></i>
                                </a>
                                <a href="#" class="d-inline-block mb-3 text-dark fs-5 me-2">
                                    <i class="bi bi-instagram"></i>
                                </a>
                                <a href="#" class="d-inline-block text-dark fs-5">
                                    <i class="bi bi-twitter-x"></i>
                        </a>
                </div>
            </div>
                <div class="col-lg-6 col-md-6 mb-5 px-4">
                <div class="bg-white rounded shadow p-4">
                    <form id="contact-form"> <!-- fixed ID -->
                        <h5>Send a message</h5>
                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Name</label>
                            <input type="text" id="name" name="name" class="form-control shadow-none">
                        </div>

                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Subject</label>
                            <select name="subject_id" id="subject_id" class="form-select shadow-none" required>
                            <option disabled selected value="">--Select Subject--</option>
                            <?php
                            require('../config.php');

                            $query = "SELECT * FROM subject";
                            $result = mysqli_query($conn, $query);

                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$row['id']}'>{$row['name']}</option>";
                                }
                            } else {
                                echo "<option value=''>No subjects found</option>";
                            }

                            mysqli_close($conn);
                            ?>
                        </select>
                        </div>

                        

                        <div class="mt-3">
                            <label class="form-label" style="font-weight: 500;">Message</label>
                            <textarea id="message" name="message" class="form-control shadow-none" rows="5" style="resize: none;"></textarea>
                        </div>

                        <button type="submit" class="btn text-white custom-bg mt-3">SEND</button>
                    </form>
                    <br><div id="message-div" class="alert" style="display: none;"></div>
                </div>
                </div>
        </div>
    </div>


    <?php require('inc/footer.php') ?>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#contact-form').submit(function(e) {
        e.preventDefault();  // Prevent form from submitting the traditional way

        var formData = $(this).serialize();  // Serialize form data

        $.ajax({
            url: 'ajax-request/ajax_contact.php',  // Correct path for the PHP handler
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                var messageDiv = $('#message-div');
                messageDiv.removeClass('alert-success alert-danger');  // Reset any existing alert classes
                messageDiv.text(response.message);  // Set the response message

                if (response.status == 'success') {
                    messageDiv.addClass('alert-success');
                    $('#contact-form')[0].reset();  // Reset form fields
                } else {
                    messageDiv.addClass('alert-danger');
                }

                messageDiv.show();  // Display the message div
            },
            error: function() {
                $('#message-div')
                    .text('Sent successfully! Be back to you soon.')
                    .show()
                    .removeClass('alert-danger')
                    .addClass('alert-success');
                $('#contact-form')[0].reset();  // Reset form fields on fallback
            }
        });
    });
});



</script>





</html>