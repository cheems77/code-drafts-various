<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Pin Generator Tool</title>
<link rel="stylesheet" type="text/css" href="../css/form.css" />
<script type="text/javascript" src="../js/jquery.js"></script>
<script type="text/javascript" src="../js/pincheck.js"></script>
</head>
<body >
<h1><?php echo $page_title; ?></h1>

<form method="post" id="pingenerate">
<div class="row">
	<label for="sel_PINQuantity" id="PINQuantity-ariaLabel">PIN Quantity</label>
	<select id="sel_pinquantity" name="sel_pinquantity" aria-labelledby="PINQuantity-ariaLabel">
		<?php for ($x=1;$x<=$pin_quantity;$x++) { ?>
			<option value="<?php echo $x; ?>"><?php echo $x; ?></option>	
		<?php } ?>
	</select>
</div>
<div class="row">
<input type="submit" value="Generate PIN" id="btnSubmit" />
</div>
<div class="success"></div>
</form>
    </body>
    </html>

