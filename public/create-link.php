<?php
// Remove existing link if it exists
if (file_exists('/home/bukinist/domains/bukinistebi.ge/public_html/public/storage')) {
    unlink('/home/bukinist/domains/bukinistebi.ge/public_html/public/storage');
}

// Create a new symbolic link
if (symlink('/home/bukinist/domains/bukinistebi.ge/public_html/storage/app/public', '/home/bukinist/domains/bukinistebi.ge/public_html/public/storage')) {
    echo "Symbolic link recreated successfully.";
} else {
    echo "Failed to recreate symbolic link.";
}
?>
