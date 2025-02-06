<?php

function RtError($message): void
{
    echo json_encode($message);
    return;
}

?>