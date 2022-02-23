
<!--Display the error messages above the form-->
<?php
if (count($errors) > 0) :?>
    <div class="error">
        <?php foreach ($errors as $error) : ?>
            <p><?php echo "<p style='text-align: center'>". $error . "</p>" ?></p>
        <?php endforeach ?>
    </div>
<?php  endif ?>

<?php

