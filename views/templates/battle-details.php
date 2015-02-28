<div class='wrapper'>
<?php
$unixtime = strtotime($battle['date']);
$date = date("F j, Y", $unixtime);
?>

<a href="./map"><button class='btn btn-primary'>< Back To Homepage</button></a>
<h1 class='page-header'><?php echo $battle['name']; ?></h1>
<h3><?php echo $battle['location']; ?></h3>
<p><?php echo $battle['outcome']; ?></p>
<p><?php echo $date; ?></p>
<!-- nl2br() preserves line breaks in text echoed from DB-->
<p><?php echo nl2br($battle['description']);?></p>


<?php foreach($factions as $faction):?>

  <h2><?php echo $faction['factionName'];?></h2>
  <?php foreach($faction['notablePersons'] as $notablePerson):?>
    <h3><?php echo $notablePerson['name'];?></h3>
    <img src="<?php echo $notablePerson['imageURL'];?>" class="person-image">
  <?php endforeach; ?>
<?php endforeach; ?>

<section class="twitter-wrapper">
  <!--Nichole's individual part, including the two files containing twitter sections-->
  <?php include('./views/inc/comment_form.php'); ?>
  <?php include('./views/inc/twitter-war.php'); ?>
</section>
