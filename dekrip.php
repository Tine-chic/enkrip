<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "cipher_db");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load key
$key = [];
$result = $conn->query("SELECT letter, substitute FROM susi_tb ORDER BY letter ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $key[$row['letter']] = $row['substitute'];
    }
}

$decrypted = '';
$cipher_text = '';
$alert = '';

// Decrypt
if (isset($_POST['decrypt'])) {
    $cipher_text = $_POST['cipher_text'] ?? '';
    
    if (empty($cipher_text)) {
        $alert = 'Error: Please enter text to decrypt.';
    } else {
        $decrypted = decrypt_text($cipher_text, $key);
        $alert = 'Text decrypted successfully!';
    }
}

$conn->close();

function decrypt_text($text, $key) {
    $reverse_key = array_flip($key);
    $decrypted = '';
    foreach (str_split(strtolower($text)) as $char) {
        if (isset($reverse_key[$char])) {
            $decrypted .= $reverse_key[$char];
        } else {
            $decrypted .= $char;
        }
    }
    return $decrypted;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Decrypt Text</title>
    <link rel="stylesheet" href="desayn.css">
</head>
<body>
    <div class="container">
        <h1>Decrypt Text</h1>

        <div class="alert" style="display: <?php echo $alert ? 'block' : 'none'; ?>; background:#ffe4e1; border:1px solid #ff6b6b; color:#b22222; font-weight:bold; padding:10px; border-radius:10px; margin-bottom:15px;">
            <?php echo htmlspecialchars($alert); ?>
        </div>

        <div class="module">
            <h2>Decryption Input</h2>
            <form method="post" action="">
                <textarea name="cipher_text" placeholder="Enter text to decrypt..."><?php echo htmlspecialchars($cipher_text); ?></textarea><br>
                <button type="submit" name="decrypt" value="1">Decrypt</button>
            </form>
        </div>

        <div class="module">
            <h2>Decrypted Result</h2>
            <p id="decryptedResult"><?php echo htmlspecialchars($decrypted); ?></p>
        </div>

        <div class="module">
            <div class="nav" role="navigation" aria-label="Main navigation">
                <a class="nav-btn encrypt" href="enkrip.php" title="Encrypt">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="10" rx="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <span>Encrypt</span>
                </a>    

                <a class="nav-btn edit" href="dynamic.php" title="Edit Key">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M3 21l3-1 11-11 1-3-3 1L4 20z" />
                        <path d="M14 7l3 3" />
                    </svg>
                    <span>Edit</span>
                </a>
            </div>
        </div>
    </div>

    <script src="skrip.js"></script>
</body>
</html>
