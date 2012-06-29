<?php
if(isMobile()):
    $this->load->view('v2/includes/header_mobile', $this->data);
else:
    $this->load->view('v2/includes/header_web', $this->data);
endif;
?>