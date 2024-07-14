<?php
$dep=$_GET["dep"];
$sem=$_GET["sem"];
echo "<option>Choose Subject</option>";


if($dep=="CSE" && $sem=="4")
{
?>
<option>Probability and Statistics (Lab Integrated)</option>
<option>Distributed and Cloud Computing (Lab integrated)</option>
<option>Web Development Frameworks (Lab integrated)</option>
<option>Artificial Intelligence (Lab integrated)</option>
<option>Ethical Hacking (Lab integrated)</option>
<option>Cloud Foundations (Lab integrated)</option>
<option>Data Science using Python (Lab integrated)</option>
<option>MERN Stack Development (Lab integrated)</option>
<option>Cloud Architecting (Lab integrated)</option>
<option>UI/UX Design (Lab integrated)</option>
<?php
}
elseif($dep=="AI-DS" && $sem=="4")
{
?>
<option>Probability and Statistics (Lab Integrated)</option>
<option>Operating System (Lab Integrated)</option>
<option>Distributed Cloud Computing (Lab Integrated)</option>
<option>Machine Learning (Lab Integrated)</option>
<option>Web Development Framework (Lab Integrated)</option>
<option>Business Intelligence & Analytics (Lab Integrated)</option>
<option>AI & ML for Health Care (Lab Integrated)</option>

<?php
}
elseif($dep=="ECE" && $sem=="4")
{
?>
<option>Control Engineering</option>
<option>Linear Integrated Circuits</option>
<option>Analog and Digital Communication</option>
<option>Probability and Random Process</option>
<?php
}
elseif($dep=="ECE" && $sem=="6")
{
?>
<option>Embedded Systems (Lab Integrated)</option>
<option>Digital Signal Processing Laboratory</option>
<?php
}
elseif($dep=="CSE" && $sem=="6")
{
?>
<option>Compiler Design (Lab integrated)</option>
<option>Security Laboratory</option>
<?php
}
elseif($dep=="AI-DS" && $sem=="6")
{
?>
<option>Knowledge Engineering (Lab Integrated)</option>
<?php
}
elseif($dep=="MECH" && $sem=="6")
{
    ?>
    <option>Computer Aided Design and Manufacturing</option>
    <option>Design and Fabrication Project and Internship</option>
    <option>Advanced Product Lifecycle Management Laboratory</option>
    <option>Product Data Management</option>
    <?php
    }
elseif($dep=="CSE(CS)" && $sem=="4")
{
?>
<option>Linear Algebra and Number Theory (Lab Integrated)</option>
<option>Distributed and Cloud Computing (Lab integrated)</option>
<option>Cyber Security Essentials (Lab Integrated)</option>
<option>Artificial Intelligence (Lab Integrated)</option>
<option>Web Security (Lab Integrated)</option>
<?php
}
elseif($dep=="CSE" && $sem=="8")
{
    ?>
   <option>project work</option>
<?php
}
elseif($dep=="ECE" && $sem=="8")
{
    ?>
   <option>Project work</option>
  
<?php
}
elseif($dep=="MECH" && $sem=="8")
{
    ?>
    <option>PROJECT WORK</option>
<?php
}
elseif($dep=="AI-DS" && $sem=="8")
{
    ?>
    <option>Project Work</option>
<?php
}
?>