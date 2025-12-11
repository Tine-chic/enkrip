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

$encrypted = '';
$plain_text = '';
$alert = '';

// Encrypt
if (isset($_POST['encrypt'])) {
    $plain_text = $_POST['plain_text'] ?? '';
    
    if (empty($plain_text)) {
        $alert = 'Error: Please enter text to encrypt.';
    } else {
        $encrypted = encrypt_text($plain_text, $key);
        $alert = 'Text encrypted successfully!';
    }
}

$conn->close();

function encrypt_text($text, $key) {
    $encrypted = '';
    foreach (str_split(strtolower($text)) as $char) {
        if (isset($key[$char])) {
            $encrypted .= $key[$char];
        } else {
            $encrypted .= $char;
        }
    }
    return $encrypted;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Encrypt Text</title>
    <link rel="stylesheet" href="desayn.css">
</head>
<body>
    <div class="container">
        <h1>Encrypt Text</h1>

        <div class="alert" style="display: <?php echo $alert ? 'block' : 'none'; ?>">
            <?php echo htmlspecialchars($alert); ?>
        </div>

        <div class="module">
            <h2>Encryption</h2>
            <form method="post" action="">
                <textarea name="plain_text" placeholder="Enter text to encrypt..."><?php echo htmlspecialchars($plain_text); ?></textarea><br />
                <button type="submit" name="encrypt" value="1">Encrypt</button>
            </form>
        </div>

        <div class="module">
            <h2>Encrypted Result</h2>
            <p id="encryptedResult"><?php echo htmlspecialchars($encrypted); ?></p>
        </div>

        <div class="module">
            <div class="nav" role="navigation" aria-label="Main navigation">

                <a class="nav-btn decrypt" href="dekrip.php" title="Decrypt">
                    <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path d="M21 2l-7 7" />
                        <circle cx="7" cy="17" r="4" />
                    </svg>
                    <span>Decrypt</span>
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
