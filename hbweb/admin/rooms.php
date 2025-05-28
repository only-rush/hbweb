<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Room</title>
    <style>
        /* Reset some basic styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 5;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea, #764ba2);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #fff;
        }

        .container {
            background: rgba(255, 255, 255, 0.1);
            padding: 40px 60px;
            border-radius: 40px;
            box-shadow: 1 8px 24px rgba(0,0,0,0.2);
            text-align: center;
            width: 520px;
        }

        h1 {
            margin-bottom: 30px;
            font-size: 2.2rem;
            letter-spacing: 1px;
            font-weight: 700;
        }

        a {
            display: block;
            margin: 15px 0;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
            text-decoration: none;
            font-size: 1rem;
            border-radius: 8px;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.15);
        }

        a:hover {
            background: #fff;
            color: #764ba2;
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(255, 255, 255, 0.6);
        }

        a:active {
            transform: translateY(0);
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.15);
        }

        h3 {
            color: #fff; /* Updated to match the body text color */
        }

        form {
            background-color: rgba(255, 255, 255, 0.9); /* Slightly opaque white for contrast */
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: block;
            width: 100%;
            margin-top: 20px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
            text-align: left;
            color: #333; /* Dark color for labels */
        }

        input[type="text"],
        input[type="file"],
        input[type="number"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            background-color: #5cb85c;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #4cae4c;
        }

        #message {
            margin-top: 10px;
            font-weight: bold;
            color: #fff; /* Message text color */
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php">← Back</a><br><br>

        <h3>Add Room</h3>
        <form id="roomForm" enctype="multipart/form-data">
            <label for="name">Room Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="photo_path">Room Photo:</label>
            <input type="file" id="photo_path" name="photo_path" required>

            <label for="description">Room Description:</label>
            <textarea id="description" name="description" required></textarea>

            <label for="price">Price per night:</label>
            <input type="number" id="price" name="price" step="0.01" min="0" required>

            <label for="status_id">Room Status:</label>
            <select id="status_id" name="status_id" required>
                <option value="1">Available</option>
                <option value="0">Not Available</option>
            </select>

            <h4>Select Features</h4>
            <div id="featuresContainer"></div>

            <h4>Select Facilities</h4>
            <div id="facilitiesContainer"></div>

            <button type="submit">Add Room</button>
        </form>

        <div id="message"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Fetch features and facilities for the checkboxes
        function fetchOptions() {
            $.ajax({
                url: 'ajax-request/ajax-features.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let featureHtml = '';
                    data.forEach(function(feature) {
                        featureHtml += `
                            <label><input type="checkbox" name="features[]" value="${feature.id}"> ${feature.name}</label>
                        `;
                    });
                    $('#featuresContainer').html(featureHtml);
                },
                error: function() {
                    $('#message').html('<span style="color: red;">Failed to load features.</span>');
                }
            });

            $.ajax({
                url: 'ajax-request/ajax-facilities.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let facilityHtml = '';
                    data.forEach(function(facility) {
                        facilityHtml += `
                            <label><input type="checkbox" name="facilities[]" value="${facility.id}"> ${facility.name}</label>
                        `;
                    });
                    $('#facilitiesContainer').html(facilityHtml);
                },
                error: function() {
                    $('#message').html('<span style="color: red;">Failed to load facilities.</span>');
                }
            });
        }

        // Submit the room form
        $('#roomForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this); // to handle file uploads
            $.ajax({
                url: 'ajax-request/ajax-rooms.php',
                type: 'POST',
                data: formData,
                processData: false, // to prevent jQuery from processing the data
                contentType: false, // to ensure the proper content type is sent for file uploads
                dataType: 'json',
                success: function(response) {
                    $('#message').html(`<span style="color: ${response.status === 'success' ? 'green' : 'red'};">${response.message}</span>`);
                    if (response.status === 'success') {
                        $('#roomForm')[0].reset();
                        fetchOptions(); // Reload features and facilities
                    }
                    setTimeout(() => $('#message').html(''), 3000);
                },
                error: function() {
                    $('#message').html('<span style="color: red;">AJAX request failed.</span>');
                }
            });
        });

        // Initial fetch of features and facilities
        $(document).ready(function() {
            fetchOptions();
        });
    </script>
</body>
</html>
        