<?php

// Verifica si existe la variable $success_msg
if (isset($success_msg)) {
   // Itera a través de cada mensaje de éxito en el array $success_msg
   foreach ($success_msg as $success_msg) {
      // Genera un script de JavaScript que muestra una alerta de tipo "success" en SweetAlert con el mensaje de éxito correspondiente
      echo '<script>swal("' . $success_msg . '", "" ,"success");</script>';
   }
}

// Verifica si existe la variable $warning_msg
if (isset($warning_msg)) {
   // Itera a través de cada mensaje de advertencia en el array $warning_msg
   foreach ($warning_msg as $warning_msg) {
      // Genera un script de JavaScript que muestra una alerta de tipo "warning" en SweetAlert con el mensaje de advertencia correspondiente
      echo '<script>swal("' . $warning_msg . '", "" ,"warning");</script>';
   }
}

// Verifica si existe la variable $info_msg
if (isset($info_msg)) {
   // Itera a través de cada mensaje de información en el array $info_msg
   foreach ($info_msg as $info_msg) {
      // Genera un script de JavaScript que muestra una alerta de tipo "info" en SweetAlert con el mensaje de información correspondiente
      echo '<script>swal("' . $info_msg . '", "" ,"info");</script>';
   }
}

// Verifica si existe la variable $error_msg
if (isset($error_msg)) {
   // Itera a través de cada mensaje de error en el array $error_msg
   foreach ($error_msg as $error_msg) {
      // Genera un script de JavaScript que muestra una alerta de tipo "error" en SweetAlert con el mensaje de error correspondiente
      echo '<script>swal("' . $error_msg . '", "" ,"error");</script>';
   }
}

?>
