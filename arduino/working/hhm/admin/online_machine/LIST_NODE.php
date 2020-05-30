<div class="LIST-NODE" id="LIST-NODE-<?= $ID ?>" style="display:none;">
  <div class="LN-MID" id="LN-MachineID-<?= $ID ?>" onclick="edit_data(<?=$ID?>)"><?= $MachineID ?></div>
  <div class="LN-DESC-AREA" style="float:left;width:calc(100% - 85px);">
    <div class="LN-DESC" id="LN-Title-<?= $ID ?>"><img src="../../img/title.svg" align="absmiddle" class="LN-ICON" title="Title"><?= $Title ?></div>
    <div class="LN-DESC" id="LN-Category-<?= $ID ?>"><img src="../../img/category.svg" align="absmiddle" class="LN-ICON" title="Category"><?= $Category ?></div>
    <div class="LN-DESC LN-CAMERA">
      <div id="LN-CameraID1-<?= $ID ?>"><img src="../../img/camera1.svg" align="absmiddle" class="LN-ICON" title="Camera 1"><?=$CameraID1?></div>
      <div id="LN-CameraID2-<?= $ID ?>"><img src="../../img/camera2.svg" align="absmiddle" class="LN-ICON" title="Camera 2"><?=$CameraID2?></div>
      <div id="LN-CameraID3-<?= $ID ?>"><img src="../../img/camera3.svg" align="absmiddle" class="LN-ICON" title="Camera 3"><?=$CameraID3?></div>

    </div>
    <div class="LN-DESC">
      <div style="float:left" id="LN-Play_Mode-<?= $ID ?>"><img src="../../img/play_mode.svg" align="absmiddle" class="LN-ICON" title="Play Mode"><?= $Play_Mode ?></div>
      <div style="float:left" id="LN-Max_Second-<?= $ID ?>"><img src="../../img/timing.svg" align="absmiddle" class="LN-ICON" title="Max Second"><?= $Max_Second ?></div>
      <div style="float:left" id="LN-Price-<?= $ID ?>"><img src="../../img/token.svg" align="absmiddle" class="LN-ICON" title="Token"><?= $Price ?></div>
    </div>
    <div class="LN-DESC" id="LN-Grab_Weight-<?= $ID ?>"><img src="../../img/weight.svg" align="absmiddle" class="LN-ICON" title="Grab Weight"><?= $Grab_Weight ?></div>
    <div style="display:none;" id="LN-Location-<?= $ID ?>"><?= $Location ?></div>
    <div style="display:none;" id="LN-Tag-<?= $ID ?>"><?= $Tag ?></div>
    <div style="display:none;" id="LN-Active-<?= $ID ?>"><?= $Active ?></div>
  </div>
  <div class="LN-PIC" id="LN-PIC-<?= $ID ?>"><img src="../../machine_pic/<?=$ID?>/<?=$ID?>_File1.jpg">&nbsp;</div>
</div>
