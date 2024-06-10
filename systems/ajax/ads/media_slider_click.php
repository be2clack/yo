<?php

update("update uni_sliders set sliders_click=sliders_click+? where sliders_id=?", [1, intval($_POST["id"]) ]);

?>