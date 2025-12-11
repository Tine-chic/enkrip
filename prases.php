<?php
session_start();

// Retrieve session messages if any
$alert = $_SESSION['alert'] ?? '';
unset($_SESSION['alert']);

// Database connection
$conn = new mysqli("localhost", "root", "", "cipher_db");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Load key mappings from the database
$key = [];
$result = $conn->query("SELECT letter, substitute FROM susi_tb ORDER BY letter ASC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $key[$row['letter']] = $row['substitute'];
    }
}

$conn->close();
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dynamic Key Editing</title>
    <link rel="stylesheet" href="desayn.css">
</head>
<body>
<div class="container">
    <h1>Dynamic Key Editing</h1>

```
<?php if ($alert): ?>
    <div class="alert" style="background:#ffe4e1; border:1px solid #ff6b6b; color:#b22222; font-weight:bold; padding:10px; border-radius:10px; margin-bottom:15px;">
        <?php echo htmlspecialchars($alert); ?>
    </div>
<?php endif; ?>

<div class="module">
    <h2>Key Mapping Grid</h2>

    <form id="dynamicForm" method="post" action="prases.php">
        <div class="grid">
            <?php foreach ($key as $letter => $sub): ?>
                <div onclick="editKey('<?php echo $letter; ?>')">
                    <strong class="letter"><?php echo htmlspecialchars($letter); ?></strong><br>
                    <span id="sub_<?php echo $letter; ?>" class="sub"><?php echo htmlspecialchars($sub); ?></span>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="editor" style="display:none;">
            <h3>Edit Key</h3>
            <p>Selected Letter: <strong id="editLetter"></strong></p>
            <input type="text" id="editValue" maxlength="1">
            <button type="button" onclick="applyChange()">Apply</button>
        </div>

        <input type="hidden" name="updated_key" id="updated_key">
        <button name="final_update">Update All</button>
        <input type="hidden" name="redirect" value="dynamic.php">
    </form>
</div>

<div class="module">
    <p>
        <a href="enkrip.php">Go to Encrypt</a> |
        <a href="dekrip.php">Go to Decrypt</a>
    </p>
</div>
```

</div>

<script>
    // Initialize key data for JavaScript
    let keyData = <?php echo json_encode($key, JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP); ?>;
</script>

<script src="skrip.js"></script>

</body>
</html>
