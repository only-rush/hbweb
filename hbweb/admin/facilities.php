<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

        input[type="text"] {
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
        <a href="index.php">← Back</a>

        <h1>Add Facility</h1>

        <form id="facilityForm">
            <label for="name">Facility Name:</label>
            <input type="text" id="name" name="name" required>
            <button type="submit">Add Facility</button>
        </form>

        <div id="message"></div>

        <h3>Facility List</h3>
        <table id="facilityTable">
            <thead>
                <tr>
                    <th>Facility Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        function fetchFacilities() {
            $.ajax({
                url: 'ajax-request/ajax-facilities.php',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    let tableBody = '';
                    data.forEach(function(facility) {
                        tableBody += `
                            <tr>
                                <td>${facility.name}</td>
                                <td><button class="deleteBtn" data-id="${facility.id}">Delete</button></td>
                            </tr>
                        `;
                    });
                    $('#facilityTable tbody').html(tableBody);
                },
                error: function() {
                    $('#facilityTable tbody').html('<tr><td colspan="2">Failed to load facilities.</td></tr>');
                }
            });
        }

        $('#facilityForm').on('submit', function(e) {
            e.preventDefault();

            $.ajax({
                url: 'ajax-request/ajax-facilities.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(response) {
                    $('#message').html(`<span style="color: ${response.status === 'success' ? 'green' : 'red'};">${response.message}</span>`);
                    if (response.status === 'success') {
                        $('#facilityForm')[0].reset();
                        fetchFacilities();
                    }
                    setTimeout(() => $('#message').html(''), 2000);
                },
                error: function() {
                    $('#message').html('<span style="color: red;">AJAX request failed.</span>');
                }
            });
        });

        $('#facilityTable').on('click', '.deleteBtn', function() {
            const facilityId = $(this).data('id');

            if (confirm('Are you sure you want to delete this facility?')) {
                $.ajax({
                    url: 'ajax-request/ajax-facilities.php',
                    type: 'POST',
                    data: { id: facilityId },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            $('#message').html('<span style="color: green;">Facility successfully deleted.</span>');
                            fetchFacilities();
                            setTimeout(() => $('#message').html(''), 3000);
                        } else {
                            $('#message').html(`<span style="color: red;">${response.message}</span>`);
                        }
                    },
                    error: function() {
                        $('#message').html('<span style="color: red;">Failed to delete facility.</span>');
                    }
                });
            }
        });

        $(document).ready(function() {
            fetchFacilities();
        });
    </script>
</body>
</html>
