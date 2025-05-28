<?php
require('../config.php'); 
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
      <h2 class="fw-bold h-font text-center">Send a Review on your Experience with us</h2>
      <div class="h-line bg-dark"></div>
    </div>

    <div class="container">
    <div class="row justify-content-center"> <!-- Centered Row -->
        <div class="col-lg-6 col-md-6 mb-5 px-4">
            <div class="bg-white rounded shadow p-4">
                <h5 class="mb-3">We'd Love to Hear from You!</h5>
                <p class="mb-4">Thank you for staying with us. We hope you had a wonderful experience. If you have a moment, please leave us a review — your feedback helps us grow and serve you better. We look forward to welcoming you again soon!</p>

                <form id="reviewForm" method="POST">
    <div class="mb-3">
        <label class="form-label">Your Name</label>
        <input type="text" name="name" id="reviewerName" class="form-control shadow-none" required>
        <div class="form-check mt-2">
            <input class="form-check-input" type="checkbox" id="anonymousCheck">
            <label class="form-check-label" for="anonymousCheck">
                Submit as Anonymous
            </label>
        </div>
    </div>

    <div class="mb-3">
        <label class="form-label">Your Rating</label>
        <div id="starRating" class="mb-2">
            <i class="bi bi-star star" data-value="1"></i>
            <i class="bi bi-star star" data-value="2"></i>
            <i class="bi bi-star star" data-value="3"></i>
            <i class="bi bi-star star" data-value="4"></i>
            <i class="bi bi-star star" data-value="5"></i>
        </div>
        <input type="hidden" name="rating" id="ratingInput" required>
    </div>

    <div class="mb-3">
        <label class="form-label">Your Review</label>
        <textarea name="message" rows="4" class="form-control shadow-none" required></textarea>
    </div>
    <div id="message-div" class="alert" style="display:none;"></div>
    <button type="submit" class="btn btn-sm text-white custom-bg shadow-none">Submit Review</button>
</form>

<!-- Star Styles -->
<style>
    #starRating .star {
        font-size: 24px;
        color: #ccc;
        cursor: pointer;
    }
    #starRating .star.selected,
    #starRating .star.hovered {
        color: #ffc107;
    }
</style>


            </div>
        </div>
    </div>
</div>



    <?php require('inc/footer.php') ?>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Star selection logic
    const stars = document.querySelectorAll('#starRating .star');
    const ratingInput = document.getElementById('ratingInput');

    stars.forEach(star => {
        star.addEventListener('click', () => {
            const rating = star.getAttribute('data-value');
            ratingInput.value = rating;
            updateStars(rating);
        });

        star.addEventListener('mouseover', () => {
            updateStars(star.getAttribute('data-value'), true);
        });

        star.addEventListener('mouseout', () => {
            updateStars(ratingInput.value);
        });
    });

    function updateStars(rating, hover = false) {
        stars.forEach(star => {
            const val = star.getAttribute('data-value');
            star.classList.remove('selected', 'hovered');
            if (val <= rating) {
                star.classList.add(hover ? 'hovered' : 'selected');
            }
        });
    }

    // Anonymous checkbox logic
    $('#anonymousCheck').on('change', function () {
        const nameField = $('#reviewerName');
        if (this.checked) {
            nameField.prop('disabled', true).removeAttr('required');
        } else {
            nameField.prop('disabled', false).attr('required', true);
        }
    });

    // AJAX form submit
    $('#reviewForm').on('submit', function (e) {
    e.preventDefault();

    const name = $('#anonymousCheck').is(':checked') ? 'Anonymous' : $('#reviewerName').val();
    const message = $('textarea[name="message"]').val();
    const rating = $('#ratingInput').val();

    const messageDiv = $('#message-div');
    messageDiv.hide().removeClass('alert-success alert-danger').text('');

    if (!rating) {
        messageDiv.addClass('alert-danger').text("Please select a rating.").show();
        return;
    }

    $.ajax({
        url: 'ajax-request/ajax-reviews.php',
        type: 'POST',
        data: {
            name: name,
            message: message,
            rating: rating
        },
        dataType: 'json',
        success: function (response) {
            messageDiv.removeClass('alert-danger');
            if(response.status === 'success') {
                messageDiv.addClass('alert-success').text(response.message).show();
                $('#reviewForm')[0].reset();
                $('#reviewerName').prop('disabled', false).attr('required', true);
                updateStars(0); // clear stars
            } else {
                messageDiv.addClass('alert-danger').text(response.message).show();
            }
        },
        error: function () {
            messageDiv
                .removeClass('alert-success')
                .addClass('alert-danger')
                .text('Something went wrong. Please try again.')
                .show();
        }
    });
});

</script>
</html>