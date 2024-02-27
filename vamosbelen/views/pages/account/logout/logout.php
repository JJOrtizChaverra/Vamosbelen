<?php

session_destroy();

echo '<script>
    sweetAlert("loading", "");
    localStorage.removeItem("token_user");
    window.location.href = "' . $path . 'account&login#header" 
</script>';

?>