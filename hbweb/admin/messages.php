<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Reset some basic styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 5px;
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
            box-shadow: 1 8px 24px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 600px;
        }

        h2 {
            margin-bottom: 20px;
            font-size: 2rem;
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

        .tab {
            cursor: pointer;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            background: rgba(255, 255, 255, 0.2);
            color: #fff;
        }

        .tab:hover {
            background-color: #f0f0f0;
        }

        .active-tab {
            background-color: #fff;
            color: #764ba2;
            font-weight: bold;
        }

        .message-box {
            border: 1px solid #ccc;
            padding: 15px;
            margin-left: 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
        }

        #message-content {
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Inbox</h2>
        <a href="index.php">← Back</a>
        <div style="display: flex;">
            <div style="width: 200px;" id="subject-list"></div>
            <div style="flex: 1;" class="message-box" id="message-content">
                <p>Select a message to view its content.</p>
            </div>
        </div>
    </div>

    <script>
        // Fetch message subjects on page load
        $(document).ready(function() {
            $.get('ajax-request/ajax_fetch_messages.php', function(data) {
                const messages = JSON.parse(data);
                let html = '';
                messages.forEach((msg, index) => {
                    html += `<div class="tab" data-id="${msg.id}">${msg.subject}</div>`;
                });
                $('#subject-list').html(html);
            });

            // Click event to load full message
            $('#subject-list').on('click', '.tab', function() {
                $('.tab').removeClass('active-tab');
                $(this).addClass('active-tab');

                const id = $(this).data('id');
                $.get('ajax-request/ajax_fetch_message.php', { id: id }, function(data) {
                    const msg = JSON.parse(data);
                    $('#message-content').html(`
                        <h4>${msg.subject}</h4>
                        <p><strong>From:</strong> ${msg.name}</p>
                        <p>${msg.message}</p>
                    `);
                });
            });
        });
    </script>
</body>
</html>
