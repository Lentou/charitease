<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<?php 
    if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
?>
    <script>
        swal({
            title: '<?php echo $_SESSION['status']; ?>',
            text: '<?php echo $_SESSION['status_text']; ?>',
            icon: '<?php echo $_SESSION['status_code']; ?>',
            button: 'Submit'
        });
    </script>
<?php 
        unset($_SESSION['status']);
        unset($_SESSION['status_text']);
        unset($_SESSION['status_code']);
    } 
?>