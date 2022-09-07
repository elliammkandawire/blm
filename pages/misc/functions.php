<?php                   
   function showerror($msg){ ?>
    <div class="alert alert-danger alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php foreach($msg as $key => $mssg){?> 
            <p class="">-&nbsp;<?= $mssg ?></p>
        <?php } ?> 
    </div>
<?php } ?>

<?php                   
   function showSuccess($success){ ?>
    <div class="alert alert-success alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <?php foreach($success as $key => $mssg){?> 
            <p class="">-&nbsp;<?= $mssg ?></p>
        <?php } ?> 
    </div>
<?php } ?>

<?php
    function showSessError($sess){ ?>
       <div class="alert alert-danger alert-dismissable">
	       <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php foreach($sess as $key => $sesse){?> 
                <p class="">-&nbsp;<?= $sesse ?></p>
            <?php } ?> 
        </div>
<?php } ?>

<?php
    function showUpdateSuccess($msg){ ?>
        <div class="alert alert-success alert-dismissable">
	        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	        <a href="user-details.php">Your details have been updated Successfully!</a>
	    </div>
<?php } ?>

<?php
function showDeleteSuccess($msg){ ?>
    <div class="alert alert-success alert-dismissable">
	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
	     <a href="logout.php">Your profile has been deleted Successfully</a>
	</div>
<?php } ?>