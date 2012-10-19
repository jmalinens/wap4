<?php
if(isMobile()):
    $this->load->view('v2/includes/footer_mobile', $data);
else:
    $this->load->view('v2/includes/footer_web', $data);
endif;
?>
</div>
</div>
</body>
</html>