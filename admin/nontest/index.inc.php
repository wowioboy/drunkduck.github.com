<a href="<?=$_SERVER['PHP_SELF']?>?filter=1">Filter Out Entries</a>
<?


if ( $_GET['filter'] )
{
  if ( isset($_GET['filterout']) ) {
    db_query("UPDATE nontest_entries SET filterout=1 WHERE id='".(int)$_GET['filterout']."'");
  }
  $res = db_query("SELECT * FROM nontest_entries WHERE filterout='0' ORDER BY id ASC");
  ?>
  <table border="1" cellpadding="0" cellspacing="0">
  <?
  while( $row = db_fetch_object($res) )
  {
    ?>
    <tr>
      <td align="center">
        <img src="/nontest/entries/1/<?=$row->id?>.<?=$row->image_ext?>">
        <br>
        <a href="<?=$_SERVER['PHP_SELF']?>?filter=1&filterout=<?=$row->id?>">Remove Entry</a>
        <br>
        <?=$row->user_id?>
      </td>
    </tr>
    <?
  }
  ?>
  </table>
  <?
}
?>