<?php

include('lib/common.php');
// written by carol cheung ccheung39@gatech.edu

if (!isset($_SESSION['email'])) {
    header('Location: clerk_menu.php');
    exit();
}

//Check category and subtype and forward accordingly
//Power Tools, Garden, Ladder. Note: Hand tools are embedded in add_tools.php
if ($_SESSION['subtype'] == 'Drill'){
    include 'add_tool_drill.php';
} else if ($_SESSION['subtype'] == 'Saw'){
    include 'add_tool_saw.php';
} else if ($_SESSION['subtype'] == 'Sander'){
    include 'add_tool_sander.php';
} else if ($_SESSION['subtype'] == 'Air-Compressor'){
    include 'add_tool_aircompressor.php';
} else if ($_SESSION['subtype'] == 'Mixer'){
    include 'add_tool_mixer.php';
} else if ($_SESSION['subtype'] == 'Generator'){
    include 'add_tool_generator.php';

} else if ($_SESSION['subtype'] == 'Digger') {
    include 'add_tool_digger.php';
} else if ($_SESSION['subtype'] == 'Pruner'){
    include 'add_tool_pruner.php';
} else if ($_SESSION['subtype'] == 'Rakes'){
    include 'add_tool_rakes.php';
} else if ($_SESSION['subtype'] == 'Wheelbarrows'){
    include 'add_tool_wheelbarrows.php';
} else if ($_SESSION['subtype'] == 'Striking'){
    include 'add_tool_striking.php';

} else if ($_SESSION['subtype'] == 'Straight'){
    include 'add_tool_ladder.php';
} else if ($_SESSION['subtype'] == 'Step'){
    include 'add_tool_ladder.php';
}
?>
