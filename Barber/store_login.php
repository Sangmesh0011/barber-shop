<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
     <style>body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        margin: 0;
        padding: 0;
    }
    
    .container {
        max-width: 400px;
        margin: 50px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
    }
    
    h1 {
        text-align: center;
    }
    
    label {
        display: block;
        margin-bottom: 8px;
    }
    
    input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }
    
    button {
        width: 100%;
        padding: 10px;
        background-color: #4CAF50;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    
    button:hover {
        background-color: #45a049;
    }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="process_strlogin.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" id="username1" name="username1" required>
            <label for="password">Password:</label>
            <input type="password" id="password1" name="password1" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
