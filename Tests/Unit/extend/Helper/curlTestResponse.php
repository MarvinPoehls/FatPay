<?php

if (!isset($_REQUEST['billing_lastname']) || $_REQUEST['billing_lastname'] === 'Failed') {
    echo json_encode(false);
}
else {
    echo json_encode(true);
}