
<?php
$br = $_POST['br'];
$totalid = $_POST['totalid'];
$mod_title = $_POST['mod_title'];
$br_split = explode(",", $br);
?>
<h2><?= ucfirst($mod_title) ?></h2>

<?php
if ($mod_title == 'active') {
    ?>
    <table border="1">
        <tr>
            <td>Branch</td>
            <td>Active</td>
        </tr>
        <?php
        foreach ($br_split AS &$split) {
            echo "<tr>";
            echo "<td class='branch-list'>";
            echo $split;
            echo "</td>";
            echo "<td>";
            ?>
            <input type="checkbox" data-role="flipswitch" name="flip-checkbox-1" id="flip-checkbox-1">
            <?php
            echo "</td>";
            echo "</tr>";
        }
        ?>
    </table>
    <?php
}
?>

<style type="text/css">
    .branch-list{
        text-align:center;
    }
</style>