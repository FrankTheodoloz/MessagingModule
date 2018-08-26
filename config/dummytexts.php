<?php
/**
 * Subject: Provides dummy texts for demo data. Just for fun.
 * User: Frank
 * Date: 24/08/2018
 * Time: 15:31
 */

global $subList;
$subList = [];

/***
 * fctGetRandomText: Return random texts
 * @return mixed
 * @throws Exception
 */
function fctGetRandomText()
{
    $text = "Per nascetur. Suspendisse cum.\r\nLacinia cras auctor. Sed.\r\nMus sociis odio.\r\nConvallis nulla nascetur. Commodo montes eu donec.\r\nSuscipit sodales nam. Aenean, porta.\r\nFelis sociis consequat integer venenatis.\r\nTempor risus sociis malesuada. Primis dignissim arcu.\r\nQuisque litora torquent commodo etiam. Pharetra dictum.\r\nDictum at lacus vulputate pharetra scelerisque.\r\nCurae; consectetuer malesuada dignissim mollis aptent. Nibh.\r\nProin inceptos, dictum.\r\nElit hymenaeos. Metus pretium porttitor enim molestie. Scelerisque dolor.\r\nMauris ipsum vitae fames dapibus.\r\nPorttitor sagittis inceptos. Tempus hendrerit.\r\nVolutpat hendrerit habitasse.\r\nMolestie mollis non primis nullam.\r\nTempus orci donec.\r\nEleifend. Pede iaculis.\r\nFaucibus sodales viverra.\r\nFames eget, blandit vitae et. Taciti.\r\nLaoreet aliquet. Hac.\r\nElementum id et.\r\nMetus mattis curae;.\r\nOrci. Litora senectus.\r\nMagna mus.\r\nUllamcorper condimentum, nisi dapibus.\r\nDuis ad. Hendrerit. Potenti ante id. Tellus quam class nec, integer vel.\r\nAliquet habitasse.\r\nVelit primis nascetur dolor.\r\nConvallis praesent interdum elit.\r\nProin lobortis natoque.\r\nTempus orci magna.\r\nMollis tempus.\r\nOrnare molestie euismod per.\r\nClass eleifend suspendisse.\r\nIpsum duis lectus.\r\nPosuere rhoncus tempus.\r\nMollis posuere lectus fusce.\r\nViverra rutrum. Auctor.\r\nEtiam volutpat.\r\nDui. Erat gravida ligula morbi id odio semper potenti inceptos neque lectus.\r\nFacilisi dui morbi magna nam turpis, vestibulum feugiat sit parturient turpis ornare nisi dis dis quis sociosqu, volutpat aptent eu eu dictum dignissim.\r\nAenean placerat conubia a vehicula imperdiet odio. Risus venenatis sapien ipsum fringilla duis varius sociis lectus. Dolor laoreet eleifend.\r\nEnim lacus est. Conubia tincidunt lacinia, ridiculus. Gravida per erat per lorem egestas vestibulum tempor.\r\nSapien montes curabitur. Nec Iaculis. Proin mi ipsum nullam pharetra nostra aenean eros neque fringilla urna urna dolor elementum accumsan metus quam feugiat dapibus. Dictum. Nonummy nec.\r\nHabitasse ultrices nullam sollicitudin. Quis sem morbi auctor, volutpat. Arcu habitant potenti. Nisi venenatis, fermentum, cras sem.\r\nLobortis. Magna augue dolor augue scelerisque sed leo nunc risus. Lorem viverra nonummy pretium magnis mi cursus dolor sagittis dictum faucibus fusce nisl curabitur.\r\nCursus erat.\r\nSem placerat.\r\nDolor Urna.\r\nCursus lorem.\r\nCubilia eget.\r\nProin turpis.\r\nEleifend metus.\r\nInterdum tellus.\r\nIn hac.\r\nMattis malesuada.\r\nUrna mus.\r\nDui cubilia.\r\nIn quisque.\r\nAugue. Nascetur.\r\nTellus dignissim.\r\nCubilia ad.\r\nErat vel.\r\nMagna convallis.\r\nVolutpat tempor.\r\nDiam consequat.\r\nLuctus. Ante rhoncus luctus diam mi cursus. Dis platea sapien.\r\nCursus proin vivamus est ridiculus At torquent risus.\r\nLibero elit duis erat urna non cursus sed.\r\nA vulputate parturient egestas duis sit. Condimentum.\r\nRhoncus faucibus dictum. Volutpat lacinia ultrices per.\r\nPellentesque non pede lobortis pharetra bibendum, felis blandit curae;.\r\nConsectetuer malesuada placerat vulputate. Libero.\r\nQuisque ac et, amet. Inceptos euismod. Lorem sapien. Lorem justo. Vivamus.\r\nSenectus a fames orci, donec, conubia. Habitant sagittis. Convallis etiam.\r\nAenean lobortis primis bibendum per.";
    $separator = "\r\n";
    $line = strtok($text, $separator);
    while ($line !== false) {
        $sentences[] = $line;
        $line = strtok($separator);
    }
    $max = sizeof($sentences);
    $randomLine = random_int(0, $max - 1);
    return $sentences[$randomLine];
}

/***
 * fctGetRandomSubject: Return random subjects which *should have...* btw 5 and 32 char length
 * @return mixed
 * @throws Exception
 */
function fctGetRandomSubject()
{
    global $subList;

    do {
        $subject = fctGetRandomText();
    } while (strlen($subject) > 32 || strlen($subject) < 5 || array_key_exists($subject, $subList));

    $subList[] = $subject;
    //echo $subject . "(" . strlen($subject) . ")<br/>"; //DEBUG
    return $subject;
}
