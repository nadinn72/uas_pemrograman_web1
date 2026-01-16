<?php
// generate-new-hash.php
$passwords = [
    'admin@film.com' => 'admin123',
    'user@film.com' => 'user123'
];

foreach ($passwords as $email => $password) {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    echo "UPDATE users SET password = '$hash' WHERE email = '$email';<br>";
}
?>