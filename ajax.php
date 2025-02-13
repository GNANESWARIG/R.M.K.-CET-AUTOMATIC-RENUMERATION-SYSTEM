<?php
$dep=$_GET["dep"];
$sem=$_GET["sem"];
echo "<option>Choose Subject</option>";
if($dep=="CSE" && $sem=="3")
{
?>

<?php
}
elseif($dep=="AI-DS" && $sem=="3")
{
?>

<?php
}
elseif($dep=="ECE" && $sem=="3")
{
?>

<?php
}
elseif($dep=="ECE" && $sem=="5")
{
?>

<?php
}
elseif($dep=="CSE" && $sem=="5")
{
?>

<?php
}
elseif($dep=="AI-DS" && $sem=="5")
{
?>

<?php
}
elseif($dep=="CSE(CS)" && $sem=="3")
{
?>

<?php
}
elseif($dep=="CSE" && $sem=="7")
{
    ?>
   <option>Data Analytics</option>
<?php
}
elseif($dep=="ECE" && $sem=="7")
{
    ?>
   <option>RF and Microwave Engineering(Lab Integrated)</option>
   <option>Optical Communication and Networks(Lab Integrated)</option>
   <option>Professional Readiness for Innovation, Employability and Entrepreneurship</option>
<?php
}
elseif($dep=="MECH" && $sem=="7")
{
?>
<option>Simulation and Analysis Laboratory</option>
<option>Mechatronics Laboratory</option>
<?php
}

elseif($dep=="AI-DS" && $sem=="7")
{
    ?>
    <option>Deep Learning Techniques (Lab Integrated)</option>
    <option>Capstone Projects</option>
    <option>Capstone Projects(Minors)</option>
    <option>Distributed Cloud Computing(Lab Integrated)</option>
    <option>Professional readiness for Innovation, Employability and Entrepreneurship</option>
<?php
}
?>