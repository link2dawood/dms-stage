<?php
/* Template Name: Delete Attachments */
get_header();
?>

<div id="delete-attachments-container">
    <h2>Deleting Attachments</h2>
    <div id="delete-results"></div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function deleteAttachments() {
        $.ajax({
            url: '<?php echo admin_url("admin-ajax.php"); ?>',
            type: 'POST',
            data: {
                action: 'delete_listing_attachments',
				nonce: '<?php echo wp_create_nonce("delete_attachments_nonce"); ?>'
            },
            success: function(response) {
                console.log(response);
                if (response.success) {
                    $('#delete-results').append('<p style="color: green;">' + response.data.message + '</p>');
                } else {
                    $('#delete-results').append('<p style="color: orange;">Warning: ' + (response.data ? response.data : 'Unknown error') + '</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $('#delete-results').append('<p style="color: red;">Error: ' + error + '</p>');
            }
        });
    }
    
    setInterval(deleteAttachments, 5000);
</script>

<?php
get_footer();
