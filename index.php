<?php
// /index.php

// Ito ang sasalo sa sinumang magta-type ng main folder directory natin.
// Ididiretso niya agad ang user sa public dashboard (na magre-redirect naman sa login page kung walang session).
header("Location: public/index");
exit;
?>