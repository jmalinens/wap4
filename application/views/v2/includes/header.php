<?php
if(isMobile()):
    $this->load->view('v2/includes/header_mobile', $data);
else:
    $this->load->view('v2/includes/header_web', $data);
endif;
?>