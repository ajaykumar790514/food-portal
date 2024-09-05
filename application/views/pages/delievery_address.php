<div class="row">
<?php 
foreach($addresses as $address){ 
    if ($address->is_default==1) {
      echo '<input type="hidden" name="address_id_default" value="'.$address->id.'">';
   }
?>
<div class="col-lg-6 mt-2" id="<?=$address->id?>">
    <div class="card mb-lg-0">
        <div class="card-header">
        <div class="row">
             <div class="col-7">
            <h5 class="mb-0"><?= $address->contact_person_name; ?></h5>
             </div>
             <div class="col-5">
             </div>
        </div>
        </div>

        <div class="card-body">
           <address><?= $address->address_line_1.' '.$address->address_line_2.' '.$address->address_line_3.' '.$address->city.' '.$address->state.' '.$address->country.' , '.$address->pincode ; ?></address>
            <p><span class="text-dark">Landmark: </span><?= $address->landmark ?></p>
            <p><span class="text-dark">Phone: </span><?= $address->contact; ?></p>
             <a data-bs-toggle="modal" data-bs-target="#add-address-modal" data-whatever="Edit Delievery Address" href="javascript:void(0)" data-url="<?=$edit_addr_url?><?=$address->id?>" class="btn-small text-danger mr-4 "><i class="fi-rs-edit"></i> Edit</a>
            <hr>
            <button onclick="closeAddress()" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree" class="btn mb-2 btn-solid2 <?= ($address->is_default==1) ? 'btn-success  text-white' : 'bg-dark text-white' ?> delivery-btn" value="<?= $address->id ?>" style="cursor: pointer; color: white;">Deliver Here</button>
        </div>
    </div>
</div>
<?php } ?>
<div class="col-md-6 pb-4 mt-2">
    <a data-bs-toggle="modal" data-bs-target="#add-address-modal" data-bs-whatever="Add Delievery Address" data-url="<?=$edit_addr_url?>" href="javascript:void(0);" >
        <div class="bg-light border rounded  mb-3  shadow-sm text-center h-100 d-flex align-items-center">
            <h6 class="text-center m-0 w-100"><i class="fa fa-plus mb-5"></i><br><br>Add New Address</h6>
        </div>
    </a>
</div>
</div>