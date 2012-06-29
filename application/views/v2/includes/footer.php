<?php
if(isMobile()):
    $this->load->view('v2/includes/footer_mobile', $this->data);
else:
    $this->load->view('v2/includes/footer_web', $this->data);
endif;
?>
</div>
</div>
</body>
</html>