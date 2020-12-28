<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Source Code Review</title>

<link rel="stylesheet" type="text/css" href="styles.css" />

</head>

<body style="background-image: url('img/bl.jpg');background-size:cover;">
<p align="right" style="padding:20px;"><a href="doc.pdf">Documentation</a>
<div id="page">

    <h1>Source Code Review</h1>
    
<form class="searchForm" method="post" action="om.php">
<fieldset>
<input type="text" name="gitUrl" size="50" value="" placeholder="Enter the GIT URL of code to be scanned" id="t1">



<div id="searchInContainer">      
<table border="0" width="100%">
<tr>
<td><input type="radio" value="Git" name="cb" id="r1" onclick="git()">  Git</td>
<td><input type="radio" value="SVN" name="cb" id="r1" onclick="mount()">Code Monted</td>
</tr>
<tr><td colspan="2">&nbsp;</td></tr>
</table>

<table border=0 width=100%>
<tr>
<td>
 Central File Upload Library
<br>
<input type="text" name="upload" size="50" value="" placeholder="Leave Blank if there is no Central Library for File Upload">

</td>
</tr>
</table>
<input type="submit" class="btn btn-primary btn-cons" value="Scan">

            </div>
            
        </fieldset>

</form>            


    <div id="resultsDiv"></div>



</div>

<div id="page">


    <div id="resultsDiv"></div>



</div>
</div>

<div class="footer" align="center"><a href="http://test3.my.infoedge.com/manager/demo/demo/om/Documentation.pdf"> Documentation </a></div>


<script type="text/javascript">
function mount() { 
document.getElementById('t1').disabled=true;
document.getElementById('t1').value='';


}

function git() {
document.getElementById('t1').disabled=false;
}

</script>
</body>
</html>
